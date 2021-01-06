<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Service;

interface SettingsServiceInterface
{
    public function getSettings(): array;

    /**
     * @return mixed
     */
    public function getByPath(string $path);
}
