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
 * Class DatabaseTest
 * @package Sura\Database\Tests
 */
class ErrorCodeTest extends DatabaseTest
{

    /**
     * @param callable $cb
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     */
    public function testNoError(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);

        $this->assertSame($db->errorCode(), '00000');
    }
}
