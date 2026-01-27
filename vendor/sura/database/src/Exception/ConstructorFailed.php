<?php

/*
 * Copyright (c) 2023 Sura
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace Sura\Database\Exception;

use Sura\Corner\CornerTrait;

/**
 * ConstructorFailed.
 *
 * @package Sura\Database
 */
class ConstructorFailed extends \RuntimeException implements ExceptionInterface
{
    use CornerTrait;

    /** @var \PDOException|null $realException */
    private $realException = null;

    /**
     * @param \PDOException $ex
     * @return ConstructorFailed
     */
    public function setRealException(\PDOException $ex): self
    {
        $this->realException = $ex;
        return $this;
    }

    /**
     * @return \PDOException|null
     */
    public function getRealException()
    {
        return $this->realException;
    }
}
