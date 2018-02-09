<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="author" content="Tobias Seitz">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parking lot</title>
    <link rel="stylesheet" href="parking-lot.css">
</head>
<body>
<h1>Parking Lot Monitor</h1>
<div class="spotContainer">

    <?php
    $capacity = 10;

    if (!isset($_SESSION['availableSpots'])) {
        $_SESSION['availableSpots'] = $capacity;
    }

    if (isset($_POST['plus'])) {
        $_SESSION['availableSpots']--;
    }
    if (isset($_POST['minus']) && $_SESSION['availableSpots'] <= $capacity) {
        $_SESSION['availableSpots']++;
    }


    $spotsAvailable = $_SESSION['availableSpots'] > 0;
    $lotEmpty = $_SESSION['availableSpots'] == $capacity;


    for ($i = 0; $i < $_SESSION['availableSpots']; $i++) {
        echo "<div class='spot available'></div>";
    }

    for ($i = 0; $i < $capacity - $_SESSION['availableSpots']; $i++) {
        echo "<div class='spot occupied'></div>";
    }

    ?>
</div>

<form method="post" class="buttonForm">
    <div class="help">Hit + when a car enters, hit - when a car leaves.</div>
    <button type="submit" name="plus"
        <?php if (!$spotsAvailable) echo "disabled"; ?>>+</button>
    <button type="submit" name="minus"
        <?php if ($lotEmpty) echo "disabled"; ?>>-</button>
</form>
</body>
</html>