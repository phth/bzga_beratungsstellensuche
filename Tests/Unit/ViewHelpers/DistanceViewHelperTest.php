<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Tests\Unit\ViewHelpers;

use Bzga\BzgaBeratungsstellensuche\Domain\Model\GeopositionInterface;
use Bzga\BzgaBeratungsstellensuche\Service\Geolocation\Decorator\GeolocationServiceCacheDecorator;
use Bzga\BzgaBeratungsstellensuche\ViewHelpers\DistanceViewHelper;
use TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;

class DistanceViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var GeolocationServiceCacheDecorator|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $geolocationService;

    /**
     * @var DistanceViewHelper|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->geolocationService = $this->getMockBuilder(GeolocationServiceCacheDecorator::class)->disableOriginalConstructor()->getMock();
        $this->subject = $this->getMockBuilder(DistanceViewHelper::class)->setMethods(['renderChildren'])->getMock();
        $this->subject->injectGeolocationService($this->geolocationService);
        $this->injectDependenciesIntoViewHelper($this->subject);
    }

    /**
     * @test
     */
    public function render()
    {
        $this->geolocationService->expects(self::once())->method('calculateDistance')->willReturn(1);
        $demandPosition = $this->getMockBuilder(GeopositionInterface::class)->getMock();
        $location = $this->getMockBuilder(GeopositionInterface::class)->getMock();
        $this->subject->setArguments([
            'demandPosition' => $demandPosition,
            'location' => $location,
        ]);
        self::assertEquals(1, $this->subject->render());
    }
}
