<?php

class user {

    //private vars

    //constructor
    public function __construct() {
    }


    function addUser($name, $mail, $pass, $group) {

        //database connection
        include_once('dbConnection.php');
        $db = new databaseConnection();

        //escape request input to prevent SQL injections
        $name = mysqli_real_escape_string($db->getConnection(), $name);
        $mail = mysqli_real_escape_string($db->getConnection(), $mail);
        $pass = mysqli_real_escape_string($db->getConnection(), $pass);
        $group = mysqli_real_escape_string($db->getConnection(), $group);

        //insert new row into schedule table
        $query = "
        INSERT INTO `User` (`id`, `name`, `mail`, `pass_hash`, `date_created`, `user_group`) 
        VALUES 
          (NULL, 
          '$name', 
          '$mail', 
          '" . $this->create_hash($pass, '') . "', 
          '" . date('Y-m-d') . "', 
          ( 
              SELECT id 
              FROM `lokaal_manager`.`User_group` 
              WHERE User_group.rol LIKE '%".$group."%' 
          )
          );
        ";

        //execute
        $result = $db->queryDatabase($query);

        //check query results
        if ($result) {
            return 200; //user added
        } else {
            return 404; //error adding user
        }

    }

    //SHA512 hashing algorithm with stretching.
    private function create_hash($password, $salt) {
        $hash = '';
        for ($i = 0; $i < 20000; $i++) {
            $hash = hash('sha512', $hash . $salt . $password);
        }
        return $hash;
    }


}



?>