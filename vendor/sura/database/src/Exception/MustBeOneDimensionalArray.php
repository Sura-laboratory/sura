<?php

/*
 * Copyright (c) 2023 Sura
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

declare(strict_types=1);
namespace Sura\Database\Exception;

use Sura\Corner\CornerInterface;
use Sura\Corner\CornerTrait;
use Throwable;

/**
 * Class MustBeOneDimensionalArray
 * @package Sura\Database\Exception
 */
class MustBeOneDimensionalArray extends \InvalidArgumentException implements CornerInterface
{
    use CornerTrait;

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->supportLink = 'https://github.com/Sura/Database#only-one-dimensional-arrays-are-allowed';
        $this->helpfulMessage = "Many of the Database methods expect variadic parameters.

Instead of doing something like this:

    \$rows = \$db->run(\$query, \$params);

You want to do something like this:

    \$rows = \$db->run(\$query, ...\$params);
    \$rows = \$db->safeQuery(\$query, \$params);

A list of variadic methods and their array-expecting equivalents is as follows:
 
   * col() -> column(): array
   * cell() -> single(): scalar
   * first() -> column(): array
   * exists() -> single(): bool
   * q() -> safeQuery(): array[]
   * row() -> safeQuery(): array
   * run() -> safeQuery(): array[]
";
    }
}

