<?php
require_once("./core/obj/User.php");

class DBHandler
{
    private $users_table;
    private $notes_table;

    var $connection;

    /**
     * @param $host String host to connect to.
     * @param $user String username to use with the connection. Make sure to grant all necessary privileges.
     * @param $password String password belonging to the username.
     * @param $db String name of the database.
     * @param $db String name of the users table
     * @param $db String name of the notes table
     */
    function __construct($host, $user, $password, $db, $users_table, $notes_table)
    {
        $this->connection = new mysqli($host, $user, $password, $db);
        $this->users_table = $users_table;
        $this->notes_table = $notes_table;

        $this->connection->set_charset('utf8'); // prevent charset errors.
        $this->ensureTables();
    }

    /**
     * when the RAM for the script is freed,
     * we close the database connection
     */
    function __destruct()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    function insertNote($title, $text, $user_id)
    {
        if ($this->connection) {
            $queryString = "INSERT INTO ".$this->notes_table." (user_id, title, text) VALUES (?,?,?)";

            $statement = $this->connection->prepare($queryString);
            $statement->bind_param("dss", $user_id, $title, $text);
            $statement->execute();
            if ($statement->error) {
                return -1;
            } else {
                return $statement->insert_id;
            }
        }
        return -1;
    }

    function removeNote($note_id)
    {
        if ($this->connection) {
            $queryString = "DELETE FROM ".$this->notes_table." WHERE ID=?";

            $statement = $this->connection->prepare($queryString);
            $statement->bind_param("d", $note_id);
            $statement->execute();
            if ($statement->error) {
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * makes sure that the albums table is present in the database
     * before any interaction occurs with it.
     */
    function ensureTables()
    {
        if ($this->connection) {
            // check if table users exists
            $queryString = "CREATE TABLE IF NOT EXISTS " . $this->users_table . "
    (
    `ID` BIGINT NOT NULL UNIQUE AUTO_INCREMENT, 
    PRIMARY KEY(ID),
    `nickname` VARCHAR(32) NOT NULL UNIQUE,
    `password` VARCHAR(40) NOT NULL
)
CHARACTER SET utf8 COLLATE utf8_general_ci;";

            $this->connection->query($queryString);

            // check if notes table notes exists
            $queryString = "CREATE TABLE IF NOT EXISTS " . $this->notes_table . "
    (
    `ID` BIGINT NOT NULL UNIQUE AUTO_INCREMENT, 
    PRIMARY KEY(ID),
    `user_id` BIGINT NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES Users(ID) ON DELETE CASCADE,
    `title` TEXT NOT NULL,
    `text` LONGTEXT NOT NULL
)
CHARACTER SET utf8 COLLATE utf8_general_ci;";

            $this->connection->query($queryString);
        }
    }

    function authenticateUser($nickname, $password)
    {
        // convert password to hash
        $pw_hash = sha1($password);

        // compare hash with hash from DB
        if ($this->connection) {
            $queryString = "SELECT * FROM " . $this->users_table . " WHERE nickname=?";

            $statement = $this->connection->prepare($queryString);
            $statement->bind_param("s", $nickname);
            $statement->execute();
            $statement->bind_result($ID, $db_nickname, $db_password);

            $statement->fetch();
            // authenticate
            if ($db_password === $pw_hash) {
                // valid passwords
                return true;
            }
        }

        return false;
    }

    function createNewUser($nickname, $password)
    {
        //convert password to sha1 hash
        $pw_hash = sha1($password);

        if ($this->connection) {
            // because the artist name and album title come from user input, we better user prepared statements.
            $queryString = "INSERT INTO " . $this->users_table . " (nickname, password) VALUES (?,?)";

            $statement = $this->connection->prepare($queryString);
            $statement->bind_param("ss", $nickname, $pw_hash);
            $statement->execute();
            if ($statement->error) {
                return false;
            } else {
                return true;
            }
        }
        return false;
    }


    function fetchUser($nickname)
    {
        if ($this->connection) {
            $queryString = "SELECT ID, nickname FROM " . $this->users_table . " WHERE nickname=?";

            $statement = $this->connection->prepare($queryString);
            $statement->bind_param("s", $nickname);
            $statement->execute();
            $statement->bind_result($ID, $db_nickname);

            $statement->fetch();
            $statement->close();
            if (!empty($ID) && !empty($db_nickname)) {
                $notes = $this->fetchNotesByUser($ID);

                return new User($ID, $nickname, $notes);
            }
        }
        return null;
    }

    function fetchNotesByUser($user_id)
    {
        $notes = array();

        if ($this->connection) {
            $queryString = "SELECT ID, title, text FROM " . $this->notes_table . " WHERE user_id=?";

            $statement = $this->connection->prepare($queryString);
            $statement->bind_param("s", $user_id);
            $statement->execute();#

            $statement->bind_result($ID, $title, $text);

            while ($statement->fetch()) {
                $notes[] = new Note($ID, $title, $text);
            }
        }

        return $notes;
    }

    /**
     * useful to sanitize data before trying to insert it into the database.
     * @param $string String to be escaped from malicious SQL statements
     */
    function sanitizeInput(&$string)
    {
        $string = $this->connection->real_escape_string($string);
    }
}
