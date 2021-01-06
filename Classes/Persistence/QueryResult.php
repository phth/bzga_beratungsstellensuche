<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Persistence;

use TYPO3\CMS\Extbase\Persistence\Generic\Qom\Statement;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult as CoreQueryResult;

class QueryResult extends CoreQueryResult
{

    /**
     * Overwrites the original implementation of Extbase
     *
     * When the query contains a $statement the query is regularly executed and the number of results is counted
     * instead of the original implementation which tries to create a custom COUNT(*) query and delivers wrong results.
     */
    public function count(): int
    {
        if ($this->numberOfResults === null) {
            if (is_array($this->queryResult)) {
                $this->numberOfResults = count($this->queryResult);
            } elseif ($this->query->getStatement() instanceof Statement) {
                $this->initialize();
                $this->numberOfResults = count($this->queryResult);
            } else {
                return parent::count();
            }
        }

        return $this->numberOfResults;
    }
}
