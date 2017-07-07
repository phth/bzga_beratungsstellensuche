<?php

namespace Bzga\BzgaBeratungsstellensuche\Tests\Functional\Domain\Repository;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Bzga\BzgaBeratungsstellensuche\Domain\Model\Dto\Demand;
use Bzga\BzgaBeratungsstellensuche\Domain\Repository\EntryRepository;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class EntryRepositoryTest extends FunctionalTestCase
{

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var EntryRepository
     */
    protected $entryRepository;

    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/bzga_beratungsstellensuche', 'typo3conf/ext/static_info_tables'];

    const ENTRY_DEFAULT_FIXTURE_UID = 1;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->entryRepository = $this->objectManager->get(EntryRepository::class);
        $this->importDataSet(__DIR__ . '/../../Fixtures/tx_bzgaberatungsstellensuche_domain_model_category.xml');
        $this->importDataSet(__DIR__ . '/../../Fixtures/tx_bzgaberatungsstellensuche_domain_model_entry.xml');
    }

    /**
     * @test
     */
    public function findDemanded()
    {
        /** @var Demand $demand */
        $demand = $this->objectManager->get(Demand::class);
        $demand->setKeywords('Keyword');
        $entries = $this->entryRepository->findDemanded($demand);
        $this->assertEquals(self::ENTRY_DEFAULT_FIXTURE_UID, $this->getIdListOfItems($entries));
    }

    /**
     * @test
     */
    public function deleteByUid()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $this->importDataSet('ntf://Database/sys_file_storage.xml');

        $this->setUpBackendUserFromFixture(1);
        $storage = new StorageRepository();
        $subject = $storage->findByUid(1);
        $subject->setEvaluatePermissions(false);
        $this->entryRepository->deleteByUid(self::ENTRY_DEFAULT_FIXTURE_UID);
        $this->assertEquals(0, $this->entryRepository->countByUid(self::ENTRY_DEFAULT_FIXTURE_UID));
    }


    /**
     * @test
     */
    public function findOldEntriesByExternalUidsDiffForTable()
    {
        $oldEntries = $this->entryRepository->findOldEntriesByExternalUidsDiffForTable('tx_bzgaberatungsstellensuche_domain_model_entry', [1]);
        $this->assertEquals([['uid'] => 2], $oldEntries);
    }

    /**
     * @param QueryResultInterface $items
     *
     * @return string
     */
    protected function getIdListOfItems(QueryResultInterface $items)
    {
        $idList = [];
        foreach ($items as $item) {
            $idList[] = $item->getUid();
        }
        return implode(',', $idList);
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        unset($this->newsRepository);
        unset($this->objectManager);
    }
}
