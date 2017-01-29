<?php

header('Content-Type: application/json');

$aResult = array();

if (isset($_POST['classroom_name'])) {

    //get post parameter
    $classroom_name = $_POST['classroom_name'];
    
    include_once('/volume1/web/_class/movement.php');
    $movement = new movement();
    $last_movement = $movement->getLastMovement($classroom_name);
    
    include_once('/volume1/web/_class/schedule.php');
    $schedule = new schedule();
    $schedule_array = $schedule->getTodaysSchedule($classroom_name);
    if (strpos(json_encode($schedule_array), 'No scheduled events found for today') === false) {
        $schedule = '';
        
        
        foreach($schedule_array as $event) {
            $event_start = strtotime($event['start_tijd']);
            $event_end = strtotime($event['eind_tijd']);
            
            if ($event_start < time() && $event_end > time()) {
                //event is ongoing
                $schedule = 'Momenteel gereserveerd tot ' . date('H:i:s', $event_end);
                $schedule .= ' <span type="button" class="btn btn-link" data-toggle="modal" data-target="#schedule_modal_'.$classroom_name.'">Meer..</span>';
                break;
            } else if ($event_start > time()) {
                //event is coming up next
                $schedule = 'Gereserveerd van ' . date('H:i:s', $event_start) . ' tot ' . date('H:i:s', $event_end);
                $schedule .= ' <span type="button" class="btn btn-link" data-toggle="modal" data-target="#schedule_modal_'.$classroom_name.'">Meer..</span>';
                break;
            } else {
                $schedule = 'Geen reserveringen meer vandaag';
            }
            
        }
        
        
        $schedule_table_html = '';
        foreach($schedule_array as $event) {
            $event_start = strtotime($event['start_tijd']);
            $event_end = strtotime($event['eind_tijd']);
            
            $schedule_table_html .= "
                <tr>
                  <td>".date('H:i:s', $event_start)."</td>
                  <td>".date('H:i:s', $event_end)."</td>
                  <td>".$event['beschrijving']."</td>
                </tr>
                ";
        }
        
        $schedule_modal = '
            <div id="schedule_modal_'.$classroom_name.'" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lesrooster voor '.$classroom_name.' op '.date('j-n-Y').'</h4>
                  </div>
                  <div class="modal-body">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Begin tijd</th>
                          <th>Eind tijd</th>
                          <th>Beschrijving</th>
                        </tr>
                      </thead>
                      <tbody>
                        '.$schedule_table_html.'
                      </tbody>
                    </table>
                    </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>

              </div>
            </div>
        ';
        
    } else {
        $schedule = 'Geen reserveringen meer vandaag';
    }
    
    
    include_once('/volume1/web/_class/temperature.php');
    $temperature = new temperature();
    $last_temperature = $temperature->getTemperature($classroom_name);
	
	//form table
	$rowHtml = "
        <th scope='row'>
            ".$classroom_name."
            ". ((strtotime($last_movement) < strtotime('-30 minutes')) && ($last_movement !== '-') && (strpos($schedule, 'Momenteel') === false) ? 
               '<span class="label label-success">Beschikbaar</span>' : 
               ($last_movement !== '-' ?
               '<span class="label label-danger">In gebruik</span>' :
               '<span class="label label-warning">Status onbekend</span>'
               )) ."
        </th>
        <td>
            ".$last_movement."
        </td>
        <td>
            ". $schedule ."
        </td>
        <td>
            ". ($last_temperature !== '-' ? 
               $last_temperature : 
               '<span class="label label-default">Onbekend</span>') ."
        </td>
        <td>
            <a href='/admin/reservering.php' type='button' class='btn btn-info'>Reserveren</a>
        </td>";

	//return table html
	$aResult['rowHtml'] = $rowHtml;
	$aResult['modalHtml'] = $schedule_modal;

}


echo json_encode($aResult);
  
?>