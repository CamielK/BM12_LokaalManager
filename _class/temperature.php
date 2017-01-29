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
  
  public function getTemperature($classroom) {
  	
    //database connection
    include_once('dbConnection.php');
    $db = new databaseConnection();
    
    //escape request input to prevent SQL injections
    $classroom = mysqli_real_escape_string($db->getConnection(), $classroom);
      
    //insert new row into movement table
    $query = "
          SELECT Temperatuur.temp, Lokaal.lokaalnummer
          FROM `lokaal_manager`.`Temperatuur`
          JOIN `lokaal_manager`.`Lokaal` ON Temperatuur.lokaal_id = Lokaal.id
          WHERE Lokaal.lokaalnummer LIKE '%".$classroom."%'
          AND Temperatuur.tijd > DATE_SUB(NOW(), INTERVAL 2 HOUR)
          ORDER BY Temperatuur.tijd DESC
          LIMIT 1
          ;";
    
    //execute
    $result = $db->queryDatabase($query);

    //check result
    if ($result->num_rows > 0) {
    	$response['last_temperature'] = $result->fetch_assoc();
    } else {
        $response['error'] = 'No temperature records for room: '.$classroom.'.';
        $response['last_temperature']['temp'] = '-';
    }
    
    //close connection
    $db->closeConnection();

    //return result
    return $response['last_temperature']['temp'];
  	
  }
    
}
	
?>