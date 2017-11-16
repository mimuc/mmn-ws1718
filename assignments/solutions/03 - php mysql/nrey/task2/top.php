<?php
  session_start();
  include_once('connectionInfo.private.php'); // TODO create this file!
  require_once('AuthHandler.php');
  require_once('DBHandler.php');
  include('cardItem.php');
  include('dashboardNavi.php');
  include('userForm.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="http://www.medien.ifi.lmu.de/lehre/ws1617/mmn/uebung/material/assignments.css">
    <link rel="stylesheet" href="./notes.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <title>Note App</title>
    <style>
    body {
      text-align: center;
      font-size: 16px;
      color: #444;
      font-family: 'Roboto','Helvetica' sans-serif!important;
    }
    </style>
</head>
<body>
