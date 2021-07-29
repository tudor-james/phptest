<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/env.php';

use Google\Cloud\Storage\StorageClient;

$app = array();
$app['mysql_user'] = $mysql_user;
$app['mysql_password'] = $mysql_password;
$app['mysql_dbname'] = "test";
$app['project_id'] = getenv('GCLOUD_PROJECT');

$servername = null;
$username = $app['mysql_user'];
$password = $app['mysql_password'];
$dbname = $app['mysql_dbname'];
$dbport = null;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 
	$dbport, "/cloudsql/phpinternaltest:europe-west2:phpinternaltest");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "\nConnected successfully\n";


echo "\ntesting gcloud php\n";

?>




