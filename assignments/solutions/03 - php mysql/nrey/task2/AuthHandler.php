<?php

class AuthHandler
{
    const TABLE_USERS = 'users';
    var $connection;

    /**
     * @param $host String host to connect to.
     * @param $user String username to use with the connection. Make sure to grant all necessary privileges.
     * @param $password String password belonging to the username.
     * @param $db String name of the database.
     */
    function __construct($host,$user,$password,$db){

      $this->connection = new mysqli($host,$user,$password,$db);

      /* check connection */
      if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit();
      }

      $this->ensureUsersTable();
    }

    /*********************
     * DESTRUCTOR
     */

     function closeDb(){
       $this->connection->close();
     }

    /**
     * Creates a database record for the given title and note.
     * Checks first if a username already exists.
     * @param $title String title of a note
     * @param $note String note message
     * @return bool true for success, false for error
     */
    function insertUser($user,$password){
        if($this->connection && !$this->existsUser($user)){
          $password = password_hash($password, PASSWORD_DEFAULT);
          $table = $this->getTableConstant();
          $query_insertRow = "INSERT INTO $table
          (`user`, `password`) VALUES (?, ?)";
          $statement = $this->connection->prepare($query_insertRow);
          $statement->bind_param('ss', $user, $password);
          if($statement->execute()){
            return true;
          }
        }
        return false;
    }

    /**
     * makes sure that the notes table is present in the database
     * before any interaction occurs with it.
     */
    function ensureUsersTable(){
      if($this->connection){
        // create table if it doesn't exist.
        $table = $this->getTableConstant();
        $query_createTable = "CREATE TABLE IF NOT EXISTS $table (
          `id` INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT,
          `user` VARCHAR(20) NOT NULL,
          `password` VARCHAR(200) NOT NULL
        )";
        // prepare the statement
        $statement = $this->connection->prepare($query_createTable);
        // execute the query
        $statement->execute();
      }
    }

    /**
     * Checks if a user that wants to login has entered the right credentials.
     * @return boolean
     */
    function loginUser($user,$password){
      if($this->connection){
        $table = $this->getTableConstant();
        $query_selectUser = "SELECT * FROM $table WHERE user=?";
        $statement = $this->connection->prepare($query_selectUser);
        $statement->bind_param('s', $user);
        $statement->execute();
        $result = $statement->get_result();
        while($row = $result->fetch_assoc()){
          $hash = $row['password'];
          $userID = $row['id'];
          if(password_verify($password, $hash)){
              $_SESSION['loggedIn'] = true;
              $_SESSION['userName'] = $user;
              $_SESSION['userID'] = $userID;
              return true;
          }
        }
      }
      return false;
    }

    /**
     * checks if a user is in database
     * @return boolean
     */
    function existsUser($user){
        $users = array();
        $table = $this->getTableConstant();
        $query_selectUser = "SELECT * FROM $table WHERE user=?";
        $statement = $this->connection->prepare($query_selectUser);
        $statement->bind_param('s', $user);
        $statement->execute();
        $result = $statement->get_result();
        while($result->fetch_assoc()){
          return true;
        }
        return false;
    }

    // used to check if user that wants to register has entered matching passwords
    function confirmPassword($password, $confirmPassword){
      return $password == $confirmPassword;
    }

    /**
     * useful to sanitize data before trying to insert it into the database.
     * @param $string String to be escaped from malicious SQL statements
     */
    function sanitizeInput(&$string){
        $string = $this->connection->real_escape_string($string);
    }

    function getTableConstant(){
      return self::TABLE_USERS;
    }
}
