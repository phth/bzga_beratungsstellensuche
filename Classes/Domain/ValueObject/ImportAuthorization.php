<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\ValueObject;

final class ImportAuthorization
{
    private string $url;
    private string $clientId;
    private string $clientSecret;

    public function __construct(string $url, string $clientId, string $clientSecret)
    {
        $this->url = $url;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }
}
