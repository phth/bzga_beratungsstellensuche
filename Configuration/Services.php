<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Bzga\BzgaBeratungsstellensuche\Command\ImportCommand;
use Bzga\BzgaBeratungsstellensuche\Command\TruncateCommand;
use Bzga\BzgaBeratungsstellensuche\Domain\Serializer\Normalizer\EntryNormalizer;
use Bzga\BzgaBeratungsstellensuche\Domain\Serializer\Normalizer\GetSetMethodNormalizer;
use Bzga\BzgaBeratungsstellensuche\Service\Geolocation\Decorator\GeolocationServiceCacheDecorator;
use Bzga\BzgaBeratungsstellensuche\Service\Geolocation\GeolocationService;
use Bzga\BzgaBeratungsstellensuche\Service\Geolocation\GeolocationServiceInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void {
    $services = $containerConfigurator->services();
    $services->defaults()
        ->private()
        ->autowire()
        ->autoconfigure();
    $services->load('Bzga\\BzgaBeratungsstellensuche\\', __DIR__ . '/../Classes/')->exclude([
        __DIR__ . '/../Classes/Domain/Model',
    ]);

    $services->alias(GeolocationServiceInterface::class, GeolocationService::class);

    $services->set(GeolocationServiceCacheDecorator::class)->public();
    $services->set(GetSetMethodNormalizer::class)->public();
    $services->set(EntryNormalizer::class)->public();

    // Add commands
    $services->set('console.command.beratungsstellensuche_import', ImportCommand::class)
        ->tag('console.command', [
            'command' => 'bzga:beratungsstellensuche:import',
            'schedulable' => true,
        ]);
    $services->set('console.command.beratungsstellensuche_truncate', TruncateCommand::class)
        ->tag('console.command', [
            'command' => 'bzga:beratungsstellensuche:truncate',
            'schedulable' => true,
        ]);
};
