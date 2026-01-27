<?php

declare(strict_types=1);

namespace Sura\Corner;

/**
 * Class Error
 * @package Sura\Corner
 */
class Error extends \Error implements CornerInterface
{
    use CornerTrait;
}
