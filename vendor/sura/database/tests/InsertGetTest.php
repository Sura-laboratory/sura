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

class InsertGetTest extends InsertTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertGetTableNameThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $db->insertGet('', ['foo' => 1], 'foo');
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertGetMapArgThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $db->insertGet('irrelevant_but_valid_tablename', [[1]], 'foo');
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertGetMapArgKeysThrowsException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(InvalidArgumentException::class);
        $db->insertGet('irrelevant_but_valid_tablename', ['1foo' => 1], '1foo');
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertGet(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->assertEquals(
            $db->insertGet('irrelevant_but_valid_tablename', ['foo' => 'bar'], 'bar'),
            'bar'
        );
    }
    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testInsertGetException(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $this->expectException(\Exception::class);
        $this->assertEquals(
            $db->insertGet('irrelevant_but_valid_tablename', [], 'bar'),
            'bar'
        );
    }
}

