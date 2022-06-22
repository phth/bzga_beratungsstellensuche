<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Tests\Functional\ViewHelpers;

use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class TranslateViewHelperTest extends FunctionalTestCase
{

    /**
     * @var array
     */
    protected $coreExtensionsToLoad = ['extbase', 'fluid'];

    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/bzga_beratungsstellensuche', 'typo3conf/ext/static_info_tables'];

    protected function setUp(): void
    {
        parent::setUp();
        Bootstrap::initializeLanguageObject();
    }

    /**
     * @test
     */
    public function translateFromDefaultExtension(): void
    {
        self::assertSame('previous page', LocalizationUtility::translate('previous-page', 'bzga_beratungsstellensuche'));
    }
}
