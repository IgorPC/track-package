<?php

use IgorPC\TrackPackage\DB\Connection;

include (__DIR__.'/../autoload.php');

define('DB_HOST', '');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');

function connect()
{
    $conn = new Connection(DB_NAME, DB_HOST, DB_USER, DB_PASS);
    return $conn->connect();
}




