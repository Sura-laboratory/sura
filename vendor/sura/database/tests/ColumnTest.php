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

class ColumnTest extends ColTest
{
    protected function getResultForMethod(Database $db, $statement, $offset, $params)
    {
        return $db->column($statement, $params, $offset);
    }
}

