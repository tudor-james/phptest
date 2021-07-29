<?php

require __DIR__ . '/vendor/autoload.php';

public static function initUnixDatabaseConnection(
    string $username,
    string $password,
    string $dbName,
    string $connectionName,
    string $socketDir,
    array $conn_config
): PDO {
    try {
        # [START cloud_sql_mysql_pdo_create_socket]
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');
        $dbName = getenv('DB_NAME');
        $connectionName = getenv("CLOUD_SQL_CONNECTION_NAME");
        $socketDir = getenv('DB_SOCKET_DIR') ?: '/cloudsql';

        // Connect using UNIX sockets
        $dsn = sprintf(
            'mysql:dbname=%s;unix_socket=%s/%s',
            $dbName,
            $socketDir,
            $connectionName
        );

        // Connect to the database.
        $conn = new PDO($dsn, $username, $password, $conn_config);
        # [END cloud_sql_mysql_pdo_create_socket]
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
echo "\nConnected successfully\n";


echo "\ntesting gcloud php\n";

?>




