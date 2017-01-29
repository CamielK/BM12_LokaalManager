<?php
    //check if session is valid
    $min_auth_level = 'ADMIN';
    include('session.php');

    //handle submit action
    if (isset($_POST["submit"])) {

        //get inputs
        $name = $_POST['naam'];
        $mail = $_POST['mail'];
        $pass = $_POST['wachtwoord'];
        $pass2 = $_POST['wachtwoord2'];
        $group = $_POST['group'];

        //check inputs
        if (!$_POST['naam']) {
            $errName = 'Vul een gebruikersnaam in';
        }
        if (!$_POST['mail'] || !filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
            $errMail = 'Vul een geldig emailadres in';
        }
        if (!$_POST['wachtwoord']) {
            $errPass = 'Vul een wachtwoord in';
        }
        if (!$_POST['wachtwoord2']) {
            $errPass2 = 'Bevestig het wachtwoord';
        }
        if (!$_POST['group']) {
            $errGroup = 'Selecteer een gebruikersgroep';
        }
        if ($_POST['wachtwoord'] != $_POST['wachtwoord2']) {
            $errPass2 = 'De wachtwoorden komen niet overeen';
        }



        if (!isset($errName) && !isset($errMail) && !isset($errPass) && !isset($errPass2) && !isset($errGroup)) {

            include_once('../_class/user.php');
            $user = new user();

            $success = $user->addUser($name, $mail, $pass, $group);


            switch ($success) {
                case 200:
                    $result='<div class="alert alert-success">Gebruiker toegevoegd!</div>';
                    break;
                case 404:
                    $result='<div class="alert alert-danger">Er ging iets fout tijdens het toevoegen. probeer het nog eens.</div>';
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
    <title>Gebruikers beheren</title>
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/panel.css">

    <script>
        function showLoadingIcon() {
            document.getElementById('submit').style.display = 'none';
            document.getElementById('loadingIcon').style.display = 'block';
        }
    </script>
</head>
<body>

<div id="wrapper">



    <div id="row">

        <div id="sidebar" class="col-sm-3 col-md-2 sidebar">
            <?php $page = 'usr'; include('../templates/adminSidebar.php'); ?>
        </div>

        <div id="content" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="panel panel-blue margin-bottom-40">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-tasks"></i> Gebruiker toevoegen</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="post" action="gebruikers.php" >
                        <?php if (isset($result)) { echo $result; } ?>
                        <div class="form-group">
                            <label for="inputType" class="col-md-2 control-label col-md-offset-0">Naam</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="naam" name="naam" placeholder="Gebruikersnaam" value="
<?php if (isset($_POST['naam'])) { echo htmlspecialchars($_POST['naam']); } ?>">
                            </div>
                            <?php if (isset($errName)) { echo "<p class='text-danger col-md-offset-2'>$errName</p>"; } ?>
                        </div>
                        <div class="form-group">
                            <label for="inputType" class="col-md-2 control-label col-md-offset-0">Mail</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="mail" name="mail" placeholder="you@example.com" value="
<?php if (isset($_POST['mail'])) { echo htmlspecialchars($_POST['mail']); } ?>">
                            </div>
                            <?php if (isset($errMail)) { echo "<p class='text-danger col-md-offset-2'>$errMail</p>"; } ?>
                        </div>
                        <div class="form-group">
                            <label for="inputType" class="col-md-2 control-label col-md-offset-0">Wachtwoord</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control" id="wachtwoord" name="wachtwoord" placeholder="******">
                            </div>
                            <?php if (isset($errPass)) { echo "<p class='text-danger col-md-offset-2'>$errPass</p>"; } ?>
                        </div>
                        <div class="form-group">
                            <label for="inputType" class="col-md-2 control-label col-md-offset-0">Bevestig wachtwoord</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control" id="wachtwoord2" name="wachtwoord2" placeholder="******">
                            </div>
                            <?php if (isset($errPass2)) { echo "<p class='text-danger col-md-offset-2'>$errPass2</p>"; } ?>
                        </div>
                        <div class="form-group">
                            <label for="inputType" class="col-md-2 control-label col-md-offset-0">Groep</label>
                            <div class="col-md-10">
                                <?php $groups = array('ADMIN', 'STUDENT', 'TEACHER'); ?>
                                <select class="form-control" id="group" name="group">
                                    <option disabled selected value>Kies een gebruikersgroep</option>
                                    <?php foreach($groups as $group) { ?>
                                        <option value="<?php echo $group;?>" <?php echo ($_POST["group"] ==  $group) ? ' selected="selected"' : '';?>><?php echo $group;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if (isset($errGroup)) { echo "<p class='text-danger col-md-offset-2'>$errGroup</p>"; } ?>
                        </div>
                        <div class="form-group">
                            <input id="submit" name="submit" type="submit" value="Gebruiker toevoegen" class="col-md-4 col-md-offset-4 btn btn-lg btn-primary" onclick="showLoadingIcon()">
                            <div id="loadingIcon" style="display: none;" class="col-md-4 col-md-offset-4 btn btn-lg btn-primary">
                                <span id="loadingIcon" class="glyphicon glyphicon-refresh spinning"></span>
                                Gebruiker toevoegen..
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