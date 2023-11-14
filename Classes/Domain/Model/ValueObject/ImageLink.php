<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Model\ValueObject;

/**
 * @author Sebastian Schreiber
 */
class ImageLink
{
    private array|string $identifier = '';

    public function __construct(private readonly string $externalUrl)
    {
        $this->setIdentifier($externalUrl);
    }

    public function getExternalUrl(): string
    {
        return $this->externalUrl;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    private function setIdentifier(string $externalUrl): void
    {
        $urlSegments = parse_url($externalUrl);

        if (array_key_exists('query', $urlSegments)) {
            parse_str($urlSegments['query'], $querySegments);
            $this->identifier = $querySegments['id'];
        } else {
            $this->identifier = $externalUrl;
        }
    }
}
