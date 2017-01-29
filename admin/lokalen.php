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

                    <div class="row">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Lokaalnummer</th>
                                <th>Laatste beweging</th>
                                <th>Lokaalrooster</th>
                                <th>Temperatuur</th>
                                <th>Acties</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $classes = ['B3206', 'B3208', 'B3210', 'B3212', 'B3214', 'B3216'];
                            foreach ($classes as $class) {
                                echo "
                                    <tr id='classroom_row_$class'>
                                      <th scope='row'>
                                        $class
                                        <span class='label label-warning'>Status onbekend</span>
                                      </th>
                                      <td><i class='fa fa-spinner fa-spin' aria-hidden='true'></i></td>
                                      <td><i class='fa fa-spinner fa-spin' aria-hidden='true'></i></td>
                                      <td><i class='fa fa-spinner fa-spin' aria-hidden='true'></i></td>
                                      <td><a href='/admin/reservering.php' type='button' class='btn btn-danger disabled'>Lokaal verbergen</a></td>
                                    </tr>
                                    ";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>