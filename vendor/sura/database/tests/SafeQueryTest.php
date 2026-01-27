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
use Sura\Database\Factory;

class SafeQueryTest extends RunTest
{
    protected function getResultForMethod(Database $db, $statement, $offset, $params)
    {
        return $db->safeQuery($statement, $params);
    }

    /**
     * @dataProvider goodFactoryCreateArgumentProvider
     * @param $dsn
     * @param null $username
     * @param null $password
     * @param array $options
     */
    public function testSafeQueryCalledWithVariadicParamsThrowsException(
        $expectedDriver,
        $dsn,
        $username = null,
        $password = null,
        $options = []
    ) {
        $db = Factory::create($dsn, $username, $password, $options);
        $args = [1, 2, 3, 4];
        $results = $db->run('SELECT ? AS foo, ? AS bar UNION SELECT ? AS foo, ? AS bar', ...$args);
        $this->assertIsArray($results);

        $expectedResult = [['foo' => 1, 'bar' => 2], ['foo' => 3, 'bar' => 4]];

        foreach ($results as $i => $result) {
            $this->assertIsArray($result);
            $this->assertEquals(array_diff_assoc($result, $expectedResult[$i]), []);
        }

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Only one-dimensional arrays are allowed, please use ' .
            Database::class .
            '::safeQuery()'
        );
        $db->run('SELECT ? AS foo, ? AS bar UNION SELECT ? AS foo, ? AS bar', $args);
    }
}

