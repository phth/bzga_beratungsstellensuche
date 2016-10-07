<?php


namespace BZgA\BzgaBeratungsstellensuche\Hooks;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use BZgA\BzgaBeratungsstellensuche\Domain\Repository\EntryRepository;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;

class DataHandlerProcessor
{

    /**
     * @param string $table
     * @param integer $id
     * @param integer $recordToDelete
     * @param boolean $recordWasDeleted
     * @param DataHandler $tceMain
     */
    public function processCmdmap_deleteAction($table, $id, $recordToDelete, &$recordWasDeleted, DataHandler $tceMain)
    {
        if ($table === EntryRepository::ENTRY_TABLE) {
            $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
            /* @var $objectManager ObjectManager */
            $entryRepository = $objectManager->get(EntryRepository::class);
            /* @var $entryRepository EntryRepository */
            $entryRepository->deleteByUid($id);
            $recordWasDeleted = true;
        }
    }

    /**
     * Checks if the fields defined in $checkFields are set in the data-array of pi_flexform. If a field is
     * present and contains an empty value, the field is unset.
     *
     * Structure of the checkFields array:
     *
     * array('sheet' => array('field1', 'field2'));
     *
     * @param string $status
     * @param string $table
     * @param string $id
     * @param array $fieldArray
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $reference
     *
     * @return void
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, &$reference)
    {
        if ($table === 'tt_content' && $status == 'update' && isset($fieldArray['pi_flexform'])) {
            $checkFields = array(
                'additional' => array(
                    'settings.singlePid',
                    'settings.listPid',
                    'settings.backPid',
                    'settings.list.itemsPerPage',
                ),
            );

            $flexformData = GeneralUtility::xml2array($fieldArray['pi_flexform']);

            foreach ($checkFields as $sheet => $fields) {
                foreach ($fields as $field) {
                    if (isset($flexformData['data'][$sheet]['lDEF'][$field]['vDEF']) &&
                        trim($flexformData['data'][$sheet]['lDEF'][$field]['vDEF']) === ''
                    ) {
                        unset($flexformData['data'][$sheet]['lDEF'][$field]);
                    }
                }

                // If remaining sheet does not contain fields, then remove the sheet
                if (isset($flexformData['data'][$sheet]['lDEF']) && $flexformData['data'][$sheet]['lDEF'] === array()) {
                    unset($flexformData['data'][$sheet]);
                }
            }

            $flexFormTools = GeneralUtility::makeInstance(FlexFormTools::class);
            /** @var $flexFormTools $flexFormTools */
            $fieldArray['pi_flexform'] = $flexFormTools->flexArray2Xml($flexformData, true);

        }
    }


}