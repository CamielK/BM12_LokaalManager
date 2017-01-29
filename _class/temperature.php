<?php
	
class temperature {
	
  //private vars
  
  //constructor
  public function __construct() {
  }
  
  public function addTemperatureRecord($temp, $classroom) {
  	
    //database connection
    include_once('dbConnection.php');
    $db = new databaseConnection();
    
    //escape request input to prevent SQL injections
    $temp = mysqli_real_escape_string($db->getConnection(), $temp);
    $classroom = mysqli_real_escape_string($db->getConnection(), $classroom);
      
    //insert new row into movement table
    $query = "
        INSERT INTO `lokaal_manager`.`Temperatuur` (`id`, `lokaal_id`, `temp`) 
        VALUES (NULL, ( 
          SELECT id 
          FROM `lokaal_manager`.`Lokaal` 
          WHERE Lokaal.lokaalnummer LIKE '%".$classroom."%' 
          ), '".$temp."'
        );";
    
    //execute
    $result = $db->queryDatabase($query);

    //check result
    if ($result) {
      $response['success'] = 'New Temperature record was added.';
    } else {
      $response['error'] = 'Error adding temperature record.';
    }
    
    //close connection
    $db->closeConnection();

    //return result
    return json_encode($response);
  	
  }
    
}
	
?>