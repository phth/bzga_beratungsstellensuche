<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Events;

/**
 *  Extends the callbacks used for denormalization in the GetSetMethodNormalizer.
 *  Replaces signal `Events::DENORMALIZE_CALLBACKS_SIGNAL`
 */
final class ExtendDenormalizeCallbacksEvent
{
    private array $callbacks;
    private array $extendedCallbacks;

    /**
     * @param array $callbacks
     * @param array $extendedCallbacks
     */
    public function __construct(array $callbacks, array $extendedCallbacks)
    {
        $this->callbacks = $callbacks;
        $this->extendedCallbacks = $extendedCallbacks;
    }

    /**
     * @return array
     */
    public function getCallbacks(): array
    {
        return $this->callbacks;
    }

    /**
     * @return array
     */
    public function getExtendedCallbacks(): array
    {
        return $this->extendedCallbacks;
    }

    /**
     * @param array $extendedCallbacks
     */
    public function setExtendedCallbacks(array $extendedCallbacks): void
    {
        $this->extendedCallbacks = $extendedCallbacks;
    }
}
