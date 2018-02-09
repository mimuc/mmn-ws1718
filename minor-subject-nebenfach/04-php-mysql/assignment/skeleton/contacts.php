<?php

/**
 * Here's how this file is structured.
 *
 * First, we set up the database connection.
 * Hereby we create the database if it does not exist yet.
 * Then, we create the AddressBook table if it does not exist yet.
 *
 * Next, we build the HTML skeleton.
 *
 * Before we print the contacts table, we check if the user has submitted a new address with the form.
 * We need to check if the form was submitted and then create a database query to save the contact.
 *
 * Afterwards, we construct the form with the necessary attributes and elements to submit all contact information
 *
 * Finally we query the database for all contacts and print them out in a nicely formatted table.
 *
 */


/**
 * S T E P 1:  S E T U P   T H E   D A T A B A S E   A N D   T A B L E
 */

/*
 * this is an associative array that contains all the information necessary to connect to the MySQL server / database
 * TODO insert your own credentials here. If you use XAMPP you probably don't have to do this, but for the CIP MySQL DB you have to!
 * CIP hostname: db2.ifi.lmu.de (only accessible from the CIP pool).
 */
$connectionInfo = array("host" => "localhost", "username" => "root", "password" => "", "database" => "assignments");

$c = mysqli_connect(); // TODO use $connectionInfo as we did in the tutorial.

if (!mysqli_select_db($c, $connectionInfo['database'])) {
    $createDBQuery = "CREATE DATABASE IF NOT EXISTS " . $connectionInfo['database'];
    $createDBResult = mysqli_query($c, $createDBQuery);

    // first, check if the creation of the database succeeded (this will definitely fail at the CIP Pool)
    // it could be that your database user does not have the necessary rights to create databases.
    if (!$createDBResult) {
        // print the error so we can find out what went wrong (Google!)
        echo mysqli_error($c);
    } else {
        // let's try to re-select the database, now that we know that it was created.
        // if your user has the necessary rights, you should be able to work with the database right away
        // you can also use phpMyAdmin to see all databases and users.
        mysqli_select_db($c, $connectionInfo['database']);
    }
}

// initialize the database table.
// TODO replace the empty string with a valid SQL statement to create the AddressBook table.
// it should not be created if it already exists.
$createTableQuery = "";

// here is where the query is sent to the database.
$createTableResult = mysqli_query($c, $createTableQuery);
if (!$createTableResult) {
    echo mysqli_error($c);
}

/**
 * S T E P 2:   H T M L   S K E L E T O N
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Address Book with PHP and MySQL</title>
    <meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1, user-scalable=yes">
    <link rel="stylesheet" href="contacts.css"/>
</head>
<body>
<header><span>Address Book</span></header>
<div id="container">

    <?php

    /**
     * S T E P 3:   H A N D L E    F O R M   S U B M I S S I O N
     *              C R E A T E    A    N E W    D A T A B A S E   E N T R Y
     */

    // first simply check if the "submit" button was clicked.
    // Its name attribute is set to "Save", so this is what we check.
    if (isset($_POST['Save'])) {
        // make sure that all the information was submitted and no form input is missing:
        if (isset($_POST['FirstName']) &&
            isset($_POST['LastName']) &&
            isset($_POST['StreetAddress']) &&
            isset($_POST['City'])
        ) {
            $firstName = ''; // TODO use the information from $_POST
            $lastName = ''; // TODO use the information from $_POST
            $streetAddress = ''; // TODO use the information from $_POST
            $city = ''; // TODO use the information from $_POST

            $insertContactQuery = ''; // TODO put the query to insert the contact to the AddressBook here.
            $insertContactResult = null; // TODO run the query.

            if ($insertContactResult) { // the contact was successfully inserted.
                echo '<div class="notification success">
                        Contact ' . $firstName . ' ' . $lastName .
                      ' successfully inserted into the database.
                      </div>';
            } else { // the insertion somehow failed.
                echo '<div class="notification error">
                        Could not insert ' . $firstName . ' ' . $lastName .
                     ' into the database.
                     </div>';
            }
        } else { // at least one of the parameters is missing.
            echo '<div class="notification error">Please fill out the entire form!</div>';
        }
    }


    /**
     * S T E P 4:   S H O W   T H E   F O R M
     */
    ?>
    <!-- if the "action" is missing, the form will be sent to the same page -->
    <form method="post" id="contactForm">
        <!--
                TODO  add an <input> for:
                    a) the first name
                    b) the last name
                    c) the street address
                    d) the city
                Check the isset(...) above to find out the right name for each <input>
        -->
        <input type="submit" name="Save" value="Save contact!"/>
    </form>

    <?php
    /**
     * S T E P 5:   Q U E R Y   T H E   D A T A B A S E
     *              C R E A T E    A N   H T M L   T A B L E   F R O M   T H E   R E S U L T
     */

    $selectContactsQuery = ''; // TODO put the "select" statement here.
    $selectContactsResult = null; // TODO run the query

    if (!$selectContactsResult) { // something went wrong.
        echo '<div class="notification error">We are sorry, but we could not fetch the contacts.</div>';
    } else { //  all good, there is a result, even if it's "empty"

        echo '<table class="contacts">';
        echo '<thead><tr><th>First Name</th><th>Last Name</th><th>Street Address</th><th>City</th></tr>';
        echo '<tbody>';
        // now, loop through the rows in the result and create the content of the table
        // TODO: fetch a row from the $selectContactsResult
        // TODO: echo a table row <tr>
        // TODO: echo a table data cell (<td>) for each column and read the data from the row (FirstName, LastName, StreetAddress, City)
        // TODO: we recommend a while-loop for this
        echo '</tbody>';
        echo '</table>'; // .contacts

    }

    ?>
    <!--#container-->
</div>
</body>
</html>