<?php
include '../config/config.inc.php';
if($_SESSION['ALOID'] != '') {
    header("location:" . $sitename);
    exit;
}

if (isset($_REQUEST['logsubmit'])) {
    @extract($_REQUEST);
    /* $captcha = $_POST['g-recaptcha-response'];
      $ip = $_SERVER['REMOTE_ADDR'];
      $rsp = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip=$ip";
      $jsondate = file_get_contents($rsp);
      $arr = json_decode($jsondate, true);
      if ($arr['success'] == '1') { */
    $msg = LoginCheck($uname, $pwd, $ip, $rememberme, $_REQUEST['login']);
    // echo $msg;
    if ($msg == "Admin" || $msg == "User" || $msg == "agent" || $msg == "Hurray! You will redirect into dashboard soon") {
        header("location:" . $sitename);
        exit;
    } else {
        echo '<script>window.onload = function(){ $("#login-box").addClass("animated shake" ); };</script>';
    }
    /* } else {
      $msg = $arr.'<span style="color:#FF0000; font-weight:bold;">Captcha Code Invalid</span>';
      } */
}
?>
<!DOCTYPE html>
<html lang="en">
    
<head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Admin || Login</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="webtoall" name="author" />
       
        <link href="<?php echo $sitename; ?>public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo $sitename; ?>public/assets/css/metismenu.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo $sitename; ?>public/assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="<?php echo $sitename; ?>public/assets/css/style.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div class="wrapper-page">

            <div class="card">
                <div class="card-body">

                    <h3 class="text-center m-0">Drop Taxi
                       <!--<img src="<?php echo $fsitename; ?>images/Logo.png" width="-->
                       <!-- 120">-->
                    </h3>

                    <div class="p-3">
                        <h4 class="text-muted font-18 m-b-5 text-center">Welcome Back !</h4>
                        <p class="text-muted text-center"><?php
                    if ($msg != '') {
                        echo $msg;
                    } else {
                        ?>Sign in
                    <?php } ?></p>
                        <form class="form-horizontal m-t-20" action="" method="post" autocomplete="off" id="login-box">
                            
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="uname" name="uname" required="required" value="<?php echo $_COOKIE['lemail']; ?>" class="form-control"  pattern="[a-zA-Z0-9.@-]{0,55}" title="Username" maxlength="55"/>
                            </div>

                            <div class="form-group">
                                <label for="userpassword">Password</label>
                                <input type="password" required="required" value="<?php echo $_COOKIE['lpass']; ?>" name="pwd" minlength='6' class="form-control" maxlength="55" id="pwd" title="Password" />
                            </div>

                            <div class="form-group row m-t-20">
                               
                                <div class="col-6 text-right">
								 <button class="btn btn-primary w-md waves-effect waves-light"
                                    type="submit" name="logsubmit" id="logsubmit">Log In
                            </button>
                                
                                </div>
                            </div>


                        </form>
                    </div>

                </div>
            </div>

            <div class="m-t-40 text-center">
               
                <p>© <?php echo date('Y'); ?> Drop Taxi.</p>
            </div>

        </div>

          <!-- jQuery  -->
  <script src="<?php echo $sitename; ?>public/assets/js/jquery.min.js"></script>
  <script src="<?php echo $sitename; ?>public/assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo $sitename; ?>public/assets/js/metisMenu.min.js"></script>
  <script src="<?php echo $sitename; ?>public/assets/js/jquery.slimscroll.js"></script>
  <script src="<?php echo $sitename; ?>public/assets/js/waves.min.js"></script>

  <script src="<?php echo $sitename; ?>public/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>

  <!-- App js -->
  <script src="<?php echo $sitename; ?>public/assets/js/app.js"></script>

</body>

</html>