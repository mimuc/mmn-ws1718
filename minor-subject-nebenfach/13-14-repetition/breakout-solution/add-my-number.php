<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title></title>
</head>
<body>

<?php
if (isset($_POST['add']) && !empty($_POST['add'])) {

  if (!isset($_SESSION['result'])) {
    $_SESSION['result'] = 0;
  }

  $oldNumber = $_SESSION['result'];
  $addedNumber = $_POST['add'];
  $newResult = $_SESSION['result'] + $addedNumber;


  echo "$oldNumber + $addedNumber = $newResult";
  $_SESSION['result'] += $_POST['add'];
}
?>
<form method="POST">
  <input type="number"
         placeholder=""
         name="add">
  <input type="submit"
         name="submit"
         value="Add">
</form>
</body>
</html>