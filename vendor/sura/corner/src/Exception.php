<?php

declare(strict_types=1);

namespace Sura\Corner;

/**
 * Class Exception
 * @package Sura\Corner
 */
class Exception extends \Exception implements CornerInterface
{
    use CornerTrait;
}
