<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\Set\ValueObject\LevelSetList;
use Ssch\TYPO3Rector\Rector\General\ConvertImplicitVariablesToExplicitGlobalsRector;
use Ssch\TYPO3Rector\Rector\General\ExtEmConfRector;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;

return static function (RectorConfig $rectorConfig): void {

    $rectorConfig->sets([
        Typo3LevelSetList::UP_TO_TYPO3_11,
        LevelSetList::UP_TO_PHP_74,
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
