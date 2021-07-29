<?php

require __DIR__ . '/vendor/autoload.php';

try {
    $username = 'phptest';
    $password = 'cerebus';
    $dbName = 'test';
    $connectionName = 'phpinternaltest:europe-west2:phpinternaltest';
    $socketDir = '/cloudsql';

    // Connect using UNIX sockets
    $dsn = "mysql:host=172.18.0.3:3306;dbname=test";
    // Connect to the database.
    $conn = new PDO($dsn, $username, $password);

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




