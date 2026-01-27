<?php

/*
 * Copyright (c) 2023 Sura
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

declare (strict_types=1);

namespace Sura\Database\Tests;

use Sura\Database\Database;
use PDO;

class EscapeLikeTest extends DatabaseTest
{
    public function dataValues()
    {
        return [
            // input, expected
            ['plain', 'plain'],
            ['%single', '\\%single'],
            ['%double%', '\\%double\\%'],
            ['_under_score_', '\\_under\\_score\\_'],
            ['%mix_ed', '\\%mix\\_ed'],
            ['\\%escaped?', '\\\\\\%escaped?'],
        ];
    }

    /**
     * @dataProvider dataValues
     */
    public function testEscapeLike($input, $expected)
    {
        // This defines sqlite, but mysql and postgres share the same rules
        $Database = new Database($this->getMockPDO(), 'sqlite');

        $output = $Database->escapeLikeValue($input);

        $this->assertSame($expected, $output);
    }

    public function dataMSSQLValues()
    {
        return array_merge($this->dataValues(), [
            // input, expected
            ['[range]', '\\[range\\]'],
            ['[^negated]', '\\[^negated\\]'],
        ]);
    }
    /**
     * @dataProvider dataMSSQLValues
     */
    public function testMSSQLEscapeLike($input, $expected)
    {
        $Database = new Database($this->getMockPDO(), 'mssql');

        $output = $Database->escapeLikeValue($input);

        $this->assertSame($expected, $output);
    }

    private function getMockPDO(): PDO
    {
        $mock = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('setAttribute')->willReturn(true);

        return $mock;
    }
}

