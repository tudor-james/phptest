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

use Google\Cloud\Samples\CloudSQL\MySQL\DBInitializer;
use Google\Cloud\Samples\CloudSQL\MySQL\Votes;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

// Create and set the dependency injection container.
$container = new Container;
AppFactory::setContainer(new Psr11Container($container));

// add the votes manager to the container.
$container['votes'] = function (Container $container) {
    return new Votes($container['db']);
};

// Setup the database connection in the container.
$container['db'] = function () {
    # [START cloud_sql_mysql_pdo_timeout]
    // Here we set the connection timeout to five seconds and ask PDO to
    // throw an exception if any errors occur.
    $connConfig = [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    # [END cloud_sql_mysql_pdo_timeout]

    $username = getenv('DB_USER');
    $password = getenv('DB_PASS');
    $dbName = getenv('DB_NAME');

    if (empty($username = getenv('DB_USER'))) {
        throw new RuntimeException('Must supply $DB_USER environment variables');
    }
    if (empty($password = getenv('DB_PASS'))) {
        throw new RuntimeException('Must supply $DB_PASS environment variables');
    }
    if (empty($dbName = getenv('DB_NAME'))) {
        throw new RuntimeException('Must supply $DB_NAME environment variables');
    }

    if ($dbHost = getenv('DB_HOST')) {
        return DBInitializer::initTcpDatabaseConnection(
            $username,
            $password,
            $dbName,
            $dbHost,
            $connConfig
        );
    } else {
        $connectionName = getenv('CLOUDSQL_CONNECTION_NAME');
        $socketDir = getenv('DB_SOCKET_DIR') ?: '/cloudsql';
        return DBInitializer::initUnixDatabaseConnection(
            $username,
            $password,
            $dbName,
            $connectionName,
            $socketDir,
            $connConfig
        );
    }
};

// Configure the templating engine.
$container['view'] = function () {
    return Twig::create(__DIR__ . '/../views');
};

// Create the application.
$app = AppFactory::create();

// Add the twig middleware
$app->add(TwigMiddleware::createFromContainer($app));

// Setup error handlinmg
$app->addErrorMiddleware(true, false, false);

return $app;
