<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\View\Entry;

use Bzga\BzgaBeratungsstellensuche\Domain\Model\Entry;
use TYPO3Fluid\Fluid\View\ViewInterface;

final class AutocompleteJson implements ViewInterface
{
    protected $variables = [];

    public function render(): string
    {
        /** @var Entry[] $entries */
        $entries = $this->variables['entries'];
        $q = $this->variables['q'];

        $suggestions = [];

        foreach ($entries as $entry) {
            if (\str_starts_with($entry->getCity(), $q)) {
                $suggestions[] = $entry->getCity();
            }

            if (\str_starts_with($entry->getZip(), $q)) {
                $suggestions[] = $entry->getZip();
            }
        }

        return json_encode(array_unique($suggestions), JSON_THROW_ON_ERROR);
    }

    public function assign($key, $value)
    {
        $this->variables[$key] = $value;
        return $this;
    }

    public function assignMultiple(array $values)
    {
        foreach ($values as $key => $value) {
            $this->assign($key, $value);
        }
        return $this;
    }

    public function renderSection($sectionName, array $variables = [], $ignoreUnknown = false)
    {
    }

    public function renderPartial($partialName, $sectionName, array $variables, $ignoreUnknown = false)
    {
    }
}
