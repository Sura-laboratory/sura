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

class InsertReturnIdTest extends InsertTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertReturnIdTableNameThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $db->insertReturnId('', ['foo' => 1], 'foo');
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertReturnIdMapArgThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $db->insertReturnId('irrelevant_but_valid_tablename', [[1]], 'foo');
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertReturnIdMapArgKeysThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $db->insertReturnId('irrelevant_but_valid_tablename', ['1foo' => 1], '1foo');
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertReturnId(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->assertEquals(
            $db->insertReturnId('irrelevant_but_valid_tablename', ['foo' => 'bar']),
            '1'
        );
        $this->assertEquals(
            $db->insertReturnId('irrelevant_but_valid_tablename', ['foo' => 'bar2']),
            '2'
        );
    }
    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertReturnIdException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(\Exception::class);
        $this->assertEquals(
            $db->insertReturnId('irrelevant_but_valid_tablename', [], 'bar'),
            'bar'
        );
    }
}

