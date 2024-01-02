<?php  include ('config/config.inc.php');


 $stmt=$db->prepare("SELECT * FROM `notification` WHERE `read_status`='0' AND (`to`='admin' OR `title`='DROPTAXI - Your Trip is Started' OR `title`='DROPTAXI - Your Trip is End' OR (`message`='Confirm to get this booking' AND `driver_name`!='') ");
 $stmt->execute();
 $cnt=$stmt->rowCount();

echo $cnt;   

?>