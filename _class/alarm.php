<?php

class alarm {

    //private vars

    //constructor
    public function __construct() {
    }

    public function addAlarmRecord($class_number, $info, $priority, $timeout) {

        //database connection
        include_once('dbConnection.php');
        $db = new databaseConnection();

        //escape request input to prevent SQL injections
        $classroom = mysqli_real_escape_string($db->getConnection(), $class_number);
        $info = mysqli_real_escape_string($db->getConnection(), $info);
        $priority = mysqli_real_escape_string($db->getConnection(), $priority);
        $timeout = mysqli_real_escape_string($db->getConnection(), $timeout);

        //insert new row into alarm table
        $query = "
        INSERT INTO `lokaal_manager`.`Alarm` (`id`, `omschrijving`, `prioriteit`, `start_tijd`, `timeout`, `lokaal_id`)
        VALUES (
          NULL,
          '$info',
          '$priority',
          CURRENT_TIMESTAMP,
          '$timeout',
          (
          SELECT id
          FROM `lokaal_manager`.`Lokaal`
          WHERE Lokaal.lokaalnummer LIKE '%".$classroom."%'
          )
        );";

        //execute
        $result = $db->queryDatabase($query);

        //check result
        if ($result) {
            $response['success'] = 'Alarm was added.';

        } else {
            $response['error'] = 'Error adding alarm.';
        }

        //close connection
        $db->closeConnection();

        //return result
        return json_encode($response);

    }

    public function getAlarm($classroom) {

        //database connection
        include_once('dbConnection.php');
        $db = new databaseConnection();

        //escape request input to prevent SQL injections
        $classroom = mysqli_real_escape_string($db->getConnection(), $classroom);

        //insert new row into movement table
        $query = "
          SELECT Alarm.*, Lokaal.lokaalnummer
          FROM `lokaal_manager`.`Alarm`
          JOIN `lokaal_manager`.`Lokaal` ON Alarm.lokaal_id = Lokaal.id
          WHERE Lokaal.lokaalnummer LIKE '%".$classroom."%'
          ORDER BY Alarm.start_tijd DESC
          LIMIT 1
          ;";

        //execute
        $result = $db->queryDatabase($query);

        //check result
        $response = array();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $timeout_time = new DateTime($row['start_tijd']);
            $timeout_time->modify("+" . $row['timeout'] . " seconds");
            if ($timeout_time > new DateTime('now')) {
                $response[] = array(
                    'start_tijd' => $row['start_tijd'],
                    'omschrijving' => $row['omschrijving'],
                    'timeout' => $row['timeout'],
                    'lokaalnummer' => $row['lokaalnummer']
                );
            } else {
                $response[] = 'No alarm records for room: '.$classroom.'.';
            }
        } else {
            $response[] = 'No alarm records for room: '.$classroom.'.';
        }

        //close connection
        $db->closeConnection();

        //return result
        return $response;

    }

}

?>