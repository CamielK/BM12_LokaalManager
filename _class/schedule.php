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

  function addScheduleRecord($date, $start, $end, $classroom, $info, $username) {

      //database connection
      include_once('dbConnection.php');
      $db = new databaseConnection();

      //escape request input to prevent SQL injections
      $date = mysqli_real_escape_string($db->getConnection(), $date);
      $start = mysqli_real_escape_string($db->getConnection(), $start);
      $end = mysqli_real_escape_string($db->getConnection(), $end);
      $classroom = mysqli_real_escape_string($db->getConnection(), $classroom);
      $info = mysqli_real_escape_string($db->getConnection(), $info);

      //insert new row into schedule table
      $query = "
        INSERT INTO `Lokaalrooster` (`id`, `lokaal_id`, `start_tijd`, `eind_tijd`, `beschrijving`, `user_id`) 
        VALUES 
          (NULL, 
          ( 
              SELECT id 
              FROM `lokaal_manager`.`Lokaal` 
              WHERE Lokaal.lokaalnummer LIKE '%".$classroom."%' 
          ), 
          '$date $start', 
          '$date $end', 
          '$info', 
          ( 
              SELECT id 
              FROM `lokaal_manager`.`User` 
              WHERE User.name LIKE '%".$username."%' 
          )
          );
        ";

      //execute
      $result = $db->queryDatabase($query);

      //check query results
      if ($result) {
          return 200; //schedule record added
      } else {
          return 404; //error adding schedule record
      }

  }
}
	
?>