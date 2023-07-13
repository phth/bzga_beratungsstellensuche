<?php

namespace Bzga\BzgaBeratungsstellensuche\Events;

use Bzga\BzgaBeratungsstellensuche\Domain\Model\Dto\Demand;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

final class ExtendDemandConstraintsEvent
{
    private Demand $demand;
    private QueryInterface $query;
    private array $constraints;

    public function __construct(Demand $demand, QueryInterface $query, array $constraints)
    {
        $this->demand = $demand;
        $this->query = $query;
        $this->constraints = $constraints;
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

    /**
     * @param array $constraints
     */
    public function setConstraints(array $constraints): void
    {
        $this->constraints = $constraints;
    }
}
