<?php
// error_reporting(1);
// ini_set('display_errors','1');
// error_reporting(E_ALL);
function generateRandomString3($length = 4) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getplace($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `place` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}

function addplace($place,$status,$getid) {
    global $db;
    if ($getid == '') {
        $link22 = FETCH_all("SELECT * FROM `place` WHERE `place`=?", $type);
        if ($link22['id'] == '') {

            $resa = $db->prepare("INSERT INTO `place` (`place`,`status`) VALUES(?,?)");
            $resa->execute(array($place,$status));
              $res = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Inserted</div>';
        } else {
           $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Type already exists!</div>';
        }
    } else {
        $link22 = FETCH_all("SELECT * FROM `place` WHERE `place`=? AND `id`!=? ", $place,$getid);
        if ($link22['id'] == '') {
           $resa = $db->prepare("UPDATE `place` SET `place`=?,`status`=?  WHERE `id`=?");
            $resa->execute(array(trim($place),trim($status), $getid));

                                            
            $res ='<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Updated</div>';
        } else {
            $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Type already exists!</div>';
        }
    }
    return $res;
}

function delplace($a) {
    $b = str_replace(".", ",", $a);
    $b = explode(",", $b);
    foreach ($b as $c) {
        global $db;
        $get = $db->prepare("DELETE FROM `place` WHERE `id` = ? ");
        $get->execute(array($c));
    }
    $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Deleted!</div>';
    
    
    return $res;
}


