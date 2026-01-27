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
class QuoteTest extends DatabaseTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseQuoteProvider
     * @depends      Sura\Database\Tests\EscapeIdentifierTest::testEscapeIdentifier
     * @depends      Sura\Database\Tests\EscapeIdentifierTest::testEscapeIdentifierThrowsSomething
     * @param callable $cb
     * @param $quoteThis
     * @param array $expectOneOfThese
     */
    public function testQuote(callable $cb, $quoteThis, array $expectOneOfThese)
    {
        $db = $this->databaseExpectedFromCallable($cb);

        $this->assertTrue(count($expectOneOfThese) > 0);

        $matchedOneOfThose = false;
        $quoted = $db->quote((string)$quoteThis);

        foreach ($expectOneOfThese as $expectThis) {
            if ($quoted === $expectThis) {
                $this->assertSame($quoted, $expectThis);
                $matchedOneOfThose = true;
            }
        }
        if (!$matchedOneOfThose) {
            $this->assertTrue(
                false,
                'Did not match ' . $quoted . ' against any of ' . implode('; ', $expectOneOfThese)
            );
        }
    }
}

