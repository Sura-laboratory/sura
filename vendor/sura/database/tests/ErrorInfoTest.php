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
 * Class ErrorInfoTest
 * @package Sura\Database\Tests
 */
class ErrorInfoTest extends DatabaseTest
{

    /**
     * @dataProvider goodFactoryCreateArgument2DatabaseProvider
     * @param callable $cb
     */
    public function testNoError(callable $cb)
    {
        $db = $this->databaseExpectedFromCallable($cb);

        $info = $db->errorInfo();
        $this->assertTrue(is_array($info));
        $this->assertSame($info[0], '00000');
        $this->assertSame($info[1], null);
        $this->assertSame($info[2], null);
    }
}
