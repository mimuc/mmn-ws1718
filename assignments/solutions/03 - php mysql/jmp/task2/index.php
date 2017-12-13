<?php
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

include_once("config.php");

require("./core/obj/DBHandler.php");
global $dbhandler;
global $user;

$dbhandler = new DBHandler(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, USERS_TABLE, NOTES_TABLE);

if (!empty($_SESSION["logged_in"]) && !empty($_SESSION["nickname"])) {
    // get user data from DB
    $user = $dbhandler->fetchUser($_SESSION["nickname"]);
}

if (!empty($_POST)) {
    if (!empty($_POST["login"])) {
        // login process

        if ($dbhandler->authenticateUser($_POST["nickname"], $_POST["password"]) == true) {
            $_SESSION["logged_in"] = true;
            $_SESSION["nickname"] = $_POST["nickname"];
            $user = $dbhandler->fetchUser($_SESSION["nickname"]);
        } else {
            $_SESSION["logged_in"] = false;
        }
    } else if (!empty($_POST["logout"])) {
        session_destroy();
        $_SESSION = array();

    }

    if (!empty($_POST["register"])) {
        // register new user
        $nickname = trim($_POST["nickname"]);

        if ($nickname !== "" && $_POST["password"] === $_POST["retry_password"]) {

            // valid credentials
            if (empty($dbhandler->fetchUser($nickname))) {
                // user does not exist
                $_SESSION["logged_in"] = $dbhandler->createNewUser($nickname, $_POST["password"]);
                $_SESSION["nickname"] = $nickname;

                header('Location: index.php');
                exit();
            }
        }
    } else if (!empty($_POST["add_note"])) {
        //add note process
        if (isset($_POST["title"]) && isset($_POST["text"]) && $_POST["title"] != "" && $_POST["text"] != "") {
            //add new Note
            $id = $dbhandler->insertNote($_POST["title"], $_POST["text"], $user->getId());

            // update user object
            $user->addNote($id, $_POST["title"], $_POST["text"]);
        }
    } else if (!empty($_POST["delete_notes"])) {
        //add note process
        if (!empty($_POST["note"])) {
            foreach ($_POST["note"] as $note) {
                if ($dbhandler->removeNote($note)) {
                    // note deleted. Remove object
                    $user->removeNote($note);
                }
            }
        }
    }
}

include_once("./core/header.inc.php");
include_once("./core/main.inc.php");
include_once("./core/footer.inc.php");
?>
