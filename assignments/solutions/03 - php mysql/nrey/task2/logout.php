<?php
// this is used to logout and completely remove all data that is connected to the session
// redirects to login page after killing the session
session_start();
unset($_SESSION);
session_destroy();
session_write_close();
header('Location: ./login.php');
die;
?>
