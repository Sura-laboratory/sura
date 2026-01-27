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

use Sura\Database\Exception\ConstructorFailed;
use Sura\Database\Factory;

class ConstructorFailedTest extends DatabaseTest
{

    /**
     * @dataProvider badFactoryCreateArgumentProvider
     * @param $dsn
     * @param null $username
     * @param null $password
     * @param array $options
     */
    public function testConstructorFailed($dsn, $username = null, $password = null, $options = [])
    {
        $this->expectException(ConstructorFailed::class);
        Factory::create($dsn, $username, $password, $options);
    }
}

