<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Serializer\Normalizer;

use Bzga\BzgaBeratungsstellensuche\Domain\Serializer\NameConverter\BaseMappingNameConverter;
use Bzga\BzgaBeratungsstellensuche\Events\ExtendDenormalizeCallbacksEvent;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer as BaseGetSetMethodNormalizer;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;

/**
 * @author Sebastian Schreiber
 */
class GetSetMethodNormalizer extends BaseGetSetMethodNormalizer
{
    private EventDispatcher $eventDispatcher;

    /**
     * @var array
     */
    protected $denormalizeCallbacks;

    public function __construct(
        ClassMetadataFactoryInterface $classMetadataFactory = null,
        NameConverterInterface $nameConverter = null
    ) {
        $this->classMetadataFactory = $classMetadataFactory;
        if ($nameConverter === null) {
            $nameConverter = new BaseMappingNameConverter();
        }
        $this->nameConverter = $nameConverter;
        parent::__construct($classMetadataFactory, $nameConverter);
    }

    public function setDenormalizeCallbacks(array $callbacks): self
    {
        $callbacks = $this->dispatchDenormalizeCallbacksEvent($callbacks);
        foreach ($callbacks as $attribute => $callback) {
            if (!is_callable($callback)) {
                throw new \InvalidArgumentException(sprintf(
                    'The given callback for attribute "%s" is not callable.',
                    $attribute
                ));
            }
        }
        $this->denormalizeCallbacks = $callbacks;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $allowedAttributes = $this->getAllowedAttributes($class, $context, true);
        $normalizedData = $this->prepareForDenormalization($data);

        $reflectionClass = new \ReflectionClass($class);
        $object = $this->instantiateObject($normalizedData, $class, $context, $reflectionClass, $allowedAttributes);

        $classMethods = get_class_methods($object);
        foreach ($normalizedData as $attribute => $value) {
            if ($this->nameConverter) {
                $attribute = $this->nameConverter->denormalize($attribute);
            }

            $allowed = $allowedAttributes === false || in_array($attribute, $allowedAttributes);

            if ($allowed) {
                $setter = 'set' . ucfirst((string)$attribute);

                if (in_array($setter, $classMethods, false) && !$reflectionClass->getMethod($setter)->isStatic()) {
                    if (isset($this->denormalizeCallbacks[$attribute])) {
                        $value = call_user_func($this->denormalizeCallbacks[$attribute], $value);
                    }
                    if ($value !== null) {
                        $object->$setter($value);
                    }
                }
            }
        }

        return $object;
    }

    protected function dispatchDenormalizeCallbacksEvent(array $callbacks): array
    {
        $event = new ExtendDenormalizeCallbacksEvent($callbacks, []);
        $event = $this->eventDispatcher->dispatch($event);
        if ($event->getExtendedCallbacks()) {
            $callbacks = array_merge($callbacks, $event->getExtendedCallbacks());
        }

        return $callbacks;
    }

    public function injectEventDispatcher(EventDispatcher $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
