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

use Exception;
use Sura\Database\Database;
use Sura\Database\Factory;

/**
 * Class DatabaseTest
 * @package Sura\Database\Tests
 */
abstract class DatabaseWriteTest extends DatabaseTest
{

    /**
    * Database data provider
    * Returns an array of callables that return instances of Database
    * @return array
    *
    * @psalm-return array<int, array{0:callable():Database}>
    *
    * @see DatabaseTest::goodFactoryCreateArgumentProvider()
    */
    public function goodFactoryCreateArgument2DatabaseProvider()
    {
        return array_map(
            function (array $arguments) {
                $dsn = $arguments[1];
                $username = isset($arguments[2]) ? $arguments[2] : null;
                $password = isset($arguments[3]) ? $arguments[3] : null;
                $options = isset($arguments[4]) ? $arguments[4] : [];
                return [
                    function () use ($dsn, $username, $password, $options) : Database {
                        $factory = Factory::create(
                            $dsn,
                            $username,
                            $password,
                            $options
                        );
                        try {
                            $factory->run(
                                'CREATE TABLE irrelevant_but_valid_tablename (foo char(36) PRIMARY KEY)'
                            );
                            $factory->run(
                                'CREATE TABLE table_with_bool (foo char(36) PRIMARY KEY, bar BOOLEAN)'
                            );
                        } catch (Exception $e) {
                            $this->markTestSkipped($e->getMessage());
                            return null;
                        }
                        return $factory;
                    }
                ];
            },
            $this->goodFactoryCreateArgumentProvider()
        );
    }

    /**
    * Remaps DatabaseWriteTest::goodFactoryCreateArgument2DatabaseProvider()
    */
    public function goodFactoryCreateArgument2DatabaseInsertManyProvider()
    {
        $cbArgsSets = $this->goodFactoryCreateArgument2DatabaseProvider();
        $args = [
            [
                [
                    ['foo' => '1'],
                    ['foo' => '2'],
                    ['foo' => '3'],
                ],
            ],
        ];

        return \array_reduce(
            $args,
            function (array $was, array $is) use ($cbArgsSets) {
                foreach ($cbArgsSets as $cbArgs) {
                    $args = array_values($is);
                    foreach (array_reverse($cbArgs) as $cbArg) {
                        array_unshift($args, $cbArg);
                    }
                    $was[] = $args;
                }

                return $was;
            },
            []
        );
    }
}
