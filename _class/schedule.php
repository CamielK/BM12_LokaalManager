<?php
	
class schedule {
	
  //private vars
  
  //constructor
  public function __construct() {
  }
  
  public function getTodaysSchedule($classroom) {
  	
    //database connection
    include_once('dbConnection.php');
    $db = new databaseConnection();
    
    //escape request input to prevent SQL injections
    $classroom = mysqli_real_escape_string($db->getConnection(), $classroom);
    
    //insert new row into movement table
    $query = "
        SELECT Lokaalrooster.*
        FROM Lokaalrooster
        JOIN `lokaal_manager`.`Lokaal` ON Lokaalrooster.lokaal_id = Lokaal.id
        WHERE Lokaal.lokaalnummer LIKE '%".$classroom."%'
        AND DAY(Lokaalrooster.start_tijd) = DAY(NOW())
        ORDER BY Lokaalrooster.start_tijd ASC
        ;";
    
    //execute
    $result = $db->queryDatabase($query);

    //check result
    $response = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = array(
                'start_tijd' => $row['start_tijd'], 
                'eind_tijd' => $row['eind_tijd'], 
                'beschrijving' => $row['beschrijving']);
        }
    } else {
      $response['schedule'] = 'No scheduled events found for today.';
    }
    
    //close connection
    $db->closeConnection();

    //return result
    return $response;
  	
  }
    
    
}
	
?>