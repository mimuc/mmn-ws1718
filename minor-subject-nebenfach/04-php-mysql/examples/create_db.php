<?php


$connectionInfo = array(
    "host" => "localhost",
    "user" => "root",
    "password" => ""
);

// include_once('connectionInfo.private.php'); // TODO uncomment this.

$c = mysqli_connect($connectionInfo['host'],
    $connectionInfo['user'],
    $connectionInfo['password']);

if ($c) {
    echo "<div>Connection has been successfully established</div>";
} else {
    die("<div>Could NOT connect to database</div>");
}

$query = "CREATE DATABASE IF NOT EXISTS mmn1617";
$result = mysqli_query($c, $query);

if (!$result) {
    echo mysqli_error($c);
} else {
    echo "<div>Database mmn1617 was created</div>";
}

?>