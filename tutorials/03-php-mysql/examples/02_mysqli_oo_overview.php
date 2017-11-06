<?php


// Please note: this file doesn't really do anything!

/*
 * Usually, localhost works, but
 * sometimes you need to put "127.0.0.1" instead.
 * This depends on the user you'll use later.
 * In phpMyAdmin, you can set the privileges for the user.
 * You should !not! use the root user in a real-world setting.
 */
$host = "localhost";
/*
 * the user name. If you're running MySQL with the
 * default configuration, the default user name is "root"
 * There is some information here: http://www.liquidweb.com/kb/create-a-mysql-user-on-linux-via-command-line/
 */
$user = '<INSERT_USER_HERE>';

/*
 * writing passwords into script is not the best idea
 * but for the exercises it's fine.
 * UNLESS: You push it to GitHub.
 * That's something you don't want to do.
 *
 * For XAMPP the default password is ""
 */
$password = "<INSERT_DB_PASSWORD_HERE>";

$database = "myDB";
// $c is then a mysqli object representing the connection to the database.
$c = new mysqli($host,$user,$password,$database);

$query = "SELECT * FROM mytable";

/*
 * rather than passing $c as a parameter, we now can call
 * its query method passing the SQL query as a parameter.
 */
$results = $c->query($query);


$results->fetch_assoc();
$results->fetch_row();

/*
 * if you want to fetch all results at once
 * (not recommended for larger result sets)
 * you can do this with fetch_all
 * the result is a two dimensional array.
 */
$results->fetch_all(MYSQLI_BOTH);
$results->fetch_all(MYSQLI_ASSOC);
$results->fetch_all(MYSQLI_NUM);

$c->close();