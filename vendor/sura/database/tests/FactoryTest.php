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
use PHPUnit\Framework\TestCase;

/**
 * Class FactoryTest
 * @package Sura\Database\Tests
 */
class FactoryTest extends TestCase
{
    public function testFactoryCreate()
    {
        $this->assertInstanceOf(
            Database::class,
            Factory::create('sqlite::memory:')
        );
    }
}

