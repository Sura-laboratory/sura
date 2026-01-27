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
use Sura\Framework\TestCase as PHPUnit_Framework_TestCase;

/**
 * Class DatabaseTest
 * @package Sura\Database\Tests
 */
abstract class DatabaseTest extends PHPUnit_Framework_TestCase
{

    /**
    * Data provider for arguments to be passed to Factory::create
    * These arguments will not result in a valid Database instance
    * @return array
    */
    public function badFactoryCreateArgumentProvider()
    {
        return [
            [
                'this-dsn-will-fail',
                'username',
                'putastrongpasswordhere'
            ],
        ];
    }

    /**
    * Data provider for arguments to be passed to Factory::create
    * These arguments will result in a valid Database instance
    * @return array
    */
    public function goodFactoryCreateArgumentProvider()
    {
        switch (getenv('DB')) {
            case false:
                return [
                    [
                        'sqlite',
                        'sqlite::memory:',
                        null,
                        null,
                        [],
                    ],
                ];
            break;
        }
        $this->markTestIncomplete(
            'Could not determine appropriate arguments for ' .
            Factory::class .
            '::create() from getenv()'
        );
        return [];
    }

    /**
    * Database data provider
    * Returns an array of callables that return instances of Database
    * @return array
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
                    function () use ($dsn, $username, $password, $options) {
                        return Factory::create(
                            $dsn,
                            $username,
                            $password,
                            $options
                        );
                    }
                ];
            },
            $this->goodFactoryCreateArgumentProvider()
        );
    }

    /**
    * Strict-type paranoia for a callable that is expected to return an Database instance
    * @param callable $cb
    * @return Database
    */
    protected function databaseExpectedFromCallable(callable $cb) : Database
    {
        return $cb();
    }

    /**
    * Remaps DatabaseWriteTest::goodFactoryCreateArgument2DatabaseProvider()
    */
    public function goodFactoryCreateArgument2DatabaseQuoteProvider()
    {
        $cbArgsSets = $this->goodFactoryCreateArgument2DatabaseProvider();
        $args = [
            [
                1,
                [
                    "'1'"
                ]
            ],
            [
                '1foo',
                [
                    "'1foo'"
                ]
            ]
        ];

        return array_reduce(
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

    public function assertDatabaseRegExp($match, $str)
    {
        if (method_exists($this, 'assertMatchesRegularExpression')) {
            $this->assertMatchesRegularExpression($match, $str);
            return;
        }
        if (method_exists($this, 'assertRegExp')) {
            $this->assertRegExp($match, $str);
            return;
        }
        $this->assertIsInt(preg_match($match, $str));
    }
}
