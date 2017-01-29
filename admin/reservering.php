<?php
    //check if session is valid
    $min_auth_level = 'STUDENT';
    include('session.php');
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
    <head>
        
        <?php include('../templates/head.php'); ?>
        <title>Reserveringen</title>
        <link rel="stylesheet" href="/css/admin.css">
        
    </head>
    <body>

        <div id="wrapper">



            <div id="row">

                <div id="sidebar" class="col-sm-3 col-md-2 sidebar">
                    <?php $page = 'res'; include('../templates/adminSidebar.php'); ?>
                </div>

                <div id="content" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <div class="row">
                        <div>
                            <h1 class="text-center login-title">Plaats reservering</h1></br>
                            <div class="col-lg-2 col-lg-offset-3 text-right">
                                <p>start tijd:</p>
                                <p>eind tijd:</p>
                                <p>lokaal:</p>
                                <p>beschrijving:</p>
                            </div>

                            <div class="col-lg-4">

                                <div class="account-wall">
                                    <form class="form-reservering" name="form_reservering" method="post" action="reservering.php" role="form">

                                        <input type="time" class="form-control" id="start_tijd" name="start_tijd" placeholder="start tijd" value="">
                                        <input type="time" class="form-control" id="eind_tijd" name="eind_tijd" placeholder="eind_tijd" value="">
                                        <input type="dropdown" class="form-control" id="lokaal" name="lokaal" placeholder="lokaal" value="">
                                        <input type="text" class="form-control" id="beschrijving" name="beschrijving" placeholder="beschrijving" value="">
                                        <p></p>
                                        <input id="submit" name="submit" type="submit" value="Plaats reservering" class="btn btn-lg btn-primary btn-block" onclick="showLoadingIcon()">
                                        <div id="loadingIcon" style="display: none;" class="btn btn-lg btn-primary btn-block">
                                            <span id="loadingIcon" class="glyphicon glyphicon-refresh spinning"></span>
                                            Reservering plaatsen
                                        </div>

                                        <span class="clearfix"></span>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>