<?php

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Events;

use Bzga\BzgaBeratungsstellensuche\Domain\Model\Dto\Demand;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

final class ExtendDemandConstraintsEvent
{
    public function __construct(private readonly Demand $demand, private readonly QueryInterface $query, private array $constraints)
    {
    }

    /**
     * @return Demand
     */
    public function getDemand(): Demand
    {
        return $this->demand;
    }

    /**
     * @return QueryInterface
     */
    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }

    public function setConstraints(array $constraints): void
    {
        $this->constraints = $constraints;
    }
}
