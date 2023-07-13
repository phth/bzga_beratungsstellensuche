<?php

use Bzga\BzgaBeratungsstellensuche\Backend\FormDataProvider\BeratungsstellensucheFlexFormManipulation;
use Bzga\BzgaBeratungsstellensuche\Cache\CachedClassLoader;
use Bzga\BzgaBeratungsstellensuche\Controller\EntryController;
use Bzga\BzgaBeratungsstellensuche\Hooks\DataHandlerProcessor;
use Bzga\BzgaBeratungsstellensuche\Property\TypeConverter\AbstractEntityConverter;
use Bzga\BzgaBeratungsstellensuche\Property\TypeConverter\BoolConverter;
use Bzga\BzgaBeratungsstellensuche\Property\TypeConverter\ImageLinkConverter;
use Bzga\BzgaBeratungsstellensuche\Property\TypeConverter\ObjectStorageConverter;
use Bzga\BzgaBeratungsstellensuche\Property\TypeConverter\StringConverter;
use Bzga\BzgaBeratungsstellensuche\Updates\CreateImageUploadFolderUpdate;
use Bzga\BzgaBeratungsstellensuche\Updates\ImportCountryZonesUpdate;
use Bzga\BzgaBeratungsstellensuche\Updates\MigratePluginsUpdate;
use Bzga\BzgaBeratungsstellensuche\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexPrepare;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaFlexProcess;
use TYPO3\CMS\Core\Cache\Backend\FileBackend;
use TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility as GeneralExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (! defined('TYPO3')) {
    die('Access denied.');
}

call_user_func(function ($packageKey) {
    ExtensionManagementUtility::registerExtensionKey($packageKey, 100);

    // Plugin configuration
    ExtensionUtility::configurePlugin(
        'BzgaBeratungsstellensuche',
        'Pi1',
        [EntryController::class => 'list,show,form,autocomplete'],
        [EntryController::class => 'list,form,autocomplete'],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    // Plugin configuration
    ExtensionUtility::configurePlugin(
        'BzgaBeratungsstellensuche',
        'List',
        [EntryController::class => 'list,form,autocomplete'],
        [EntryController::class => 'list,form,autocomplete'],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    // Plugin configuration
    ExtensionUtility::configurePlugin(
        'BzgaBeratungsstellensuche',
        'Show',
        [EntryController::class => 'show,form,autocomplete'],
        [EntryController::class => 'form,autocomplete'],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
    // Only include page.tsconfig if TYPO3 version is below 12 so that it is not imported twice.
    // Todo:: remove when dropping TYPO3 v11 support
    if ($versionInformation->getMajorVersion() < 12) {
        GeneralExtensionManagementUtility::addPageTSConfig('@import \'EXT:bzga_beratungsstellensuche/Configuration/page.tsconfig\'');
    }

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][BeratungsstellensucheFlexFormManipulation::class] = [
        'depends' => [
            TcaFlexPrepare::class,
        ],
        'before' => [
            TcaFlexProcess::class,
        ],
    ];

    // Page module hook
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['bzgaberatungsstellensuche_pi1']['bzga_beratungsstellensuche'] =
        'Bzga\\BzgaBeratungsstellensuche\\Hooks\\PageLayoutView->getExtensionSummary';

    // Command controllers for scheduler
    // hooking into TCE Main to monitor record updates that may require deleting documents from the index
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][]  = DataHandlerProcessor::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = DataHandlerProcessor::class;

    // Register cache to extend the models of this extension
    if (! is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$packageKey])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$packageKey]           = [];
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$packageKey]['groups'] = ['all'];
    }
    if (! isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$packageKey]['frontend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$packageKey]['frontend'] = PhpFrontend::class;
    }
    if (! isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$packageKey]['backend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$packageKey]['backend'] = FileBackend::class;
    }
    // Configure clear cache post processing for extended domain models
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][$packageKey]
        = 'Bzga\\BzgaBeratungsstellensuche\\Cache\\ClassCacheManager->reBuild';

    // Register cached domain model classes autoloader
    require_once GeneralExtensionManagementUtility::extPath($packageKey) . 'Classes/Cache/CachedClassLoader.php';
    CachedClassLoader::registerAutoloader();

    // Names of entities which can be overriden
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$packageKey]['entities'] = [
        'Entry',
        'Category',
        'Dto/Demand',
    ];

    // Caching of user requests
    if (! is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['bzgaberatungsstellensuche_cache_coordinates'])
    ) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['bzgaberatungsstellensuche_cache_coordinates'] = [
            'frontend' => VariableFrontend::class,
            'backend'  => Typo3DatabaseBackend::class,
            'options'  => [],
        ];
    }

    // Register some type converters so we can prepare everything for the data handler to import the xml
    ExtensionManagementUtility::registerTypeConverter(ImageLinkConverter::class);
    ExtensionManagementUtility::registerTypeConverter(StringConverter::class);
    ExtensionManagementUtility::registerTypeConverter(AbstractEntityConverter::class);
    ExtensionManagementUtility::registerTypeConverter(ObjectStorageConverter::class);
    ExtensionManagementUtility::registerTypeConverter(BoolConverter::class);

    // Linkvalidator
    if (GeneralExtensionManagementUtility::isLoaded('linkvalidator')) {
        GeneralExtensionManagementUtility::addPageTSConfig('@import \'EXT:bzga_beratungsstellensuche/Configuration/TsConfig/Page/LinkValidator/*.tsconfig\'');
    }

    // Upgrade wizards
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][CreateImageUploadFolderUpdate::class] = CreateImageUploadFolderUpdate::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][ImportCountryZonesUpdate::class] = ImportCountryZonesUpdate::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update'][MigratePluginsUpdate::class] = MigratePluginsUpdate::class;
}, 'bzga_beratungsstellensuche');

GeneralExtensionManagementUtility::addTypoScriptSetup(trim('
    config.pageTitleProviders {
        beratungsstelle {
            provider = Bzga\BzgaBeratungsstellensuche\PageTitle\PageTitleProvider
            before = record
            after = altPageTitle
        }
    }
'));
