<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Coffee Bookkeeper</title>
</head>
<body>
<div style="margin: 50px 0">
  <form method="post">
    <label>
      Beverage:
      <select name="beverage">
        <option value="coffee">Coffee (4€)</option>
        <option value="tea">Tea (1.50€)</option>
        <option value="coke">Coke (3€)</option>
        <option value="fanta">Fanta (3€)</option>
        <option value="sprite">Sprite (3€)</option>
        <option value="water">Water (3€)</option>
      </select>
    </label>
    <label>Your Name: <input type="text" name="person"></label>
    <input type="submit">
  </form>
</div>
<?php
$host = 'localhost'; ##TODO replace this with your server address
$user = 'root'; ##TODO replace this with your user name for the MySQL database, often "root"
$password = '5a7cxy9t'; ##TODO replace this with your password for the MySQL database, often ""
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


// now let's add some REAL data to the coffee booking table.

$prices = array("coffee" => 4, "tea" => "1.5", "coke" => 3, "fanta" => 3, "sprite" => 3, "water" => 1);

// can also be passed via a form (POST)
if (isset($_POST['beverage']) && isset($_POST['person'])) {
  $beverage = $_POST['beverage'];
  $person = $_POST['person'];
  if (key_exists($beverage, $prices)) {
    $reason = $beverage;
    $amount = $prices[$beverage];

    $now = date('Y-m-d H:i:s'); // contains the current time;
    $queryString = "INSERT INTO expenses (amount,reason,person,spending_date) 
                VALUES ($amount,'$reason','$person','$now')";
    mysqli_query($c, $queryString);
  }
}

$queryString = "SELECT * FROM expenses";

$results = mysqli_query($c, $queryString);
echo '<table>';
echo '<tr><td>ID</td><td>Amount</td><td>Beverage</td><td>Person</td><td>Date</td></tr>';
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