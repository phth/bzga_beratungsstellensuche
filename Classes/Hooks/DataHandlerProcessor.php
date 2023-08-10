<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Hooks;

use Bzga\BzgaBeratungsstellensuche\Domain\Repository\EntryRepository;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Sebastian Schreiber
 */
class DataHandlerProcessor
{
    public function processCmdmap_deleteAction(string $table, int $id, array $recordToDelete, bool &$recordWasDeleted, DataHandler $tceMain): void
    {
        if ($table === EntryRepository::ENTRY_TABLE) {
            $entryRepository = GeneralUtility::makeInstance(EntryRepository::class);
            $entryRepository->deleteByUid($id);
            $recordWasDeleted = true;
        }
    }

    public function processDatamap_postProcessFieldArray(string $status, string $table, string $id, array &$fieldArray, DataHandler &$reference): void
    {
        if ($table === 'tt_content' && $status === 'update' && isset($fieldArray['pi_flexform'])) {
            $checkFields = [
                'additional' => [
                    'settings.singlePid',
                    'settings.listPid',
                    'settings.backPid',
                    'settings.list.itemsPerPage',
                    'settings.formFields',
                ],
            ];

            $flexformData = GeneralUtility::xml2array($fieldArray['pi_flexform']);

            foreach ($checkFields as $sheet => $fields) {
                foreach ($fields as $field) {
                    if (isset($flexformData['data'][$sheet]['lDEF'][$field]['vDEF']) &&
                        trim((string)$flexformData['data'][$sheet]['lDEF'][$field]['vDEF']) === ''
                    ) {
                        unset($flexformData['data'][$sheet]['lDEF'][$field]);
                    }
                }

                // If remaining sheet does not contain fields, then remove the sheet
                if (isset($flexformData['data'][$sheet]['lDEF']) && $flexformData['data'][$sheet]['lDEF'] === []) {
                    unset($flexformData['data'][$sheet]);
                }
            }

            $flexFormTools = GeneralUtility::makeInstance(FlexFormTools::class);
            $fieldArray['pi_flexform'] = $flexFormTools->flexArray2Xml($flexformData, true);
        }
    }
}
