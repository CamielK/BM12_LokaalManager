<?php

    //*** add default json response headers
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json');

    //*** filter request url parameters
    $method = $_SERVER['REQUEST_METHOD'];
    $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
    $input = json_decode(file_get_contents('php://input'),true);

    error_log("api call received. Method: $method. Url: ".$_SERVER['PATH_INFO']." ",0);

    //*** execute API function depending on main argument
    $mainArg = array_shift($request);
    
    
    
    // record movement
    if ($mainArg==='movement') {
        
        //get searchstring parameter
        $class_number = array_shift($request);
        
        include_once('/volume1/web/_class/movement.php');
        $movement = new movement();
        
        //return search output
        echo $result = $movement->addMovement($class_number);
        
    } else if ($mainArg==='temp') {
        
        //get searchstring parameter
        $class_number = array_shift($request);
        $temperature = array_shift($request);
        
        include_once('/volume1/web/_class/temperature.php');
        $temp = new temperature();
        
        //return search output
        echo $result = $temp->addTemperatureRecord($temperature, $class_number);
        
    } else if ($mainArg==='test') {
        $arr = array('A' => 1, 'B' => 2, 'C' => 3, 'Response' => true);
        echo json_encode($arr);
    } else {
        incorrectArgs('Incorrect API arguments. Check API documentation for a list of valid arguments');
    }


    function incorrectArgs($errorMsg) {
        $arr = array('Error' => $errorMsg, 'Response' => false);
        echo json_encode($arr);
    }


?>