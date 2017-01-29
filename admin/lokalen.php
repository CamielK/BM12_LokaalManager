<?php
//check if session is valid
$min_auth_level = 'TEACHER';
include('session.php');
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>

    <?php include('../templates/head.php'); ?>
    <title>Lokalen beheren</title>
    <link rel="stylesheet" href="/css/admin.css">

</head>
<body>

<div id="wrapper">



    <div id="row">

        <div id="sidebar" class="col-sm-3 col-md-2 sidebar">
            <?php $page = 'lok'; include('../templates/adminSidebar.php'); ?>
        </div>

        <div id="content" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="row">
                <div>
                    <h1 class="text-center login-title">Lokalen beheren</h1></br>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>