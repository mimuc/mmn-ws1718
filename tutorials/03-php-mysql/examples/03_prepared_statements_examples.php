<?php

// TODO: replace with 00_connectionInfo.php or create 00_connectionInfo.private.php
include_once('00_connectionInfo.private.php');

// $c is then a mysqli object representing the connection to the database.
$c = new mysqli($host, $user, $password, $db);


// Example 1: Create a Table
$query_createPeopleTable = "CREATE TABLE IF NOT EXISTS `people` ( 
  `id` INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
  `firstName` VARCHAR(100) NOT NULL , 
  `lastName` VARCHAR(100) NOT NULL , 
  `age` INT(3) NULL
  )";

$statement = $c->prepare($query_createPeopleTable);

if ($statement->execute()) {
  echo "<p>successfully created people table";
};


// Example 2: Inserting values

// this array holds information about people that we want to insert into the database
// it's actually a 2-d array that holds other uniform arrays.
$dummies = array(
  array("first" => "John", "last" => "Doe", "age" => 20),
  array("first" => "Mary", "last" => "Jane", "age" => 23),
);

$insertStatement = $c->prepare("INSERT INTO `people` 
  (`firstName`, `lastName`, `age`) VALUES 
  (?, ?, ?)");

// warning: this is executed each time the script is accessed! --> duplicate entries
foreach ($dummies as $person) {
  // we reuse the statement and just bind the values from the current iteration to it.
  $insertStatement->bind_param("ssi", $person['first'], $person['last'], $person['age']);
  if ($insertStatement->execute()) {
    echo "<p>Inserted {$person['first']} {$person['last']} who is {$person['age']} years old.</p>";
  };
}


// Example 3: Querying the database
$query_selectPeople = "SELECT id, firstName, age 
                       FROM `people` WHERE age > ?";


$minimumAge = 19;
echo "<p>These people are older than $minimumAge:</p>";
// this shows you the "full" flow of prepared statements
// 1) prepare
$selectStatement = $c->prepare($query_selectPeople);
// 2) bind
$selectStatement->bind_param("i", $minimumAge);
// 3a) execute
$selectStatement->execute();
// optional) bind result
$selectStatement->bind_result($id, $firstName, $age);

// 4) fetch
while($selectStatement->fetch()){
  echo "<div>$id: $firstName ($age)</div>";
}