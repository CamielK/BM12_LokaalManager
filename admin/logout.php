<?php

    //close session and redirects to login page

    session_start();

    if(session_destroy()) {
        header("Location: login.php");
    }
?>