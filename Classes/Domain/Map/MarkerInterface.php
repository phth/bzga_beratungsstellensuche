<?php
declare(strict_types = 1);

namespace Bzga\BzgaBeratungsstellensuche\Domain\Map;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

interface MarkerInterface
{
    /**
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @return mixed
     */
    public function getMarker();

    /**
     * @param string $iconPath
     */
    public function addIconFromPath(string $iconPath);

    /**
     * @param PopUpInterface $popUp
     * @param string $content
     */
    public function addPopUp(PopUpInterface $popUp, string $content);
}