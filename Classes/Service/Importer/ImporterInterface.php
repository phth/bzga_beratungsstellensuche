<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Service\Importer;

use Bzga\BzgaBeratungsstellensuche\Domain\ValueObject\ImportAuthorization;

/**
 * @author Sebastian Schreiber
 */
interface ImporterInterface
{
    public function importFromFile(string $file, int $pid = 0): void;

    public function importFromUrl(string $url, ImportAuthorization $importAuthorization, int $pid = 0): void;

    public function import(string $content, int $pid = 0): void;
}
