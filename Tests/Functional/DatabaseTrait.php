<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Tests\Functional;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;

trait DatabaseTrait
{
    public function selectCount(string $fields, string $table, $where = '1=1'): int
    {
        return $this->getDatabaseInstance($table)
                    ->count($fields)
                    ->from($table)
                    ->where($where)
                    ->execute()
                    ->fetchOne(0);
    }

    public function select(string $field, string $table, int $uid)
    {
        return $this->getDatabaseInstance($table)
            ->select($field)
            ->from($table)
            ->where('uid=' . $uid)
            ->execute()
            ->fetchOne(0);
    }

    public function getDatabaseInstance(string $table): QueryBuilder
    {
        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeAll();

        return $queryBuilder;
    }
}