function getnotificationcount() {
    global $db;
	

    $get1 = $db->prepare("SELECT count(*) AS `totcount` FROM `notification` WHERE `read_status`=? AND (`to`=? OR `title`=? OR `title`=? OR (`message`=? AND `driver_name`!='')) ");
    $get1->execute(array(0,'admin','DROPTAXI - Your Trip is Started','DROPTAXI - Your Trip is End','Confirm to get this booking'));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totcount'];
    return $res;
}
function getbooking($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `booking` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}
function delbooking($a) {
    $b = str_replace(".", ",", $a);
    $b = explode(",", $b);
    foreach ($b as $c) {
        global $db;
        $get = $db->prepare("DELETE FROM `booking` WHERE `id` = ? ");
        $get->execute(array($c));
    }
    $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Deleted!</div>';
    
    
    return $res;
}
function gettrip($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `per_day_km` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}
function addtrip($oneway,$roundtrip)
{
      global $db;

$resa = $db->prepare("UPDATE `per_day_km` SET `oneway`=?,`roundtrip`=? WHERE `id`=?");
$resa->execute(array(trim($oneway),trim($roundtrip),'1'));

 $res ='<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Updated</div>';
       
return $res;
}
function getpricelist($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `price_list` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}
function addpricelist($base_fare,$perkm,$bata_fee,$other_bata_fee,$getid)
{
      global $db;

$resa = $db->prepare("UPDATE `price_list` SET `per_km`=?,`bata_fee`=?,`other_bata_fee`=?,`base_fare`=? WHERE `id`=?");
$resa->execute(array(trim($perkm),trim($bata_fee),trim($other_bata_fee), $base_fare,$getid));

     $res ='<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Updated</div>';
       return $res;
}
function getnotification($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `notification` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}
function addnotification($type,$comment,$getid,$registerid)
{
      global $db;
$orderdetails = FETCH_all("SELECT * FROM `onlinekit` WHERE `id`=?", $getid);
$prodetails = FETCH_all("SELECT * FROM `product` WHERE `id`=?", $orderdetails['productid']);
                
$resa = $db->prepare("INSERT INTO `notification` (`orderid`,`orderstatus`,`proname`,`proimage`,`qty`,`totprice`,`registerid`,`userid`,`type`,`comment`, `complaintid`,`createdby`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
$resa->execute(array($orderdetails['orderid'],$orderdetails['orderstatus'],$prodetails['english_product'],$prodetails['image'],$orderdetails['qty'],$orderdetails['totprice'],$registerid,$_SESSION['ALOID'],$type,$comment,$getid, $_SESSION['ALOID']));
$res = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><h4><i class="icon fa fa-check"></i>Successfully Inserted</h4></div>';

}
function gettypes($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `types` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}

function addtype($type,$status, $ip, $getid) {
    global $db;
    if ($getid == '') {
        $link22 = FETCH_all("SELECT * FROM `types` WHERE `name`=?", $type);
        if ($link22['id'] == '') {

            $resa = $db->prepare("INSERT INTO `types` (`name`,`status`) VALUES(?,?)");
            $resa->execute(array($type,$status));
              $res = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Inserted</div>';
        } else {
           $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Type already exists!</div>';
        }
    } else {
        $link22 = FETCH_all("SELECT * FROM `types` WHERE `name`=? AND `id`!=? ", $type,$getid);
        if ($link22['id'] == '') {
           $resa = $db->prepare("UPDATE `types` SET `name`=?,`status`=?  WHERE `id`=?");
            $resa->execute(array(trim($type),trim($status), $getid));

                                            
            $res ='<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Updated</div>';
        } else {
            $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Type already exists!</div>';
        }
    }
    return $res;
}

function deltype($a) {
    $b = str_replace(".", ",", $a);
    $b = explode(",", $b);
    foreach ($b as $c) {
        global $db;
        $get = $db->prepare("DELETE FROM `types` WHERE `id` = ? ");
        $get->execute(array($c));
    }
    $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Deleted!</div>';
    
    
    return $res;
}
function getpackage($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `packages` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}

function addpackage($location,$distance,$sedan_car_price,$suv_car_price,$image,$status, $ip, $getid) {
    global $db;
    if ($getid == '') {
      
            $resa = $db->prepare("INSERT INTO `packages` (`location`,`distance`,`sedan_car_price`,`suv_car_price`,`image`,`status`) VALUES(?,?,?,?,?,?)");
            $resa->execute(array($location,$distance,$sedan_car_price,$suv_car_price,$image,$status));
              $res = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Inserted</div>';
      
    } else {
           $resa = $db->prepare("UPDATE `packages` SET `location`=?,`distance`=?,`sedan_car_price`=?,`suv_car_price`=?,`image`=?,`status`=?  WHERE `id`=?");
            $resa->execute(array($location,$distance,$sedan_car_price,$suv_car_price,$image,$status,$getid));

                                            
            $res ='<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Updated</div>';
         
    }
    return $res;
}

function delpackage($a) {
    $b = str_replace(".", ",", $a);
    $b = explode(",", $b);
    foreach ($b as $c) {
        global $db;
        $get = $db->prepare("DELETE FROM `packages` WHERE `id` = ? ");
        $get->execute(array($c));
    }
    $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Deleted!</div>';
    
    
    return $res;
}

function getregisterform($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `register` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}


function delregisterform($a) {
    global $db;
    $b = str_replace(".", ",", $a);
    $b = explode(",", $b);
    foreach ($b as $c) {
        $get = $db->prepare("DELETE FROM `register` WHERE `id` =? ");
        $get->execute(array(trim($c)));
    }
    $res = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><h5><i class="icon fa fa-close"></i> Successfully Deleted</h5></div>';
    return $res;
}

function getcar($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `cars` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}

function addcar($round_base_fare_km,$round_per_km,$round_beta_fee,$round_base_fare,$round_other_beta_fee,$base_fare_km,$per_km,$beta_fee,$base_fare,$other_beta_fee,$amenitiesl,$type,$name,$sit_count,$ac_status,$rental_amount,$image,$status, $ip, $getid) {
    global $db;
    if ($getid == '') {
        $link22 = FETCH_all("SELECT * FROM `cars` WHERE `name`=?", $name);
        if ($link22['id'] == '') {

            $resa = $db->prepare("INSERT INTO `cars` (`round_per_km`,`round_beta_fee`,`round_base_fare_km`,`round_base_fare`,`round_other_beta_fee`,`amenities`,`type`,`name`,`per_km`,`sit_count`,`ac_status`,`rental_amount`,`beta_fee`,`image`,`status`,`base_fare_km`,`base_fare`,`other_beta_fee`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $resa->execute(array($round_per_km,$round_beta_fee,$round_base_fare_km,$round_base_fare,$round_other_beta_fee,$amenitiesl,$type,$name,$per_km,$sit_count,$ac_status,$rental_amount,$beta_fee,$image,$status,$base_fare_km,$base_fare,$other_beta_fee));
              $res = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Inserted</div>';
        } else {
           $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Car Name already exists!</div>';
        }
    } else {
        $link22 = FETCH_all("SELECT * FROM `cars` WHERE `name`=? AND `id`!=? ", $name,$getid);
        if ($link22['id'] == '') {
           $resa = $db->prepare("UPDATE `cars` SET `round_per_km`=?,`round_beta_fee`=?,`round_base_fare_km`=?,`round_base_fare`=?,`round_other_beta_fee`=?,`amenities`=?,`type`=?,`name`=?,`per_km`=?,`sit_count`=?,`ac_status`=?,`rental_amount`=?,`beta_fee`=?,`image`=?,`status`=?,`base_fare`=?,`base_fare_km`=?,`other_beta_fee`=?  WHERE `id`=?");
            $resa->execute(array($round_per_km,$round_beta_fee,$round_base_fare_km,$round_base_fare,$round_other_beta_fee,$amenitiesl,$type,$name,$per_km,$sit_count,$ac_status,$rental_amount,$beta_fee,$image,$status,$base_fare,$base_fare_km,$other_beta_fee,$getid));

                                            
            $res ='<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Updated</div>';
        } else {
            $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Car Name already exists!</div>';
        }
    }
    return $res;
}

function delcar($a) {
    $b = str_replace(".", ",", $a);
    $b = explode(",", $b);
    foreach ($b as $c) {
        global $db;
        $get = $db->prepare("DELETE FROM `cars` WHERE `id` = ? ");
        $get->execute(array($c));
    }
    $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Deleted!</div>';
    
    
    return $res;
}
function getdriver($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `driver` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}

function adddriver($wallet,$image,$image1,$image2,$driver_name,$driver_mobileno,$licence_no,$car_no,$login_username,$login_password,$driver_address,$car_id,$status, $ip, $getid) {
    global $db;
    if ($getid == '') {
        $link22 = FETCH_all("SELECT * FROM `driver` WHERE `driver_name`=?", $driver_name);
        if ($link22['id'] == '') {
$token=md5(uniqid(rand()));
            $resa = $db->prepare("INSERT INTO `driver` (`wallet`,`profile`,`license`,`rc_book`,`driver_name`,`driver_mobileno`,`licence_no`,`car_no`,`login_username`,`login_password`,`driver_address`,`car_id`,`status`,`token`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $resa->execute(array($wallet,$image,$image1,$image2,$driver_name,$driver_mobileno,$licence_no,$car_no,$login_username,$login_password,$driver_address,$car_id,$status,$token));
              $res = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Inserted</div>';
        } else {
           $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Driver Name already exists!</div>';
        }
    } else {
        $link22 = FETCH_all("SELECT * FROM `driver` WHERE `driver_name`=? AND `id`!=? ", $driver_name,$getid);
        if ($link22['id'] == '') {
           $resa = $db->prepare("UPDATE `driver` SET `profile`=?,`wallet`=?,`license`=?,`rc_book`=?,`driver_name`=?,`driver_mobileno`=?,`licence_no`=?,`car_no`=?,`login_username`=?,`login_password`=?,`driver_address`=?,`car_id`=?,`status`=? WHERE `id`=?");
            $resa->execute(array($image,$wallet,$image1,$image2,$driver_name,$driver_mobileno,$licence_no,$car_no,$login_username,$login_password,$driver_address,$car_id,$status,$getid));

                                            
            $res ='<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Updated</div>';
        } else {
            $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Driver Name already exists!</div>';
        }
    }
    return $res;
}

function deldriver($a) {
    $b = str_replace(".", ",", $a);
    $b = explode(",", $b);
    foreach ($b as $c) {
        global $db;
        $get = $db->prepare("DELETE FROM `driver` WHERE `id` = ? ");
        $get->execute(array($c));
    }
    $res = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Deleted!</div>';
    
    
    return $res;
}
?>