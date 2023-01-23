<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Backend\FormDataProvider;

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class BeratungsstellensucheFlexFormManipulation implements FormDataProviderInterface
{
    /**
     * Fields which are removed in detail view
     */
    private array $removedFieldsInDetailView = [
        'sDEF' => 'startingpoint,recursive',
        'additional' => 'listPid,list.itemsPerPage,formFields',
        'template' => '',
    ];

    /**
     * Fields which are removed in list view
     */
    private array $removedFieldsInListView = [
        'sDEF' => '',
        'additional' => '',
        'template' => '',
    ];

    /**
     * Fields which are remove in form view
     */
    private array $removedFieldsInFormView = [
        'sDEF' => 'startingpoint,recursive',
        'additional' => 'singlePid,backPid,list.itemsPerPage',
        'template' => '',
    ];

    private function updateFlexforms(array $result): array
    {
        $selectedView = '';

        $row = $result['databaseRow'];
        $dataStructure = $result['processedTca']['columns']['pi_flexform']['config']['ds'];

        // get the first selected action
        if (is_string($row['pi_flexform'])) {
            $flexformSelection = GeneralUtility::xml2array($row['pi_flexform']);
        } else {
            $flexformSelection = $row['pi_flexform'];
        }
        if (is_array($flexformSelection) && is_array($flexformSelection['data'])) {
            $selectedView = $flexformSelection['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'];
            if (!empty($selectedView)) {
                $actionParts = GeneralUtility::trimExplode(';', $selectedView, true);
                $selectedView = $actionParts[0];
            }

        // new plugin element
        } elseif (GeneralUtility::isFirstPartOfStr($row['uid'], 'NEW')) {
            // use List as starting view
            $selectedView = 'Entry->list;Entry->show';
        }

        if (!empty($selectedView)) {
            // Modify the flexform structure depending on the first found action
            switch ($selectedView) {
                case 'Entry->list;Entry->show':
                    $dataStructure = $this->deleteFromStructure($dataStructure, $this->removedFieldsInListView);
                    break;
                case 'Entry->show':
                    $dataStructure = $this->deleteFromStructure($dataStructure, $this->removedFieldsInDetailView);
                    break;
                case 'Entry->form':
                    $dataStructure = $this->deleteFromStructure($dataStructure, $this->removedFieldsInFormView);
                    break;
                default:
            }

            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['bzga_beratungsstellensuche']['Hooks/BackendUtility.php']['updateFlexforms'])) {
                $params = [
                    'selectedView' => $selectedView,
                    'dataStructure' => &$dataStructure,
                ];
                foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['bzga_beratungsstellensuche']['Hooks/BackendUtility.php']['updateFlexforms'] as $reference) {
                    GeneralUtility::callUserFunction($reference, $params, $this);
                }
            }
        }
        $result['processedTca']['columns']['pi_flexform']['config']['ds'] = $dataStructure;

        return $result;
    }

    private function deleteFromStructure(array $dataStructure, array $fieldsToBeRemoved): array
    {
        foreach ($fieldsToBeRemoved as $sheetName => $sheetFields) {
            $fieldsInSheet = GeneralUtility::trimExplode(',', $sheetFields, true);
            foreach ($fieldsInSheet as $fieldName) {
                unset($dataStructure['sheets'][$sheetName]['ROOT']['el']['settings.' . $fieldName]);
            }
        }

        return $dataStructure;
    }

    public function addData(array $result): array
    {
        if ($result['tableName'] === 'tt_content'
            && $result['databaseRow']['CType'] === 'list'
            && $result['databaseRow']['list_type'] === 'bzgaberatungsstellensuche_pi1'
            && is_array($result['processedTca']['columns']['pi_flexform']['config']['ds'])
        ) {
            $result = $this->updateFlexForms($result);
        }

        return $result;
    }
}
