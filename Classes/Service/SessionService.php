<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Service;

use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

/**
 * @author Sebastian Schreiber
 */
class SessionService
{
    private readonly ?FrontendUserAuthentication $frontendUser;

    public function __construct(
        private readonly string $sessionNamespace = 'beratungsstellendatenbank'
    ) {
        $this->frontendUser = $GLOBALS['TSFE'] ? $GLOBALS['TSFE']->fe_user : null;
    }

    public function restoreFromSession(): ?array
    {
        if (! $this->hasValidFrontendUser()) {
            return null;
        }

        $sessionData = $this->frontendUser->getKey('ses', $this->sessionNamespace);
        $data = unserialize((string)$sessionData);

        if (! is_array($data)) {
            return null;
        }

        if ($data === []) {
            return null;
        }

        foreach ($data as $key => $value) {
            if (empty($value)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    public function writeToSession($object): void
    {
        if ($this->hasValidFrontendUser()) {
            $sessionData = serialize($object);
            $this->frontendUser->setKey('ses', $this->sessionNamespace, $sessionData);
        }
    }

    public function cleanUpSession(): void
    {
        if ($this->hasValidFrontendUser()) {
            $this->frontendUser->setKey('ses', $this->sessionNamespace, null);
        }
    }

    protected function hasValidFrontendUser(): bool
    {
        if ($this->frontendUser instanceof FrontendUserAuthentication) {
            return true;
        }

        return false;
    }
}
