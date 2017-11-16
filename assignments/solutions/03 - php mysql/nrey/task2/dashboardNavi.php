<?php
  // creates and renders the top navigation based on existing session state
  // $message is passed by parent element
  function dashboardNavi($message){
    $userContent = "";
    $logoutContent = "";
    if(isset($_SESSION['loggedIn']) && isset($_SESSION['userName'])){
      $user = $_SESSION['userName'];
      $userContent = "Hello $user!";
      $logoutContent = "<a class='logout button-hover' href='logout.php'>Logout</a>";
    }

    $output = <<<EOD
      <div class='container top'>
      <div class='brand'>Note App</div>
      <div>$message</div>
      <div class='float-right'>$logoutContent</div>
      <div class='float-right greeting'>$userContent</div>
      </div>
EOD;
    echo $output;
}

?>
