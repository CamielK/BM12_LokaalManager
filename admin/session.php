<?php

    //validates if user has a valid connection. redirect to login page if no connection

    //get session
    session_start();
    $user_check=$_SESSION['login_user'];

    //if not logged in redirect to login page
    if(!isset($user_check)){
        header('Location: /admin/login.php');
    }

?>