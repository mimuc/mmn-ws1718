<?php

class DBHandler
{
    const TABLE_NOTES = 'notes';
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

      $this->ensureNotesTable();
    }

    /*********************
     * DESTRUCTOR
     */

     function closeDb(){
       $this->connection->close();
     }

    /**
     * creates a database record for the given title and note.
     * @param $title String title of a note
     * @param $note String note message
     * @return bool true for success, false for error
     */
    function insertNote($userID, $title,$note,$user){
      if($this->connection){
        $table = $this->getTableConstant();
        $query_insertRow = "INSERT INTO $table
          (`userID`, `title`, `note`, `user`) VALUES (?, ?, ?, ?)";
        $statement = $this->connection->prepare($query_insertRow);
        $statement->bind_param('isss', $userID, $title, $note, $user);
        if($statement->execute()){
          return true;
        }
      }
      return false;
    }

    /**
     * Perfoms update for a given note.
     * @param $title String title of a note
     * @param $note String note message
     * @param $id String id of a note
     * @return bool true for success, false for error
     */
    function updateNote($id,$title,$note){
        $updates = array();
        $updates[] = (strlen($title) > 0)? $this->updateQuery($id,"title",$title) : true;
        $updates[] = (strlen($note) > 0)? $this->updateQuery($id,"note",$note) : true;
        foreach ($updates as $value) {
          if(!$value){
            return false;
          }
        }
        return true;
    }

    // performs an update query for given key and value
    function updateQuery($id, $key, $value){
      if($this->connection){
        $table = $this->getTableConstant();
        $query_updateRow = "UPDATE $table SET $key=? WHERE id=?";
        $statement = $this->connection->prepare($query_updateRow);
        $statement->bind_param('ss', $value, $id);
        if($statement->execute()){
          return true;
        }
      }
      return false;
    }

    /**
     * deletes database records for the given note ids.
     * @param $ids String of notes to delete
     * @return bool true for success, false for error
     */
    function deleteNotes($ids){
      foreach ($ids as $id) {
        if(!$this->deleteNote($id)){
          return false;
        }
      }
      return true;
    }

    /**
     * Delete a note from the database.
     * @param $id String Id of a note
     * @return bool true for success, false for error
    */
    function deleteNote($id){
      if($this->connection){
        $table = $this->getTableConstant();
        $query_deleteRow = "DELETE FROM $table WHERE id=?";
        $statement = $this->connection->prepare($query_deleteRow);
        $statement->bind_param('s', $id);
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
    function ensureNotesTable(){
      if($this->connection){
        // create table if it doesn't exist.
        $table = $this->getTableConstant();
        $query_createTable = "CREATE TABLE IF NOT EXISTS $table (
          `id` INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT,
          `userID` INT(5) NOT NULL,
          `title` VARCHAR(50) NOT NULL,
          `note` VARCHAR(300) NOT NULL,
          `user` VARCHAR(50) NOT NULL,
          FOREIGN KEY (userID)
            REFERENCES users(id)
            ON DELETE CASCADE
        )";
        // prepare the statement
        $statement = $this->connection->prepare($query_createTable);
        // execute the query
        $statement->execute();
      }
    }

    /**
     * Gets notes for a given user from the database.
     * @param $user String user name of a logged in user
     * @return array of rows (id, title, note, user)
     */
    function fetchNotes($user){
      $notes = array();
      $table = $this->getTableConstant();
      $query_selectNotes = "SELECT id, title, note FROM $table WHERE user=?";
      $statement = $this->connection->prepare($query_selectNotes);
      $statement->bind_param('s', $user);
      $statement->execute();
      $result = $statement->get_result();
      while($row = $result->fetch_assoc()){
        $notes[] = $row;
      }
      return $notes;
    }

    /**
     * useful to sanitize data before trying to insert it into the database.
     * @param $string String to be escaped from malicious SQL statements
     */
    function sanitizeInput(&$string){
      $string = $this->connection->real_escape_string($string);
    }

    function getTableConstant(){
      return self::TABLE_NOTES;
    }
}
