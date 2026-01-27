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

use InvalidArgumentException;
use PDOException;

class InsertTest extends DatabaseWriteTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertNoFieldsThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(PDOException::class);
        $this->assertNull($db->insert('irrelevant_but_valid_tablename', []));
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertTableNameThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $db->insert('', ['foo' => 1]);
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertMapArgThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $db->insert('irrelevant_but_valid_tablename', [[1]]);
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertMapArgKeysThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $db->insert('irrelevant_but_valid_tablename', ['1foo' => 1]);
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertIncorrectFieldThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(PDOException::class);
        $db->insert('irrelevant_but_valid_tablename', ['bar' => 1]);
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsert(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $db->insert('irrelevant_but_valid_tablename', ['foo' => 1]);
        $this->assertEquals(
            $db->single('SELECT COUNT(foo) FROM irrelevant_but_valid_tablename WHERE foo = ?', [1]),
            '1'
        );
        $db->insert('table_with_bool', ['foo' => 'test', 'bar' => true]);
        $this->assertEquals(
            $db->single('SELECT COUNT(foo) FROM table_with_bool WHERE bar'),
            '1'
        );
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testBuildeInsertSql(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $statement = $db->buildInsertQuery('test_table', ['id', 'col1', 'col2']);
        $expected = '/insert into .test_table. \(.id., .col1., .col2.\) VALUES \(\?, \?, \?\)/i';
        $this->assertDatabaseRegExp($expected, $statement);
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     */
    public function testBuildInsertIgnoreSql(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);

        [$query] = $db->buildInsertQueryBoolSafe(
            'test_table',
            [
                'foo' => 'bar',
            ],
            false
        );

        $this->assertDatabaseRegExp(
            '/insert ignore into .test_table. \(.foo.\) VALUES \(\?\)/i',
            $query
        );
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     */
    public function testBuildInsertOnDuplicateKeyUpdate(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);

        [$query] = $db->buildInsertQueryBoolSafe(
            'test_table',
            [
                'foo' => 'bar',
            ],
            [
                'foo',
            ]
        );

        $this->assertDatabaseRegExp(
            '/insert into .test_table. \(.foo.\) VALUES \(\?\) ON DUPLICATE KEY UPDATE .foo. = VALUES\(.foo.\)/i',
            $query
        );
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     */
    public function testBuildInsertOnDuplicateKeyUpdateMultiple(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);

        [$query] = $db->buildInsertQueryBoolSafe(
            'test_table',
            [
                'foo' => 'bar',
                'bar' => 1,
                'baz' => 2,
            ],
            [
                'bar',
                'baz',
            ]
        );

        $this->assertDatabaseRegExp(
            '/insert into .test_table. \(.foo., .bar., .baz.\) VALUES \(\?, \?, \?\) 
ON DUPLICATE KEY UPDATE .bar. = VALUES\(.bar.\), .baz. = VALUES\(.baz.\)/i',
            $query
        );
    }
}

