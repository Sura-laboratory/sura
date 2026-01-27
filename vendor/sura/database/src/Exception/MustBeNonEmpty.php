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
 * Class MustBeNonEmpty
 * @package Sura\Database\Exception
 */
class MustBeNonEmpty extends \Exception implements CornerInterface
{
    use CornerTrait;

    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->supportLink = 'https://github.com/Sura/Database';
        $this->helpfulMessage = "By default, arrays passed to EasyStatement's in(), orIn(), andIn() methods must
not be empty.        

If you're generating a lot of dynamic arrays and wish to allow empty arrays to
soft-fail to an empty set, simply call setEmptyInStatementsAllowed(), like so:

    -     \$stmt = EasyStatement::open()->setEmptyInStatementsAllowed();
    +     \$stmt = EasyStatement::open()->setEmptyInStatementsAllowed(true);

Note that an empty IN statement yields an empty result. If you want it to fail
open (a.k.a. discard the IN() statement entirely), you'll need to implement
your own application logic to handle this behavior.";
    }
}
