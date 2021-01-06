<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Tests\Unit\Service\Importer\Decorator;

use Bzga\BzgaBeratungsstellensuche\Service\Importer\Decorator\ImporterRegistryDecorator;
use Bzga\BzgaBeratungsstellensuche\Service\Importer\ImporterInterface;
use TYPO3\CMS\Core\Registry;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * @author Sebastian Schreiber
 */
class ImporterRegistryDecoratorTest extends UnitTestCase
{

    /**
     * @var ImporterRegistryDecorator
     */
    protected $subject;

    /**
     * @var ImporterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $importer;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|Registry
     */
    protected $registry;

    protected function setUp(): void
    {
        $this->importer = $this->getMockBuilder(ImporterInterface::class)->getMock();
        $this->registry = $this->getMockBuilder(Registry::class)->getMock();
        $this->subject = new ImporterRegistryDecorator($this->importer, $this->registry);
    }

    /**
     * @test
     * @dataProvider contentDataProvider
     */
    public function importWithAlreadyImportedContent($content)
    {
        $hash = md5($content);
        $this->registry->expects(self::once())->method('get')->willReturn($hash);
        $this->registry->expects(self::never())->method('set');
        $this->importer->expects(self::never())->method('import');

        $this->subject->import($content);
    }

    /**
     * @test
     * @dataProvider contentDataProvider
     * @param $content
     */
    public function importUpdatedContent($content)
    {
        $hash = md5('other content');
        $this->registry->expects(self::once())->method('get')->willReturn($hash);
        $this->registry->expects(self::once())->method('set');
        $this->importer->expects(self::once())->method('import');
        $this->subject->import($content);
    }

    /**
     * @return array
     */
    public function contentDataProvider()
    {
        return [
            ['some fake content'],
        ];
    }
}
