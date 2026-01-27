<?php

/*
 * Copyright (c) 2023 Sura
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Sura\Database;

use \Sura\Database\Exception as Issues;

/**
 * Class Factory
 *
 * @package Sura\Database
 */
abstract class Factory
{
    /**
     * Create a new Database object based on PDO constructors
     *
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array $options
     * @return \Sura\Database\Database
     * @throws Issues\ConstructorFailed
     */
    public static function create(
        string $dsn,
        string $username = null,
        string $password = null,
        array $options = []
    ): Database {
        return static::fromArray([$dsn, $username, $password, $options]);
    }
    
    /**
     * Create a new Database object from array of parameters
     *
     * @param array $config
     * @return \Sura\Database\Database
     * @throws Issues\ConstructorFailed
     */
    public static function fromArray(array $config): Database
    {

        /** @var string $dsn */
        $dsn      = $config[0];
        /** @var string|null $username */
        $username = $config[1] ?? null;
        /** @var string|null $password */
        $password = $config[2] ?? null;
        /** @var array $options */
        $options  = $config[3] ?? [];

        $dbEngine = '';
        $post_query = null;

        if (!\is_string($username)) {
            $username = '';
        }
        if (!\is_string($password)) {
            $password = '';
        }

        // Let's grab the DB engine
        if (strpos($dsn, ':') !== false) {
            $dbEngine = explode(':', $dsn)[0];
        }

        /** @var string $post_query */
        $post_query = '';

        // If no charset is specified, default to UTF-8
        switch ($dbEngine) {
            case 'mysql':
                if (\strpos($dsn, ';charset=') === false) {
                    $dsn .= ';charset=utf8mb4';
                }
                break;
            case 'pgsql':
                $post_query = "SET NAMES 'UNICODE'";
                break;
        }

        try {
            $pdo = new \PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            if (\strpos((string) $e->getMessage(), 'could not find driver') !== false) {
                throw (new Issues\ConstructorFailed(
                    'Could not create a PDO connection. Is the driver installed/enabled?'
                ))->setRealException($e);
            }
            
            if (\strpos((string) $e->getMessage(), 'unknown database') !== false) {
                throw (new Issues\ConstructorFailed(
                    'Could not create a PDO connection. Check that your database exists.'
                ))->setRealException($e);
            }
            
            // Don't leak credentials directly if we can.
            throw (new Issues\ConstructorFailed(
                'Could not create a PDO connection. Please check your username and password.'
            ))->setRealException($e);
        }

        if (!empty($post_query)) {
            $pdo->query($post_query);
        }

        return new Database($pdo, $dbEngine, $options);
    }
}
