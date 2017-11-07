<?php
/**
 * Created by PhpStorm.
 * User: Tobi
 * Date: 02.11.17
 * Time: 10:41
 */


/**
 *
 *
 * USE THIS FILE TO SET UP YOUR DATABASE FOR FUTURE USE
 *
 *
 *
 */


// TODO: replace with 00_connectionInfo.php or create 00_connectionInfo.private.php
include_once('00_connectionInfo.private.php');

$c = new mysqli($host,$user,$password);

if (!$c){
  // if the connection failed, we want to print the error message and stop the script immediately.
  die($c->error);
}

// queries:
$queryCreateDB = "CREATE DATABASE IF NOT EXISTS $db";

// run the create database query:
if($c->query($queryCreateDB)){
  echo "<p>Successfully created database $db</p>";
} else{
  echo $c->error;
};


// now use the db for future queries:
if($c->select_db($db)){
  echo "<p>Switched to database $db</p>";
} else {
  echo "<p>Could not switch to database $db</p>";
}


// we don't have anything at this point, so we close the connection
$c->close();