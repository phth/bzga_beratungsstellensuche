<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Model;

use SJBR\StaticInfoTables\Domain\Model\AbstractEntity;

/**
 * @author Sebastian Schreiber
 */
class CountryZone extends AbstractEntity implements ExternalIdInterface
{
    // FQN must be used, otherwise there is an error in static_info_tables
    use \Bzga\BzgaBeratungsstellensuche\Domain\Model\ExternalIdTrait;
}
