<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

<?php

// this file does not work as you expect...

echo "<p>Hallo, " . $_POST['Name'] . "!</p>";

if(isset($_POST['add'])) {

  if( !isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
  }

  $oldCount = $_SESSION['count'];


  $_SESSION['count'] = $oldCount + $_POST['add'];
  echo $oldCount . " + "
                 . $_POST['add']
                 . " = " . $_SESSION['count'];


}
?>
<form method="POST">
  <input type="text" name="Name" placeholder="your name">
    <input type="number"
           placeholder=""
           name="add">
    <input type="submit"
           name="submit"
           value="Add">
</form>
</body>
</html>