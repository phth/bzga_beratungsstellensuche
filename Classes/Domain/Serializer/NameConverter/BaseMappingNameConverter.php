<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Serializer\NameConverter;

use Bzga\BzgaBeratungsstellensuche\Events\ExtendMapNamesEvent;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Sebastian Schreiber
 */
class BaseMappingNameConverter extends CamelCaseToSnakeCaseNameConverter
{
    private EventDispatcher $eventDispatcher;

    /**
     * Mapping of names, left side incoming names in xml|array, right side name for object
     * @var array
     */
    protected $mapNames = [
        'label' => 'title',
        'index' => 'external_id',
    ];

    /**
     * @var array
     */
    protected $mapNamesFlipped = [];

    /**
     * EntryNameConverter constructor.
     *
     * @param array|null $attributes
     * @param bool $lowerCamelCase
     */
    public function __construct(array $attributes = null, $lowerCamelCase = true, ?EventDispatcher $eventDispatcher = null)
    {
        parent::__construct($attributes, $lowerCamelCase);

        $this->eventDispatcher = $eventDispatcher??GeneralUtility::makeInstance(EventDispatcher::class);

        $event = new ExtendMapNamesEvent($this->mapNames, []);
        $event = $this->eventDispatcher->dispatch($event);
        $this->addAdditionalMapNames($event->getExtendedMapNames());
        $this->mapNamesFlipped();
    }

    /**
     * @param array $mapNames
     */
    public function addAdditionalMapNames(array $mapNames): void
    {
        ArrayUtility::mergeRecursiveWithOverrule($this->mapNames, $mapNames);
        $this->mapNamesFlipped();
    }

    private function mapNamesFlipped(): void
    {
        $this->mapNamesFlipped = array_flip($this->mapNames);
    }

    /**
     * @param array|string|null $propertyName
     * @return mixed|string|null
     */
    public function denormalize($propertyName)
    {
        if (isset($this->mapNames[$propertyName])) {
            $propertyName = GeneralUtility::underscoredToLowerCamelCase($this->mapNames[$propertyName]);
        }

        return $propertyName;
    }
}
