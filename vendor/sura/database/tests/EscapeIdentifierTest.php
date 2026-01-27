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
use Sura\Database\Exception as Issues;
use PHPUnit_Framework_Error;
use TypeError;

class EscapeIdentifierTest extends DatabaseTest
{

    /**
    * Database data provider
    * Returns an array of callables that return instances of Database
    * @return array
    * @see DatabaseTest::goodFactoryCreateArgument2DatabaseProvider()
    */
    public function goodFactoryCreateArgument2DatabaseWithIdentifierProvider()
    {
        $provider = [
            [
                'foo',
                [true, false],
            ],
            [
                'foo1',
                [true, false],
            ],
            [
                'foo_2',
                [true, false],
            ],
            [
                'foo.bar',
                [true],
            ],
            [
                'foo.bar.baz',
                [true],
            ],
            [
                'foo.bar.baz.why.would.an.identifier.even.be.this.long.anyway',
                [true],
            ],
        ];
        return array_reduce(
            $this->goodFactoryCreateArgument2DatabaseProvider(),
            function (array $was, array $cbArgs) use ($provider) {
                foreach ($provider as $args) {
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

    /**
    * Database data provider
    * Returns an array of callables that return instances of Database
    * @return array
    * @see DatabaseTest::goodFactoryCreateArgument2DatabaseProvider()
    */
    public function goodFactoryCreateArgument2DatabaseWithBadIdentifierProvider()
    {
        $identifiers = [
            1,
            '2foo',
            'foo 3',
            'foo-4',
            null,
            false,
            []
        ];
        return array_reduce(
            $this->goodFactoryCreateArgument2DatabaseProvider(),
            function (array $was, array $cbArgs) use ($identifiers) {
                foreach ($identifiers as $identifier) {
                    $args = [$identifier];
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

    private function getExpectedEscapedIdentifier($string, $driver, $quote, bool $allowSeparators)
    {
        if ($allowSeparators) {
            $str = \preg_replace('/[^\.0-9a-zA-Z_]/', '', $string);
            if (\strpos($str, '.') !== false) {
                $pieces = \explode('.', $str);
                foreach ($pieces as $i => $p) {
                    $pieces[$i] = $this->getExpectedEscapedIdentifier($p, $driver, $quote, false);
                }
                return \implode('.', $pieces);
            }
        } else {
            $str = \preg_replace('/[^0-9a-zA-Z_]/', '', $string);
        }

        if ($quote) {
            switch ($driver) {
                case 'mssql':
                    return '[' . $str . ']';
                case 'mysql':
                    return '`' . $str . '`';
                default:
                    return '"' . $str . '"';
            }
        }
        return $str;
    }

    /**
     * @param callable $cb
     * @param $identifier
     * @param bool[] $withAllowSeparators
     * @dataProvider goodFactoryCreateArgument2DatabaseWithIdentifierProvider
     */
    public function testEscapeIdentifier(callable $cb, $identifier, array $withAllowSeparators)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $db->setAllowSeparators(false); // resetting to default
        foreach ($withAllowSeparators as $allowSeparators) {
            $db->setAllowSeparators($allowSeparators);
            $this->assertEquals(
                $db->escapeIdentifier($identifier, true),
                $this->getExpectedEscapedIdentifier($identifier, $db->getDriver(), true, $allowSeparators)
            );
            $this->assertEquals(
                $db->escapeIdentifier($identifier, false),
                $this->getExpectedEscapedIdentifier($identifier, $db->getDriver(), false, $allowSeparators)
            );
        }
        $db->setAllowSeparators(false); // resetting to default
    }

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseWithBadIdentifierProvider
     * @depends      testEscapeIdentifier
     * @param callable $cb
     * @param $identifier
     */
    public function testEscapeIdentifierThrowsSomething(callable $cb, $identifier)
    {
        $db = $this->databaseExpectedFromCallable($cb);
        $thrown = false;
        try {
            $db->escapeIdentifier($identifier);
        } catch (Issues\InvalidIdentifier $e) {
            $this->assertTrue(true);
            $thrown = true;
        } catch (TypeError $e) {
            $this->assertTrue(true);
            $thrown = true;
        } catch (PHPUnit_Framework_Error $e) {
            if (preg_match(
                (
                        '/^' .
                        preg_quote(
                            ('Argument 1 passed to ' . Database::class . '::escapeIdentifier()'),
                            '/'
                        ) .
                        ' must be an instance of string, [^ ]+ given$/'
                    ),
                $e->getMessage()
            )
            ) {
                $this->assertTrue(true);
                $thrown = true;
            } else {
                throw $e;
            }
        } finally {
            if (!$thrown) {
                $this->assertTrue(
                    false,
                    (
                        'Argument 2 of ' .
                        static::class .
                        '::' .
                        __METHOD__ .
                        '() must cause either ' .
                        Issues\InvalidIdentifier::class .
                        ' or ' .
                        TypeError::class .
                        ' (' .
                            var_export($identifier, true) .
                        ')'
                    )
                );
            }
        }
    }
}

