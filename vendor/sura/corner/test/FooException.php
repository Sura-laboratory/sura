<?php
declare(strict_types=1);
namespace Sura\Corner\Tests;

use Sura\Corner\Exception;

/**
 * Class FooException
 * @package Sura\Corner\Tests
 */
class FooException extends Exception
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->helpfulMessage = "This is an example of the Exception class";
        $this->supportLink = "https://github.com/Sura/corner";
    }
}
