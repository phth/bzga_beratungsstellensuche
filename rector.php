<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\Set\ValueObject\SetList;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Rector\General\ConvertImplicitVariablesToExplicitGlobalsRector;
use Ssch\TYPO3Rector\Rector\General\ExtEmConfRector;
use Ssch\TYPO3Rector\Rector\v9\v0\InjectAnnotationRector;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;

return static function (RectorConfig $rectorConfig): void {

    $rectorConfig->sets([
        Typo3LevelSetList::UP_TO_TYPO3_11,
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_74);
    $rectorConfig->paths([
        __DIR__ .'/Classes',
        __DIR__ .'/Configuration',
        __DIR__ .'/ext_localconf.php',
        __DIR__ .'/ext_tables.php',
        __DIR__ .'/Tests',
    ]);

    $rectorConfig->skip([
        NameImportingPostRector::class => [
            __DIR__ . '/Configuration/*.php',
            __DIR__ . '/Configuration/**/*.php',
            __DIR__ . '/Classes/Domain/Model/CountryZone.php',
        ]
    ]);

    $rectorConfig->rule(ConvertImplicitVariablesToExplicitGlobalsRector::class);
    $rectorConfig->ruleWithConfiguration(ExtEmConfRector::class, [
        ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => []
    ]);
};
