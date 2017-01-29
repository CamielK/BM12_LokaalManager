<?php

    //validates if user has a valid connection. redirect to login page if no connection

    //get session
    session_start();
    $user_check=$_SESSION['login_user'];

    //if not logged in redirect to login page
    if(!isset($user_check)){
        header('Location: /admin/login.php');
    }

    //check permissions, redirect if not allowed
    if(isset($min_auth_level)) {
        switch ($min_auth_level) {
            case 'DEFAULT':
                header('Location: /admin/logout.php');
                break;
            case 'STUDENT':
                $allowed_levels = array(1,2,3);
                if (!in_array($_SESSION['user_group'], $allowed_levels)) {
                    header('Location: /admin/reservering.php');
                }
            case 'TEACHER':
                $allowed_levels = array(1,3);
                if (!in_array($_SESSION['user_group'], $allowed_levels)) {
                    header('Location: /admin/reservering.php');
                }
            case 'ADMIN':
                $allowed_levels = array(1);
                if (!in_array($_SESSION['user_group'], $allowed_levels)) {
                    header('Location: /admin/reservering.php');
                }
        }
    }
?>