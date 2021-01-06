<?php

declare(strict_types=1);

use PHPUnit\Framework\MockObject\MockObject;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Ssch\TYPO3Rector\Set\Typo3SetList;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\PostRector\NameImportingPostRector;
use Ssch\TYPO3Rector\Rector\v9\v0\InjectAnnotationRector;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    // Define what rule sets will be applied
//    $parameters->set(Option::SETS, [
//        Typo3SetList::TYPO3_87,
//        Typo3SetList::TYPO3_95,
//        Typo3SetList::TYPO3_104,
//    ]);

    // FQN classes are not imported by default. If you don't do it manually after every Rector run, enable it by:
    $parameters->set(Typo3Option::AUTO_IMPORT_NAMES, true);

    // this will not import root namespace classes, like \DateTime or \Exception
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    // this will not import classes used in PHP DocBlocks, like in /** @var \Some\Class */
    $parameters->set(Option::IMPORT_DOC_BLOCKS, true);

    // Define your target version which you want to support
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_73);

    // If you would like to see the changelog url when a rector is applied
    $parameters->set(Typo3Option::OUTPUT_CHANGELOG, false);

    // If you set option Typo3Option::AUTO_IMPORT_NAMES to true, you should consider excluding some TYPO3 files.
    $parameters->set(Option::SKIP, [
        NameImportingPostRector::class => [
            'ClassAliasMap.php',
            'class.ext_update.php',
            'ext_localconf.php',
            'ext_emconf.php',
            'ext_tables.php',
            __DIR__ . '/**/TCA/*',
        ],
    ]);

    $parameters->set(Option::AUTOLOAD_PATHS, [
        // autoload specific file
        __DIR__ . '/.Build/vendor/autoload.php',
    ]);

    // If you have trouble that rector cannot run because some TYPO3 constants are not defined add an additional constants file
    // Have a look at https://github.com/sabbelasichon/typo3-rector/blob/master/typo3.constants.php
//    $parameters->set(Option::AUTOLOAD_PATHS, [
//        __DIR__ . '/typo3.constants.php'
//    ]);

    // get services (needed for register a single rule)
    $services = $containerConfigurator->services();

    // register a single rule
    $services->set(\Rector\PHPUnit\Rector\ClassMethod\ExceptionAnnotationRector::class);
    // $services->set(\Ssch\TYPO3Rector\Rector\v10\v4\UnifiedFileNameValidatorRector::class);
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)
             ->call('configure', [[
                 \Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => [
                     'PHPUnit_Framework_MockObject_MockObject' => MockObject::class,
                 ],
             ]]);
};
