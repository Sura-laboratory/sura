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
use PDO;

class GetAvailableDriversTest extends DatabaseTest
{

    /**
     * @param $dsn
     * @param null $username
     * @param null $password
     * @param array $options
     * @dataProvider goodFactoryCreateArgumentProvider
     */
    public function testGetAvailableDrivers(
        $expectedDriver,
        $dsn,
        $username = null,
        $password = null,
        array $options = []
    ) {
        if (count(PDO::getAvailableDrivers()) < 1) {
            $this->markTestSkipped('No drivers available!');
        } else {
            $db = Factory::create($dsn, $username, $password, $options);
            $this->assertEquals(
                count(
                    array_diff_assoc(
                        PDO::getAvailableDrivers(),
                        $db->getAvailableDrivers()
                    )
                ),
                0
            );
            $this->assertEquals(
                count(
                    array_diff_assoc(
                        PDO::getAvailableDrivers(),
                        $db->getPdo()->getAvailableDrivers()
                    )
                ),
                0
            );
            $this->assertEquals(
                count(
                    array_diff_assoc(
                        $db->getAvailableDrivers(),
                        $db->getPdo()->getAvailableDrivers()
                    )
                ),
                0
            );
        }
    }
}

