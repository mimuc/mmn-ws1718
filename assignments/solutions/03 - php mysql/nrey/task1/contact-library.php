<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Library</title>
    <style>
        body,html{
            font-family: 'Helvetica Neue','Helvetica', 'Arial', sans-serif;
            font-size: 20px;
            margin:0;
            padding:0;
            background-color: #333;
            color: white;
        }
        .error{
            color: red;
        }
        .success{
            color: greenyellow;
        }
        .notification{
            color: coral;
        }
        .error,.success, .notification{
            margin: 2em 0;
            border: 2px dotted white;
            padding: 2em;
        }

        #container{
            width: 90%;
            min-width: 700px;
            margin:auto;
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
        }

        table.contacts{
            margin: 2em 0;
            width: 100%;
        }

        #formContainer{
            width: 100%;
        }
        label{
            margin-right: 2em;
            display:block;
        }

        .input{
          color:white;
        }

        .add{
          background-color: green;
        }

        .update{
          background-color: blue;
        }

        .delete{
          background-color: red;
        }
    </style>
</head>
<body>

<div id="container">



<?php

// this file holds the connection info in $host, $user, $password, $db;
include_once('connectionInfo.private.php');

// instantiate new credentials for db connection
$connectionInfo = new ConnectionInfo();

// the DBHandler takes care of all the direct database interaction.
require_once('DBHandler.php');

// instantiate new DbHandler with data from Class ConnectionInfo
$dbHandler = new DBHandler(
  $connectionInfo->host,
  $connectionInfo->user,
  $connectionInfo->password,
  $connectionInfo->db);

// now, let's see whether the user has submitted the form
if(isset($_POST['submit']) && isset($_POST['firstName']) && isset($_POST['lastName'])){
  // sanitize all input
  foreach ($_POST as $value) {
    $dbHandler->sanitizeInput($value);
  }

  // use this to check which button was clicked
  $action = $_POST['submit'];
  // required input
  $firstName = $_POST['firstName'];
  $email = $_POST['email'];
  // get input or set empty strings or -1 for zip code as default values
  $lastName = $_POST['lastName']? $_POST['lastName']:"";
  $address = $_POST['address']? $_POST['address']:"";
  $zip = $_POST['zip']? $_POST['zip']:-1;
  $city = $_POST['city']? $_POST['city']:"";

  $message = "";
  switch ($action) {
    case 'Add': // Add button was used
      if($dbHandler->insertContact($firstName,$lastName,$address,$zip,$city,$email)){
        $message = 'Successfully saved contact to the database!';
      } else {
        $message = 'Error while trying to add contact... There was a problem with given input or the contact already existed';
      }
      break;
    case 'Update': // Update button was used
      if($dbHandler->updateContact($firstName,$lastName,$address,$zip,$city,$email)){
        $message = 'Successfully updated contact in database!';
      } else {
        $message = 'Error while trying to update contact!';
      }
      break;
    case 'Delete': // Delete button was used
      if($dbHandler->deleteContact($firstName,$email)){
        $message = 'Successfully deleted contact from database!';
      } else {
        $message = 'Error while trying to delete contact!';
      }
      break;
    default:
      $message = "Something went wrong, please try again!";
      break;
  }
  echo "<div><p>$message</p></div>";
}

?>

<table class="contacts">
    <thead>
    <tr>
        <td>ID</td>
        <td>First Name</td>
        <td>Last Name</td>
        <td>Street Address</td>
        <td>Postal Code</td>
        <td>City</td>
        <td>Email</td>
    </tr>
    </thead>
    <tbody>
    <?php
    $contacts = $dbHandler->fetchContacts();
    if(count($contacts) == 0){
        echo '<tr class="notification"><td colspan="3">There are no contacts yet. You can enter details below, then choose an action.</td></tr>';
    }
    else{
        // create row with cells for each contact
        $output = "";
        foreach ($contacts as $contact){
          $output = $output . "<tr>";
          foreach($contact as $value){
            // check if zip code was empty (== -1) and clear value if that was the case
            $value = ($value == -1 || !$value > 0) ? "NO VALUE" : $value;
            $output = $output . "<td>$value</td>";
          }
          $output = $output . "</tr>";
        }
        echo $output;
    }
    ?>
    </tbody>
</table>

<div id="formContainer">
    <form method="post">
        <label>
            First Name:
            <input type="text" required name="firstName"/>
        </label>

        <label>
            Last Name:
            <input type="text" maxlength="50" name="lastName"/>
        </label>

        <label>
            Street Address:
            <input type="text" maxlength="50" name="address"/>
        </label>

        <label>
            Postal Code:
            <input type="text" maxlength="10" name="zip"/>
        </label>

        <label>
            City:
            <input type="text" maxlength="50" name="city"/>
        </label>

        <label>
            Email:
            <input type="email" maxlength="50" required name="email"/>
        </label>
        <input type="submit" name="submit" value="Add" />
        <input type="submit" name="submit" value="Update" />
        <input type="submit" name="submit" value="Delete" />
    </form>
</div>

</div>
</body>
</html>

<?php $dbHandler->closeDb(); ?>
