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
use Sura\Database\Database;
use PDOException;

class InsertManyFlatTransactionTest extends DatabaseWriteTest
{
    /**
     * @dataProvider GoodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     *
     * @psalm-param callable():Database $cb
     */
    public function testInsertMany(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $db->insertMany('irrelevant_but_valid_tablename', [['foo' => '1'], ['foo' => '2']]);
        $expectedCount = $db->tryFlatTransaction(function (Database $db) : int {
            return (int) $db->single('SELECT COUNT(*) FROM irrelevant_but_valid_tablename');
        });
        $callbackWillThrow = function (Database $mightNotBeTheOtherDb) {
            $mightNotBeTheOtherDb->insertMany('irrelevant_but_valid_tablename', [['foo' => '3'], ['foo' => '4']]);

            throw new InsertManyFlatTransactionTestRuntimeException(
                'We pretend we made a call to something else that interupts a transaction'
            );
        };
        try {
            $db->tryFlatTransaction($callbackWillThrow);
        } catch (InsertManyFlatTransactionTestRuntimeException $e) {
            // we do nothing here on purpose
        }
        $this->assertEquals(
            $expectedCount,
            2
        );
    }
}

