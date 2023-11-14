<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Map\Leaflet;

use Bzga\BzgaBeratungsstellensuche\Domain\Map\CoordinateInterface;
use Bzga\BzgaBeratungsstellensuche\Domain\Map\MapBuilderInterface;
use Bzga\BzgaBeratungsstellensuche\Domain\Map\MapInterface;
use Bzga\BzgaBeratungsstellensuche\Domain\Map\MarkerClusterInterface;
use Bzga\BzgaBeratungsstellensuche\Domain\Map\MarkerInterface;
use Bzga\BzgaBeratungsstellensuche\Domain\Map\PopUpInterface;
use Netzmacht\JavascriptBuilder\Builder;
use Netzmacht\JavascriptBuilder\Encoder\ChainEncoder;
use Netzmacht\JavascriptBuilder\Encoder\JavascriptEncoder;
use Netzmacht\JavascriptBuilder\Encoder\MultipleObjectsEncoder;
use Netzmacht\JavascriptBuilder\Output;
use Netzmacht\JavascriptBuilder\Symfony\EventDispatchingEncoder;
use Netzmacht\LeafletPHP\Definition\Map as LeafletMap;
use Netzmacht\LeafletPHP\Definition\Raster\TileLayer;
use Netzmacht\LeafletPHP\Encoder\ControlEncoder;
use Netzmacht\LeafletPHP\Encoder\GroupEncoder;
use Netzmacht\LeafletPHP\Encoder\MapEncoder;
use Netzmacht\LeafletPHP\Encoder\RasterEncoder;
use Netzmacht\LeafletPHP\Encoder\TypeEncoder;
use Netzmacht\LeafletPHP\Encoder\UIEncoder;
use Netzmacht\LeafletPHP\Encoder\VectorEncoder;
use Netzmacht\LeafletPHP\Leaflet;
use Netzmacht\LeafletPHP\Plugins\FullScreen\FullScreenControl;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class MapBuilder implements MapBuilderInterface
{
    private function mapFactory(EventDispatcher $dispatcher): callable
    {
        return static function (Output $output) use ($dispatcher) {
            $encoder = new ChainEncoder();
            $encoder
                ->register(new MultipleObjectsEncoder())
                ->register(new EventDispatchingEncoder($dispatcher))
                ->register(new JavascriptEncoder($output, JSON_UNESCAPED_SLASHES));

            return $encoder;
        };
    }

    private function dispatcherFactory(): EventDispatcher
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new ControlEncoder());
        $dispatcher->addSubscriber(new GroupEncoder());
        $dispatcher->addSubscriber(new MapEncoder());
        $dispatcher->addSubscriber(new RasterEncoder());
        $dispatcher->addSubscriber(new TypeEncoder());
        $dispatcher->addSubscriber(new UIEncoder());
        $dispatcher->addSubscriber(new VectorEncoder());

        return $dispatcher;
    }

    public function build(MapInterface $map): string
    {
        $dispatcher = $this->dispatcherFactory();
        $mapBuilder = new Leaflet(new Builder($this->mapFactory($dispatcher)), $dispatcher, [], null);

        return $mapBuilder->build($map->getMap());
    }

    public function createMap(string $mapId): MapInterface
    {
        $map = new LeafletMap($mapId, $mapId);
        $map->setZoom(17);
        $map->setOption('fullscreenControl', true);

        $layer = new TileLayer('OpenStreetMap_Mapnik', 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
        $layer->setAttribution('&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors');
        $layer->addTo($map);
        $map->addLayer($layer);

        $fullScreenControl = new FullScreenControl('fullscreen');
        $map->addControl($fullScreenControl);

        return new Map($map);
    }

    public function createMarker(string $identifier, CoordinateInterface $coordinate): MarkerInterface
    {
        return new Marker($identifier, $coordinate);
    }

    public function createCoordinate(float $latitude, float $longitude): CoordinateInterface
    {
        return new Coordinate($latitude, $longitude);
    }

    public function createPopUp(string $identifier): PopUpInterface
    {
        return new PopUp($identifier);
    }

    public function createMarkerCluster(string $identifier, MapInterface $map): MarkerClusterInterface
    {
        $markerCluster = new MarkerCluster($identifier);
        $markerCluster->getMarkerCluster()->addTo($map->getMap());
        $map->getMap()->addLayer($markerCluster->getMarkerCluster());
        return $markerCluster;
    }
}
