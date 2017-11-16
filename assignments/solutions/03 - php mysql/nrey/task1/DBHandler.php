<?php

class DBHandler
{
    const TABLE_ALBUMS = 'contacts';
    var $connection;

    /**
     * @param $host String host to connect to.
     * @param $user String username to use with the connection. Make sure to grant all necessary privileges.
     * @param $password String password belonging to the username.
     * @param $db String name of the database.
     */
    function __construct($host,$user,$password,$db){

        //TODO create the database connection.
        //TODO make sure the table 'albums' exists by calling ensureAlbumsTable()
      $this->connection = new mysqli($host,$user,$password,$db);

      /* check connection */
      if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit();
      }

      $this->ensureContactsTable();
    }

    /*********************
     * DESTRUCTOR
     */

    function closeDb(){
      $this->connection->close();
    }

    /**
     * creates a database record for the given contact inputs.
     * Checks if contact is already present and only adds if not.
     * @param $firstName String first name of the contact
     * @param $lastName String last name of the contact
     * @param $address String street address of the contact
     * @param $zip Integer Postal code of the contact
     * @param $city String City of the contact
     * @param $email String Email address of the contact
     * @return bool true for success, false for error
     */
    function insertContact($firstName,$lastName,$adress,$zip,$city,$email){
        // check if contact exists
        if($this->existsContact($firstName,$email)){
          return false;
        }
        // process input and do insert
        if($this->connection){
          $table = $this->getTableConstant();
          $query_insertRow = "INSERT INTO $table
          (`firstName`, `lastName`, `address`, `zip`, `city`, `email`)
          VALUES (?, ?, ?, ?, ?, ?)";
          $statement = $this->connection->prepare($query_insertRow);
          $statement->bind_param('sssiss', $firstName,$lastName,$adress,$zip,$city,$email);
          if($statement->execute()){
            return true;
          }
        }
        return false;
    }

    /**
     * Updates contact with right firstName and email with given input.
     * Doesn't update firstName and email!
     * @param $firstName String first name of the contact
     * @param $lastName String last name of the contact
     * @param $address String street address of the contact
     * @param $zip Integer Postal code of the contact
     * @param $city String City of the contact
     * @param $email String Email address of the contact
     * @return bool true for success, false for error
     */
    function updateContact($firstName,$lastName,$address,$zip,$city,$email){
        $updates = array();
        $updates[] = (strlen($lastName) > 0)? $this->updateQuery("lastName",$lastName,$firstName,$email) : true;
        $updates[] = (strlen($address) > 0)? $this->updateQuery("address",$address,$firstName,$email) : true;
        $updates[] = ($zip > 0)? $this->updateQuery("zip",$zip,$firstName,$email) : true;
        $updates[] = (strlen($city) > 0)? $this->updateQuery("city",$city,$firstName,$email) : true;
        foreach ($updates as $value) {
          if(!$value){
            return false;
          }
        }
        return true;
    }

    // performs an update query for given key and value
    function updateQuery($key, $value, $firstName, $email){
      if($this->connection){
        $table = $this->getTableConstant();
        $query_updateRow = "UPDATE $table SET $key='$value' WHERE firstName='$firstName' OR email='$email'";
        $statement = $this->connection->prepare($query_updateRow);
        if($statement->execute()){
          return true;
        }
      }
      return false;
    }

    /**
     * deletes a database record for the given contact inputs.
     * @param $firstName String first name of the contact
     * @param $email String Email address of the contact
     * @return bool true for success, false for error
     */
    function deleteContact($firstName,$email){
        if($this->connection){
          $table = $this->getTableConstant();
          $query_deleteRow = "DELETE FROM $table WHERE firstName='$firstName' AND email='$email'";
          $statement = $this->connection->prepare($query_deleteRow);
          if($statement->execute()){
            return true;
          }
        }
        return false;
    }

    /**
     * makes sure that the contacts table is present in the database
     * before any interaction occurs with it.
     */
    function ensureContactsTable(){
        if($this->connection){
            // create table if it doesn't exist.
            $table = $this->getTableConstant();
            $query_createTable = "CREATE TABLE IF NOT EXISTS $table (
              `id` INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `firstName` VARCHAR(50) NOT NULL,
              `lastName` VARCHAR(50) NOT NULL,
              `address` VARCHAR(50) NOT NULL,
              `zip` INT(10) NOT NULL,
              `city` VARCHAR(50) NOT NULL,
              `email` VARCHAR(50) NOT NULL
            )";
            // prepare the statement
            $statement = $this->connection->prepare($query_createTable);
            // execute the query
            $statement->execute();
        }
    }

    /**
     * @return array of rows (id, artist, title)
     */
    function fetchContacts(){
        $contacts = array();
        $table = $this->getTableConstant();
        $query_selectContacts = "SELECT * FROM $table";
        $statement = $this->connection->prepare($query_selectContacts);
        $statement->execute();
        $result = $statement->get_result();
        while($row = $result->fetch_assoc()){
          $contacts[] = $row;
        }
        return $contacts;
    }

    /**
     * checks if a contact is in database
     * @return boolean
     */
    function existsContact($firstName, $email){
        $contacts = array();
        $table = $this->getTableConstant();
        $query_selectContact = "SELECT * FROM $table WHERE firstName='$firstName' AND email='$email'";
        $statement = $this->connection->prepare($query_selectContact);
        $statement->execute();
        $result = $statement->get_result();
        while($result->fetch_assoc()){
          return true;
        }
        return false;
    }

    /**
     * useful to sanitize data before trying to insert it into the database.
     * @param $string String to be escaped from malicious SQL statements
     */
    function sanitizeInput(&$string){
        $string = $this->connection->real_escape_string($string);
    }

    function getTableConstant(){
      return self::TABLE_ALBUMS;
    }
}
