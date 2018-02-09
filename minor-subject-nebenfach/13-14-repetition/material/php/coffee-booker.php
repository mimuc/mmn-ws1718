<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title></title>
</head>
<body>

<form method="post">
  <input type="text" name="person" placeholder="Your Name">

  <select name="beverage">
    <option value="coffee">Coffee 3€</option>
    <option value="tea">Tea 1.5€</option>
    <option value="coke">Coke 2€</option>
  </select>

  <input type="submit" value="Book!">
</form>


<?php
$host = 'localhost'; ##TODO replace this with your server address
$user = 'mmn'; ##TODO replace this with your user name for the MySQL database, often "root"
$password = 'mmn.demo.pass'; ##TODO replace this with your password for the MySQL database, often ""
$database = 'mmn1617'; ##TODO replace this with the name of your database.

// let's create a connection to the database.
$c = mysqli_connect($host, $user, $password, $database);

// first, let's create a table if it doesn't exist yet.
$createTableQuery = "CREATE TABLE IF NOT EXISTS expenses 
(id INT PRIMARY KEY AUTO_INCREMENT,
 amount FLOAT,
 reason VARCHAR(255),
 person VARCHAR(255),
 spending_date DATETIME
)";

mysqli_query($c, $createTableQuery);

// now let's add some data to the coffee booking table.


if (isset($_POST['person']) && isset($_POST['beverage'])
) {

  $prices = array(
    "coffee" => 3,
    "tea" => 1.5,
    "coke" => 2
  );

  $beverage = $_POST['beverage'];

  $amount = $prices[$beverage];

  $person = $_POST['person'];
  $now = date('Y-m-d H:i:s'); // contains the current time;
  $queryString = "INSERT INTO expenses (amount,reason,person,spending_date) 
                VALUES ($amount,'$beverage','$person','$now')";
  mysqli_query($c, $queryString);
}


$queryString = "SELECT * FROM expenses";

$results = mysqli_query($c, $queryString);
echo '<table>';
while ($row = mysqli_fetch_assoc($results)) {
  echo '<tr>';
  echo '<td>' . $row['id'] . '</td>';
  echo '<td>' . $row['amount'] . '</td>';
  echo '<td>' . $row['reason'] . '</td>';
  echo '<td>' . $row['person'] . '</td>';
  echo '<td>' . $row['spending_date'] . '</td>';
  echo '</tr>';
}
echo '</table>';

// close the connection to the database.
mysqli_close($c);
?>
</body>
</html>