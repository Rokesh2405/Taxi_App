<?php

include ('../../config/config.inc.php');


if($_REQUEST['mobileno']!='') {
$link22 = FETCH_all("SELECT  * FROM `register` WHERE `mobileno`=? ", $_REQUEST['mobileno']);
echo $link22['additional_no'];
}
if($_REQUEST['bookingid']!='') {
global $db;
global $sitename;
$uquery = "UPDATE `booking` SET
                    view_status='1' WHERE `id`='".$_REQUEST['bookingid']."' ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();  
echo '<script>window.location.href = "'.$sitename.'master/booking.htm";</script>'; 
exit;
}


?>