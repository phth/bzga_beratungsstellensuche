<?php


namespace BZgA\BzgaBeratungsstellensuche\Domain\Serializer\NameConverter;


use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use BZgA\BzgaBeratungsstellensuche\Events;

abstract class AbstractMappingNameConverter extends CamelCaseToSnakeCaseNameConverter
{


    /**
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     */
    protected $signalSlotDispatcher;

    /**
     * Mapping of names, left side incoming names in xml|array, right side name for object
     * @var array
     */
    protected $mapNames = array(
        '#text' => 'title',
        'index' => 'external_id',
    );

    /**
     * @var array
     */
    protected $mapNamesFlipped = array();

    /**
     * EntryNameConverter constructor.
     * @param array|null $attributes
     * @param bool $lowerCamelCase
     */
    public function __construct(array $attributes = null, $lowerCamelCase = true)
    {
        parent::__construct($attributes, $lowerCamelCase);
        // @TODO Working with DI
        if (!$this->signalSlotDispatcher instanceof Dispatcher) {
            $this->signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        }
        $this->emitMapNamesSignal();
        $this->mapNamesFlipped();
    }

    /**
     * @param array $mapNames
     */
    public function addAdditionalMapNames(array $mapNames)
    {
        ArrayUtility::mergeRecursiveWithOverrule($this->mapNames, $mapNames);
        $this->mapNamesFlipped();
    }

    /**
     * @return void
     */
    private function mapNamesFlipped()
    {
        $this->mapNamesFlipped = array_flip($this->mapNames);
    }

    /**
     * @param string $propertyName
     * @return mixed|string
     */
    public function denormalize($propertyName)
    {
        if (isset($this->mapNames[$propertyName])) {
            $propertyName = $this->mapNames[$propertyName];
            $propertyName = parent::denormalize($propertyName);
        }

        return $propertyName;
    }

    /**
     * @return void
     */
    protected function emitMapNamesSignal()
    {
        $this->signalSlotDispatcher->dispatch(static::class, Events::SIGNAL_MapNames, array($this, $this->mapNames));
    }


}