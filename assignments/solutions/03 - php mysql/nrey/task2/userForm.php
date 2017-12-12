<?php
// creates and renders a form for user login or regestration dependen on the
// passed parameter $type
function userForm($type) {
  $confirm = "";
  $link = "";
  $button = "";

  // check the type and set optional DOM elements
  switch ($type) {
    case 'login':
      $link = "<h3><a href='register.php'>New users register here</a></h3>";
      $button = "<input class='button-hover' type='submit' name='submit' value='Login'>";
      break;
    case 'register':
      $confirm = "<input class='user-form' type='password' name='confirm' placeholder='Confirm password'>";
      $link = "<h3><a href='login.php'>Registered users login here</a></h3>";
      $button = "<input class='button-hover' type='submit' name='submit' value='Register'>";
      break;
    default:
      # do nothing
      break;
  }

  // check if user is logged in and output a corresponding message and link to dashboard
  if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']){
    $output = <<<EOT
      <div class="card dashboard-link">
      <h4>Your are already logged in!</h4>
      <h2><a href="dashboard.php">Check and edit your notes</a></h2>
      </div>
EOT;
  }
  // if user isn't logged in provide the necessary form
  else {
    $output = <<<EOT
    <form method="post">
    <input class="user-form" type="text" name="user" placeholder="Enter user"/>
    <input class="user-form" type="password" name="password" placeholder="Enter password"/>
    $confirm
    $button
    </form>
    $link
EOT;
  }
  echo $output;
}
?>
