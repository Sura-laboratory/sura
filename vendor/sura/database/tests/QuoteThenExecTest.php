<?php

/*
 * Copyright (c) 2023 Sura
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Sura\Database\Tests;

/**
 * Class ExecTest
 * @package Sura\Database\Tests
 */
class QuoteThenExecTest extends DatabaseWriteTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseInsertManyProvider
     * @depends      Sura\Database\Tests\QuoteTest::testQuote
     * @depends      Sura\Database\Tests\EscapeIdentifierTest::testEscapeIdentifier
     * @depends      Sura\Database\Tests\EscapeIdentifierTest::testEscapeIdentifierThrowsSomething
     * @param callable $cb
     * @param array $maps
     */
    public function testExec(callable $cb, array $maps)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $table = 'irrelevant_but_valid_tablename';

        $first = $maps[0];

        // Let's make sure our keys are escaped.
        $keys = \array_keys($first);
        foreach ($keys as $i => $v) {
            $keys[$i] = $db->escapeIdentifier($v);
        }

        $total = 0;

        foreach ($maps as $params) {
            $queryString = "INSERT INTO " . $db->escapeIdentifier($table) . " (";

            // Now let's append a list of our columns.
            $queryString .= \implode(', ', $keys);

            // This is the middle piece.
            $queryString .= ") VALUES (";

            // Now let's concatenate the ? placeholders
            $queryString .= \implode(
                ', ',
                \array_map(
                    function ($val) use ($db) {
                        return $db->quote($val);
                    },
                    $params
                )
            );

            // Necessary to close the open ( above
            $queryString .= ");";

            $total += $db->exec($queryString);
        }

        $this->assertSame($total, count($maps));
    }
}

