<?php

if (isset($_SESSION) && !empty($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true) {
    // user is logged in
    include_once("./core/memberarea.inc.php");
} else {
    if (empty($_GET["register"])) {
        include_once("./core/login.inc.php");
    } else {
        include_once("./core/register.inc.php");
    }
}
?>