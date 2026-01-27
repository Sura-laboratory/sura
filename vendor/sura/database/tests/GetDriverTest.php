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

use Sura\Database\Factory;

class GetDriverTest extends DatabaseTest
{
    /**
     * @param $dsn
     * @param string|null $username
     * @param string|null $password
     * @param array $options
     * @param string $expectedDriver
     *
     * @dataProvider goodFactoryCreateArgumentProvider
     */
    public function testGetDriver(
        $expectedDriver,
        $dsn,
        $username = null,
        $password = null,
        $options = []
    ) {
        $db = Factory::create($dsn, $username, $password, $options);
        $this->assertEquals($db->getDriver(), $expectedDriver);
    }
}

