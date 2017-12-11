<?php
  // wrapper for elements and imports needed for all pages
  include_once('top.php');

  $connectionInfo = new ConnectionInfo();

  // the authHandler takes care of all the direct database interaction.

  $authHandler = new AuthHandler(
  $connectionInfo->host,
  $connectionInfo->user,
  $connectionInfo->password,
  $connectionInfo->db);

  // now, let's see whether the user has submitted the form
  $message = "";
  if(isset($_POST['submit']) && isset($_POST['user']) && isset($_POST['password']) && !isset($_SESSION['loggedIn'])){

    // sanitize all input
    foreach ($_POST as $value) {
      $authHandler->sanitizeInput($value);
    }

    // use this to check which button was clicked
    $action = $_POST['submit'];
    $user = $_POST['user'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // switch over action to trigger behavior that user wanted
    switch ($action) {
      case 'Register': // Login form was used
        if($authHandler->confirmPassword($password, $confirm)){
          if($authHandler->insertUser($user, $password)){
            $message = "<div class='hint'>Successfully registered! <a href='login.php'>Login</a></div>";
          } else {
            $message = "<div class='hint error'>Error while trying to register!</div>";
          }
        } else {
            $message = "<div class='hint error'>Passwords don't match!</div>";
        }
        break;
      default:
        $message = "<div class='hint error'>Something went wrong... Please try again</div>";
        break;
    }
  }
  // create and include top navigation
  dashboardNavi($message);
?>

<div id="container">

<?php
  // create and include input form for logging in / new user
  userForm("register");
?>

<?php include_once('bottom.php');?>

<?php
  // always closes db connection after the page has been rendered
  $authHandler->closeDb();
  ?>
