<?php

header('Content-Type: application/json');

$aResult = array();

if (isset($_POST['date'], $_POST['start'], $_POST['end'])) {

    //get post parameter
    $date = $_POST['date'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    include_once('/volume1/web/_class/classroom.php');
    $classroom = new classroom();
    $availabe_classrooms = $classroom->getAvailableClassrooms($date, $start, $end);

    //form options html
    $available_classrooms_html = "";
    if ($availabe_classrooms != null) {
        $available_classrooms_html .= "<option disabled selected value>Kies een lokaal</option>";
        foreach ($availabe_classrooms as $availabe_classroom) {
            $available_classrooms_html .= "<option>".$availabe_classroom."</option>";
        }
    } else {
        $available_classrooms_html = "<option disabled selected value>Geen lokalen beschikbaar op de gegeven tijden. Kies een andere tijd/datum.</option>";
    }


    //return table html
    $aResult['classroomsHtml'] = $available_classrooms_html;

}


echo json_encode($aResult);

?>