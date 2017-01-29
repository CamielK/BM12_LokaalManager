<?php
	
class movement {
	
  //private vars
  
  //constructor
  public function __construct() {
  }
  
  public function addMovement($classroom) {
  	
    //database connection
    include_once('dbConnection.php');
    $db = new databaseConnection();
    
    //escape request input to prevent SQL injections
    $classroom = mysqli_real_escape_string($db->getConnection(), $classroom);
    
    //insert new row into movement table
    $query = "
        INSERT INTO `lokaal_manager`.`Beweging` (`id`, `lokaal_id`) 
        VALUES (NULL, ( 
          SELECT id 
          FROM `lokaal_manager`.`Lokaal` 
          WHERE Lokaal.lokaalnummer LIKE '%".$classroom."%' 
          )
        );";
    
    //execute
    $result = $db->queryDatabase($query);

    //check result
    if ($result) {
    	$response['success'] = 'Movement was added.';

    } else {
      $response['error'] = 'Error adding movement.';
    }
    
    //close connection
    $db->closeConnection();

    //return result
    return json_encode($response);
  	
  }
    
  public function getLastMovement($classroom) {
  	
    //database connection
    include_once('dbConnection.php');
    $db = new databaseConnection();
    
    //escape request input to prevent SQL injections
    $classroom = mysqli_real_escape_string($db->getConnection(), $classroom);
    
    //insert new row into movement table
    $query = "
          SELECT Beweging.tijd, Lokaal.lokaalnummer
          FROM `lokaal_manager`.`Beweging`
          JOIN `lokaal_manager`.`Lokaal` ON Beweging.lokaal_id = Lokaal.id
          WHERE Lokaal.lokaalnummer LIKE '%".$classroom."%'
          ORDER BY Beweging.tijd DESC
          LIMIT 1
          ;";
    
    //execute
    $result = $db->queryDatabase($query);

    //check result
    if ($result->num_rows > 0) {
    	$response['last_movement'] = $result->fetch_assoc();
    } else {
        $response['error'] = 'No movement records for room: '.$classroom.'.';
        $response['last_movement']['tijd'] = '-';
    }
    
    //close connection
    $db->closeConnection();

    //return result
    return $response['last_movement']['tijd'];
  	
  }
    
}
	
?>