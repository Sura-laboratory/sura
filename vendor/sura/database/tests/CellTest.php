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

use Sura\Database\Database;

class CellTest extends ColTest
{
    protected function goodColArguments()
    {
        return [
            [
                'SELECT 1 AS foo', 0, [], [1]
            ],
            [
                'SELECT 1 AS foo, 2 AS bar', 0, [], [1]
            ],
            [
                'SELECT 1 AS foo, 2 AS bar', 1, [], [1]
            ],
            [
                'SELECT 1 AS foo, 2 AS bar UNION SELECT 3 AS foo, 4 AS bar', 0, [], [1]
            ],
            [
                'SELECT 1 AS foo, 2 AS bar UNION SELECT 3 AS foo, 4 AS bar', 1, [], [1]
            ],
            [
                'SELECT ? AS foo, ? AS bar UNION SELECT ? AS foo, ? AS bar', 0, [1, 2, 3, 4], [1]
            ],
            [
                'SELECT ? AS foo, ? AS bar UNION SELECT ? AS foo, ? AS bar', 1, [1, 2, 3, 4], [1]
            ]
        ];
    }


    protected function getResultForMethod(Database $db, $statement, $offset, $params)
    {
        $args = $params;
        array_unshift($args, $statement);

        return call_user_func_array([$db, 'cell'], $args);
    }

    /**
     * @param callable $cb
     * @param string $statement
     * @param int $offset
     * @param array $params
     * @param array $expectedResult
     *
     * @dataProvider goodColArgumentsProvider
     */
    public function testMethod(callable $cb, $statement, $offset, $params, $expectedResult)
    {
        $db = $this->databaseExpectedFromCallable($cb);

        $result = $this->getResultForMethod($db, $statement, $offset, $params);

        $this->assertEquals(array_diff_assoc([$result], [$expectedResult[0]]), []);
    }
}

