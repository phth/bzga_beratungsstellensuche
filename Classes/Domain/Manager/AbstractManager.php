<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Manager;

use Bzga\BzgaBeratungsstellensuche\Domain\Model\ExternalIdInterface;
use Bzga\BzgaBeratungsstellensuche\Domain\Repository\AbstractBaseRepository;
use Bzga\BzgaBeratungsstellensuche\Persistence\Mapper\DataMap;
use Bzga\BzgaBeratungsstellensuche\Property\PropertyMapper;
use function count;
use Countable;
use IteratorAggregate;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Log\LogManagerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * @author Sebastian Schreiber
 */
abstract class AbstractManager implements ManagerInterface, Countable, IteratorAggregate
{
    /**
     * @var DataHandler
     */
    protected $dataHandler;

    /**
     * @var array
     */
    protected $dataMap = [];

    private \SplObjectStorage $entries;

    private array $externalUids = [];

    private \Bzga\BzgaBeratungsstellensuche\Persistence\Mapper\DataMap $dataMapFactory;

    private \Bzga\BzgaBeratungsstellensuche\Property\PropertyMapper $propertyMapper;

    private \Psr\Log\LoggerInterface $logger;

    public function __construct(
        DataHandler $dataHandler,
        DataMap $dataMapFactory,
        PropertyMapper $propertyMapper,
        LogManagerInterface $logManager
    ) {
        $this->dataHandler = $dataHandler;
        $this->dataHandler->bypassAccessCheckForRecords = true;
        $this->dataHandler->admin = true;
        $this->dataHandler->enableLogging = true;
        $this->dataHandler->checkStoredRecords = false;
        $this->dataMapFactory = $dataMapFactory;
        $this->propertyMapper = $propertyMapper;
        $this->entries = new \SplObjectStorage();
        $this->logger = $logManager->getLogger(self::class);
    }

    public function create(AbstractEntity $entity)
    {
        $tableName = $this->dataMapFactory->getTableNameByClassName(get_class($entity));

        $tableUid = $this->getUid($entity);

        // Add external uid to stack of updated, or inserted entries, we need this for the clean up
        $this->entries->attach($entity);

        if ($entity instanceof ExternalIdInterface) {
            $this->externalUids[] = $entity->getExternalId();
        }

        $data = [
            'pid' => $entity->getPid(),
        ];
        $properties = ObjectAccess::getGettablePropertyNames($entity);
        foreach ($properties as $propertyName) {
            $propertyNameLowercase = GeneralUtility::camelCaseToLowerCaseUnderscored($propertyName);
            if (isset($GLOBALS['TCA'][$tableName]['columns'][$propertyNameLowercase])) {
                $propertyValue = ObjectAccess::getProperty($entity, $propertyName);
                if ($typeConverter = $this->propertyMapper->supports($propertyValue)
                ) {
                    $propertyValue = $typeConverter->convert(
                        $propertyValue,
                        [
                            'manager' => $this,
                            'tableUid' => $tableUid,
                            'tableName' => $tableName,
                            'tableField' => 'image',
                            'entity' => $entity,
                        ]
                    );
                    if ($propertyValue !== null) {
                        $data[$propertyNameLowercase] = $propertyValue;
                    }
                } else {
                    if ($propertyValue !== null) {
                        $data[$propertyNameLowercase] = $propertyValue;
                    }
                }
            }
        }

        // We only update the entry if something has really changed. Speeding up import drastically
        $entryHash = md5(serialize($data));

        $hasChanged = true;
        if ($entity instanceof ExternalIdInterface) {
            $hasChanged = $this->getRepository()->countByExternalIdAndHash($entity->getExternalId(), $entryHash) === 0;
        }

        if ($hasChanged) {
            $data['hash'] = $entryHash;
            $this->dataMap[$tableName][$tableUid] = $data;
        }
    }

    public function persist(): void
    {
        if (!empty($this->dataMap)) {
            $this->dataHandler->start($this->dataMap, []);
            $this->dataHandler->process_datamap();
            if (count($this->dataHandler->errorLog) !== 0) {
                $this->logger->error('Error in importing', $this->dataHandler->errorLog);
            }
            $this->dataMap = [];
        }
    }

    public function addDataMap(string $tableName, string $tableUid, array $data): void
    {
        $this->dataMap[$tableName][$tableUid] = $data;
    }

    public function cleanUp(): void
    {
        $repository = $this->getRepository();
        $table = $this->dataMapFactory->getTableNameByClassName($repository->getObjectType());
        $oldEntries = $repository->findOldEntriesByExternalUidsDiffForTable($table, $this->externalUids);

        $cmd = [];
        foreach ($oldEntries as $oldEntry) {
            $cmd[$table][$oldEntry['uid']] = ['delete' => ''];
        }

        $this->dataHandler->start([], $cmd);
        $this->dataHandler->process_cmdmap();
    }

    public function getIterator(): \SplObjectStorage
    {
        return $this->entries;
    }

    public function count(): int
    {
        return count($this->entries);
    }

    abstract public function getRepository(): AbstractBaseRepository;

    /**
     * @return int|string
     */
    private function getUid(AbstractEntity $entity)
    {
        // @TODO: Is there a better solution to check? Can we bind it directly to the object? At the moment i am getting an error
        if ($entity->_isNew()) {
            return uniqid('NEW_', false);
        }

        return $entity->getUid();
    }
}
