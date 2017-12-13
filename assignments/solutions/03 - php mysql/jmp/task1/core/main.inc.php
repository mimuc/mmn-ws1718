<?php
// check connection
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// check if connected
$db_connected = $db->connect_error != TRUE;

if (!$db_connected) {
    ?>
    <div class="alert alert-danger" role="alert">
        Couldn't connect to database. Please check the data in config.php
    </div>
    <?php
} else {
    // check if table exists
    $sql = "CREATE TABLE IF NOT EXISTS " . TABLE_NAME . "
    (
    ID BIGINT NOT NULL AUTO_INCREMENT, 
    PRIMARY KEY(ID),
    firstname VARCHAR(32),
    lastname VARCHAR(32),
    street VARCHAR(32),
    code INT(10),
    city VARCHAR(32),
    email VARCHAR(32)
)
CHARACTER SET utf8 COLLATE utf8_general_ci;";
    if ($db->query($sql) == FALSE) {
        ?>
        <div class="alert alert-danger" role="alert">
            Couldn't create table. Please check the data in config.php
        </div>
        <?php
    }

    // the table exists. Now we can continue

    include_once("./core/addressbook.inc.php");
}

// close db
$db->close();
?>