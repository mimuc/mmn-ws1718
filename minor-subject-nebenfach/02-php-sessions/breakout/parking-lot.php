<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="author" content="Tobias Seitz">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parking lot Monitor</title>
    <link rel="stylesheet" href="parking-lot.css">
</head>
<body>
<h1>Parking Lot Monitor</h1>
<div class="spotContainer">

    <?php
    $capacity = 10;

    if (!isset($_SESSION['availableSpots'])) {
        // TODO initialize the availalbe spots with the capacity.
    }

    // TODO if the user hits the + button, then $_POST['plus'] contains data.
    // TODO decrease the number of available spots in that case
    // OPTIONAL: Make sure that the number is not decreased if we have reached 0
    # your code here

    // TODO if the user hits the - button, then $_POST['minus'] contains data.
    // TODO increase the number of available spots in that case
    // TODO make sure that you do not add more than $capacity spaces.
    # your code here


    $spotsAvailable = true; // TODO find out if there are spots available and set the value of this variable
    $lotEmpty = true;  // TODO find out if the parking lot is empty and set the value of this variable.


    // TODO: show some kind of information about how many spots are available.
    // HINT: you can use this: echo "<div class='spot available'></div>" or "<div class='spot occupied'></div>"

    ?>
</div>

<form method="post" class="buttonForm">
    <div class="help">Hit + when a car enters, hit - when a car leaves.</div>
    <button type="submit" name="plus">+</button>
    <button type="submit" name="minus">-</button>
</form>
</body>
</html>