<?php
	
class classroom {
	
  //private vars
  
	//constructor
  public function __construct() {
  }

  public function getAvailableClassrooms($date, $start, $end) {

      if (strtotime($start) >= strtotime($end)) {
          return null;
      } else {
          //database connection
          include_once('dbConnection.php');
          $db = new databaseConnection();

          //escape request input to prevent SQL injections
          $date = mysqli_real_escape_string($db->getConnection(), $date);
          $start = mysqli_real_escape_string($db->getConnection(), $start);
          $end = mysqli_real_escape_string($db->getConnection(), $end);

          $query = "
            SELECT Lokaal.lokaalnummer
            FROM Lokaal
            WHERE Lokaal.id NOT IN (
              SELECT Lokaalrooster.lokaal_id
              FROM Lokaalrooster
              WHERE ('$date $start' >= Lokaalrooster.start_tijd AND '$date $start' < Lokaalrooster.eind_tijd)
              OR ('$date $end' > Lokaalrooster.start_tijd AND '$date $end' <= Lokaalrooster.eind_tijd)
              OR (Lokaalrooster.start_tijd > '$date $start' AND Lokaalrooster.start_tijd < '$date $end')
              OR (Lokaalrooster.eind_tijd > '$date $start' AND Lokaalrooster.eind_tijd < '$date $end')
            )
            ;";

          //execute
          $result = $db->queryDatabase($query);

          //check result
          $response = array();
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  $response[] = $row['lokaalnummer'];
              }
          } else {
              return null;
          }

          //close connection
          $db->closeConnection();

          //return result
          return $response;
      }

  }
	
}
	
?>