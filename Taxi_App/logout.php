<?php 

include 'config/config.inc.php';

session_start();

logout();

session_destroy();

session_unset();

header("location:https://droptaximadurai.in/appadmin/pages/");

exit;

?>

