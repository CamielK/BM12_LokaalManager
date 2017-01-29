<!DOCTYPE html>

<?php
    
//get session info, redirect to control panel if user is already logged in
session_start();
if (isset($_SESSION['login_user'])) {
    header('Location: reservering.php');
}

//show captcha after more then 3 attempts
if (isset($_SESSION['attempts'])) {
    if ($_SESSION['attempts'] > 2) {
    	$useCaptcha = true;
    } else {
	$useCaptcha = false;
    }
} else {
    $useCaptcha = false;
}

//handle submit action
if (isset($_POST["submit"])) {

    //get inputs
    $name = $_POST['name'];
    $password = $_POST['password'];

    //check inputs
    if (!$_POST['name']) {
        $errName = 'Please enter your name';
    }
    if (!$_POST['password']) {
        $errPassword = 'Please enter your password';
    }
    

		
    //check recaptcha
    if ($useCaptcha) {
        if (!$_POST['g-recaptcha-response']) {
            $errCaptcha = 'Please confirm you are not a robot';
        } else {
            //get connection with google
            
            $url = "https://www.google.com/recaptcha/api/siteverify";
            $key = "6LcW3RIUAAAAAK8SUwAzef2AgJb-_QzVLERFMhTs";
            $input = $_POST['g-recaptcha-response'];
            $response = null;
            
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
                            'secret' => $key,
                            'response' => $input
                        )));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            //echo $response;
            
            //check for success
            if ($response != null && (strpos($response, 'success": true,')!==FALSE)) {
                $validCaptcha = true;
            } else {
                $errCaptcha = 'Invalid captcha response';
            }
        }
    }
    
    
    
    if (!isset($errName) && !isset($errPassword)) {
        
        if (!($useCaptcha && !isset($validCaptcha))) {
            include_once('../php/loginForm.php');
            $loginForm = new loginForm($name, $password);

            $success = $loginForm->sendForm();

            //debug
            //$success = (string)$success;
            //$result="<div class=\"alert alert-success\">$success</div>";
            //$result="<script>console.log( 'Debug Objects: " . (string)$success . "' );</script>";

    //        if ($success) {
    //            $result='<div class="alert alert-success">Login success. redirecting..</div>';
    //        } else {
    //            $result='<div class="alert alert-danger">Invalid login credentials</div>';
    //        }

            switch ($success) {
                case 200:
                    $result='<div class="alert alert-success">Login success. redirecting..</div>';
                    $_SESSION['attempts'] = 0;
                    $useCaptcha = false;
                    
                    //check for remember functionality
                    if (isset($_POST['rememberCbx'])) {
                        //create cookie
                        setcookie("name",$name);
                    } else {
                        //make cookie expire if user does not wish to be remembered
                        setcookie("name", "", time()-3600);
                    }
                    
                    header("location: reservering.php");
                    break;
                case 401:
                    $result='<div class="alert alert-danger">Invalid password</div>';
                    $invalid_attempt = true;
                    break;
                case 404:
                    $result='<div class="alert alert-danger">No user with that name</div>';
                    $invalid_attempt = true;
                    break;
                case 503:
                    $result='<div class="alert alert-danger">Service unavailable, try again later</div>';
                    break;
                default:
                    $result='<div class="alert alert-danger">Unknown error</div>';
                    break;
            }


            if (isset($invalid_attempt)) {
		if (isset($_SESSION['attempts'])) {
		    $_SESSION['attempts'] = $_SESSION['attempts'] + 1;
		    if ($_SESSION['attempts'] > 2) {
                        $useCaptcha = true;
                    }
		} else {
		    $_SESSION['attempts'] = 1;
		}
                
                
            }
        }
        
    }
}

?>

<html class="img-no-display">
<head>
    
    <?php include('../templates/head.php'); ?>
    
    <title>Gebruiker login</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/styles.css">
    <script src='https://www.google.com/recaptcha/api.js?hl=en'></script>
    
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
<body style="padding-top: 50px;">
    
    </br>
    <h1 class="text-center">ZUYD Lokaal manager</h1>
    </br>
    </br>
    
    <div id="wrap">

        <div class="container">

            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4">
                    <div class="account-wall">
                        <form class="form-signin" name="form_login" method="post" action="login.php" role="form">
                            
                            <?php if (isset($result)) { echo $result; } ?>
                            <h1 class="text-center login-title">Gebruiker login</h1>
                            <input type="text" class="form-control" id="name" name="name" placeholder="username" value="
<?php if (isset($_POST['name'])) { echo htmlspecialchars($_POST['name']); } ?>">
                            <?php if (isset($errName)) { echo "<p class='text-danger'>$errName</p>"; } ?>
                            <input type="password" class="form-control" id="password" name="password" placeholder="password" value="<?php if (isset($_POST['password'])) { echo htmlspecialchars($_POST['password']); } ?>">
                            <?php if (isset($errPassword)) { echo "<p class='text-danger'>$errPassword</p>"; } ?>
                            
                            <?php 
                                if ($useCaptcha) {
                                    echo "<div class='g-recaptcha' data-sitekey='6LcW3RIUAAAAACA1TOM8-oMIX48hWrTlP9EherP0' data-theme='dark'></div>";
                                    if (isset($errCaptcha)) { echo "<p class='text-danger'>$errCaptcha</p>"; }
                                }
                            ?>
                            
                            <input id="submit" name="submit" type="submit" value="Sign in" class="btn btn-lg btn-primary btn-block" onclick="showLoadingIcon()">
                            
                            <div id="loadingIcon" style="display: none;" class="btn btn-lg btn-primary btn-block"> 
                                <span id="loadingIcon" class="glyphicon glyphicon-refresh spinning"></span> 
                                Signing in
                            </div>
                            
                            <label class="checkbox pull-left">
                                &nbsp; &nbsp; &nbsp;
                                <input type="checkbox" value="remember-me" id="rememberCbx" name="rememberCbx[]">
                                Remember me
                            </label>
                            
                            <span class="clearfix"></span>
                        </form>
                    </div>
                </div>
            </div>
            
            
            
        </div>
    </div>
    
    <?php include_once('../templates/footer.php') ?>


    <!--    check for remember name cookie    -->
    <script>
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i];
            while (cookie.charAt(0)==' ') {
                cookie = cookie.substr(1);
            }
            if (cookie.indexOf('name') == 0) {
                console.log(cookie);
                document.getElementById('name').value = cookie.substr(5,cookie.length);
                document.getElementById('rememberCbx').checked = true;
            }
        }
    </script>
</body>
</html>
