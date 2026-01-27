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

/**
 * Class MustBeArrayOrEasyStatement
 * @package Sura\Database\Exception
 */
class MustBeArrayOrEasyStatement extends \TypeError implements CornerInterface
{
    use CornerTrait;

    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->supportLink = 'https://github.com/Sura/Database';
        $this->helpfulMessage = "This method expects either a 1-dimensional array with string keys,
or an EasyStatement object.

If you're not using the EasyStatement class in your code, make sure you're passing an array.

Each array element should be a key => value pair, where the key is the column name.";
    }
}
