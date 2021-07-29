<?php
/*
 * Copyright 2020 Google LLC.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace Google\Cloud\Samples\CloudSQL\MySQL;

use PDO;
use PDOException;
use RuntimeException;

class DBInitializer
{

    /**
     *  @param $username string username of the database user
     *  @param $password string password of the database user
     *  @param $dbName string name of the target database
     *  @param $dbHost string IP address or domain of the target database
     *  @param $connConfig array driver-specific options for PDO
     */
    public static function initTcpDatabaseConnection(
        string $username,
        string $password,
        string $dbName,
        string $dbHost,
        array $connConfig
    ): PDO {
        try {
            # [START cloud_sql_mysql_pdo_create_tcp]
            $username = 'phptest';
            $password = 'cerebrus';
            $dbName = 'test';
            $dbHost = "172.18.0.3";

            // Connect using TCP
            $dsn = sprintf('mysql:dbname=%s;host=%s', $dbName, $dbHost);

            // Connect to the database
            $conn = new PDO($dsn, $username, $password, $connConfig);
            # [END cloud_sql_mysql_pdo_create_tcp]
        } catch (TypeError $e) {
            throw new RuntimeException(
                sprintf(
                    'Invalid or missing configuration! Make sure you have set ' .
                    '$username, $password, $dbName, and $dbHost (for TCP mode) ' .
                    'or $connectionName (for UNIX socket mode). ' .
                    'The PHP error was %s',
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        } catch (PDOException $e) {
            throw new RuntimeException(
                sprintf(
                    'Could not connect to the Cloud SQL Database. Check that ' .
                    'your username and password are correct, that the Cloud SQL ' .
                    'proxy is running, and that the database exists and is ready ' .
                    'for use. For more assistance, refer to %s. The PDO error was %s',
                    'https://cloud.google.com/sql/docs/mysql/connect-external-app',
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        return $conn;
    }
}
