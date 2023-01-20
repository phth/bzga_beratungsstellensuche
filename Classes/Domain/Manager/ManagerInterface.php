<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Manager;

use Bzga\BzgaBeratungsstellensuche\Domain\Repository\AbstractBaseRepository;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * @author Sebastian Schreiber
 */
interface ManagerInterface
{
    /**
     * @return mixed
     */
    public function create(AbstractEntity $entity);

    public function getRepository(): AbstractBaseRepository;
}
