<?php
    //check if session is valid
    $min_auth_level = 'STUDENT';
    include('session.php');

    //handle submit action
    if (isset($_POST["submit"])) {

        //get inputs
        $date = $_POST['datum'];
        $start = $_POST['start_tijd'];
        $end = $_POST['eind_tijd'];
        $classroom = $_POST['lokaal'];
        $info = $_POST['beschrijving'];

        //check inputs
        if (!$_POST['datum']) {
            $errDate = 'Selecteer een datum';
        }
        if (!$_POST['start_tijd']) {
            $errStart = 'Selecteer een start tijd';
        }
        if (!$_POST['eind_tijd']) {
            $errEinde = 'Selecteer een eind tijd';
        }
        if (!$_POST['lokaal']) {
            $errLokaal = 'Selecteer een lokaal';
        }


        if (!isset($errDate) && !isset($errStart) && !isset($errEinde) && !isset($errLokaal)) {

            include_once('../_class/schedule.php');
            $schedule = new schedule();

            $success = $schedule->addScheduleRecord($date, $start, $end, $classroom, $info, $_SESSION['login_user']);


            switch ($success) {
                case 200:
                    $result='<div class="alert alert-success">Reservering toegevoegd!</div>';
                    break;
                case 404:
                    $result='<div class="alert alert-danger">Er ging iets fout tijdens het reserveren. probeer het nog eens.</div>';
                    break;
                case 503:
                    $result='<div class="alert alert-danger">Service unavailable, try again later</div>';
                    break;
                default:
                    $result='<div class="alert alert-danger">Onbekende error</div>';
                    break;
            }
        }
    }


?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
    <head>
        
        <?php include('../templates/head.php'); ?>
        <title>Reserveringen</title>
        <link rel="stylesheet" href="/css/admin.css">
        <link rel="stylesheet" href="/css/panel.css">

        <script>
            function showLoadingIcon() {
                document.getElementById('submit').style.display = 'none';
                document.getElementById('loadingIcon').style.display = 'block';
            }
            function hideLoadingIcon() {
                document.getElementById('submit').style.display = 'block';
                document.getElementById('loadingIcon').style.display = 'none';
            }
        </script>

    </head>
    <body>

        <div id="wrapper">

            <div id="row">

                <div id="sidebar" class="col-sm-3 col-md-2 sidebar">
                    <?php $page = 'res'; include('../templates/adminSidebar.php'); ?>
                </div>

                <div id="content" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">


                    <div class="panel panel-blue margin-bottom-40">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-tasks"></i> Plaats reservering</h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="post" action="reservering.php" >
                                <?php if (isset($result)) { echo $result; } ?>
                                <div class="form-group">
                                    <label for="inputType" class="col-md-2 control-label col-md-offset-0">Datum</label>
                                    <div class="col-md-10">
                                        <input type="date" class="form-control" id="datum" name="datum" placeholder="Datum" value="<?php echo (isset($_POST['datum'])) ? date('Y-m-d', strtotime($_POST['datum'])) : ""; ?>">
                                    </div>
                                    <?php if (isset($errDate)) { echo "<p class='text-danger col-md-offset-2'>$errDate</p>"; } ?>
                                </div>
                                <div class="form-group">
                                    <label for="inputType" class="col-md-2 control-label col-md-offset-0">Start tijd</label>
                                    <div class="col-md-10">
                                        <input type="time" class="form-control" id="start_tijd"  name="start_tijd" placeholder="Start" value="<?php echo (isset($_POST['datum'])) ? date('H:i', strtotime($_POST['start_tijd'])) : ""; ?>">
                                    </div>
                                    <?php if (isset($errStart)) { echo "<p class='text-danger col-md-offset-2'>$errStart</p>"; } ?>
                                </div>
                                <div class="form-group">
                                    <label for="inputType" class="col-md-2 control-label col-md-offset-0">Eind tijd</label>
                                    <div class="col-md-10">
                                        <input type="time" class="form-control" id="eind_tijd" name="eind_tijd" placeholder="Einde" value="<?php echo (isset($_POST['datum'])) ? date('H:i', strtotime($_POST['eind_tijd'])) : ""; ?>">
                                    </div>
                                    <?php if (isset($errEinde)) { echo "<p class='text-danger col-md-offset-2'>$errEinde</p>"; } ?>
                                </div>
                                <div class="form-group">
                                    <label for="inputType" class="col-md-2 control-label col-md-offset-0">Lokaal</label>
                                    <div class="col-md-10">
                                        <?php $lokalen = array('B3206', 'B3208', 'B3210', 'B3212', 'B3214', 'B3216'); ?>
                                        <select class="form-control" id="lokaal" name="lokaal">
                                            <option disabled selected value>Kies een lokaal</option>
                                            <?php foreach($lokalen as $lokaal) { ?>
                                                <option value="<?php echo $lokaal;?>" <?php echo ($_POST["lokaal"] ==  $lokaal) ? ' selected="selected"' : '';?>><?php echo $lokaal;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php if (isset($errLokaal)) { echo "<p class='text-danger col-md-offset-2'>$errLokaal</p>"; } ?>
                                </div>
                                <div class="form-group">
                                    <label for="inputType" class="col-md-2 control-label col-md-offset-0">Beschrijving</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="beschrijving" name="beschrijving" placeholder="Beschrijving" value="
<?php if (isset($_POST['beschrijving'])) { echo htmlspecialchars($_POST['beschrijving']); } ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input id="submit" name="submit" type="submit" value="Plaats reservering" class="col-md-4 col-md-offset-4 btn btn-lg btn-primary" onclick="showLoadingIcon()">
                                    <div id="loadingIcon" style="display: none;" class="col-md-4 col-md-offset-4 btn btn-lg btn-primary">
                                        <span id="loadingIcon" class="glyphicon glyphicon-refresh spinning"></span>
                                        Reservering plaatsen..
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
    </body>
</html>