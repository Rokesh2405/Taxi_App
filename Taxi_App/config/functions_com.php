<?php
function generateRandomString($length = 6) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandomString1($length = 6) {
    $characters = 'abcdefghijklmnopqrstuvwxyABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function adduser($name, $emailid, $mobileno)
{
 global $db;
 //Main Portal
 $username1="Allolab@".$name;
 $pass1 = openssl_random_pseudo_bytes(6);
 $pass = bin2hex($pass1);
 $password1="Portal@".$pass;
  //Main Portal
  //Doctor Portal
 $username2="Allodoc@".$name;
 $pass2 = openssl_random_pseudo_bytes(6);
 $pass22 = bin2hex($pass2);
 $password2="Doctor@".$pass22;
 //Doctor Portal

$ip = $_SERVER['REMOTE_ADDR'];
//$otp = rand(0,999999);
$otp=generateRandomString();
$Date = date("Y-m-d");
$curdate=date('Y-m-d', strtotime($Date. ' + 15 days'));


  $sns = Aws\Sns\SnsClient::factory(array(
    'credentials' => [
        'key'    => 'AKIA6OFTZJPN6AGQBGOM',
        'secret' => '4AM9XmyMwPjJ5RJwfj6jKgSYQ5iJpPrHIY1kMbcq',
    ],
    'region' => 'us-east-1',
    'version'  => 'latest',
));

$sms_msgv = "<#> Your Demo Allolab OTP : ".$otp;
//$sms_msgv=$smsmessage;
$result = $sns->publish([
    'Message' => $sms_msgv, // REQUIRED
    'MessageAttributes' => [
        'AWS.SNS.SMS.SenderID' => [
            'DataType' => 'String', // REQUIRED
            'StringValue' => 'INAllolab11'
        ],
        'AWS.SNS.SMS.SMSType' => [
            'DataType' => 'String', // REQUIRED
            'StringValue' => 'Transactional' // or 'Promotional'
        ]
    ],
    'PhoneNumber' => '+91'.$mobileno,
]);
// echo "<pre>";
// echo $result['@metadata']['statusCode'];

// Send SMS End

if($result['@metadata']['statusCode']=='200') { 
 $link22 = FETCH_all("SELECT * FROM `users` WHERE `emailid`=? OR `mobileno`", $emailid,$mobileno);
 if($link22['id']=='')
 {
 $resa = $db->prepare("INSERT INTO `users` (`name`,`emailid`,`mobileno`,`expiry_date`,`type`,`status`,`val1`,`val2`,`val3`,`orgpassword`,`otp`,`ip`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
 $resa->execute(array($name,$emailid, $mobileno, $curdate, '1','1',$username1,md5($password1),'1',$password1,$otp,$ip));
 $id = $db->lastInsertId();
 
 // Add Patients
 
 $patientidid1=substr($name,0,3)."001";
 $patientidid2=substr($name,0,3)."002";
 
  $patientname1="Priya";
  $patientname2="Malathi";
  
  $healthworkerid1=generateRandomString1();
  
  $patientmobileno1="9361513147";
  $patientmobileno2="9344908938";
  
$token1 = openssl_random_pseudo_bytes(16);
$patoken1 = bin2hex($token1);

$token2 = openssl_random_pseudo_bytes(16);
$patoken2 = bin2hex($token2);

$cudate=date("Y-m-d");

$token11 = openssl_random_pseudo_bytes(16);
$patoken11 = bin2hex($token11);


 $healthesa = $db->prepare("INSERT INTO `healthworker` (`adminid`, `healthworker`, `doctor`, `village`, `patient`, `healthworkerid`,`alreadytrained`, `token`, `status`) VALUES
($id, 'Abinaya', NULL, NULL, 27, '".$healthworkerid1."','0', '".$patoken11."', 1)");
 $healthesa->execute();


//Add Patient

 $patresa = $db->prepare("INSERT INTO `patient` (`patientid`, `adminid`,`otpused`, `name`, `age`, `gender`, `locality`, `healthworker`, `doctor`, `mobileno`, `blood_group`, `feedback`, `highrisk`, `token`) VALUES ('".$patientidid1."', '".$id."', '1', '".$patientname1."', '29', 'Female', 'Madurai', 'Abinaya', '".$name."', '".$patientmobileno1."' , 'B+', NULL, NULL, '".$patoken1."')");
 $patresa->execute(array());
 
 $patinsertid=$db->lastInsertId();
 
 $patvital = $db->prepare("INSERT INTO `vitals` (`visit`,`height`, `weight`, `hb`, `bp`, `bp_DBP`, `bp_SBP`, `heart_rate`, `temperature`, `blood_oxygen`, `blood_glucose`, `ecg_timebase`, `ecg_gain`, `ecg_duration`, `ecg_rrmax`, `ecg_rrmin`, `ecg_hr`, `ecg_hrv`, `ecg_mood`, `ecg_br`, `sugar`, `hemoglobin`, `highrisk`, `patientid`,`appdate`) VALUES
('1','168', '74', '58', '56/41 mm', '41', '56', '58', '37.3', '99', '100', '25mm/s', '10mm/mV', '32', '1181', '416', '117', '54', '31', '14', '', 'Nil', 'Nil', '".$patinsertid."', '".$cudate."' )");
 $patvital->execute(array());
 
 $patsym = $db->prepare("INSERT INTO `symptoms` (`patient`, `visitno`, `headache`, `blurred`, `dizziness`, `breathe`, `edema`, `chest`, `abdominal`, `vomiting`, `stomachburning`, `urinaryburning`, `bleeding`, `feverandchils`, `rashesonskin`, `vaginalitching`, `asthma`, `bodypain`, `cough`, `sourtaste`,`appdate`) VALUES
('".$patinsertid."', '1', 1, 0, 1, 1, 0, 1, 0, '0', '0', '0', NULL, '0', '0', '0', '0', '0', '0', '0', '".$cudate."')");
 $patsym->execute(array());
 
 $patsupply = $db->prepare("INSERT INTO `supply` (`visit`, `details`, `tting`, `irontab`, `patientid`, `date`) VALUES
(1, 'paracetamol', NULL, NULL, '".$patinsertid."', '".$cudate."')");
 $patsupply->execute(array());
 

$patresa1 = $db->prepare("INSERT INTO `patient` (`patientid`, `adminid`,`otpused`, `name`, `age`, `gender`, `locality`, `healthworker`, `doctor`, `mobileno`, `blood_group`, `feedback`, `highrisk`, `token`) VALUES ('".$patientidid2."', '".$id."', '1', '".$patientname2."', '29', 'Female', 'Madurai', 'Abinaya', '".$name."', '".$patientmobileno2."' , 'B+', NULL, NULL, '".$patoken2."')");
 $patresa1->execute(array());
 
 $patinsertid1=$db->lastInsertId();
 
 $patvital1 = $db->prepare("INSERT INTO `vitals` (`visit`,`height`, `weight`, `hb`, `bp`, `bp_DBP`, `bp_SBP`, `heart_rate`, `temperature`, `blood_oxygen`, `blood_glucose`, `ecg_timebase`, `ecg_gain`, `ecg_duration`, `ecg_rrmax`, `ecg_rrmin`, `ecg_hr`, `ecg_hrv`, `ecg_mood`, `ecg_br`, `sugar`, `hemoglobin`, `highrisk`, `patientid`,`appdate`) VALUES
('1','168', '74', '58', '56/41 mm', '41', '56', '58', '37.3', '99', '100', '25mm/s', '10mm/mV', '32', '1181', '416', '117', '54', '31', '14', '', 'Nil', 'Nil', '".$patinsertid1."', '".$cudate."' )");
 $patvital1->execute(array());
 
 $patsym1 = $db->prepare("INSERT INTO `symptoms` (`patient`, `visitno`, `headache`, `blurred`, `dizziness`, `breathe`, `edema`, `chest`, `abdominal`, `vomiting`, `stomachburning`, `urinaryburning`, `bleeding`, `feverandchils`, `rashesonskin`, `vaginalitching`, `asthma`, `bodypain`, `cough`, `sourtaste`,`appdate`) VALUES
('".$patinsertid1."', '1', 1, 0, 1, 1, 0, 1, 0, '0', '0', '0', NULL, '0', '0', '0', '0', '0', '0', '0', '".$cudate."')");
 $patsym1->execute(array());
 
 $patsupply1 = $db->prepare("INSERT INTO `supply` (`visit`, `details`, `tting`, `irontab`, `patientid`, `date`) VALUES
(1, 'paracetamol', NULL, NULL, '".$patinsertid1."', '".$cudate."')");
 $patsupply1->execute(array());
 
 
 
 // Add Patients
 
 $resa1 = $db->prepare("INSERT INTO `doctor` (`adminid`,`doctorname`,`username`,`password`,`emailid`,`mobileno`,`status`) VALUES (?,?,?,?,?,?,?)");
 $resa1->execute(array($id,$name,$username2,$password2,$emailid,$mobileno,'1'));
 $id1 = $db->lastInsertId();
 
 $resa2 = $db->prepare("INSERT INTO `users` (`name`,`emailid`,`mobileno`,`expiry_date`,`type`,`status`,`doctorid`,`usergroup`,`val1`,`val2`,`val3`,`orgpassword`,`ip`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
 $resa2->execute(array($name,$emailid, $mobileno, $curdate,'1','1',$id1,$id,$username2,md5($password2),'1',$password2,$ip));
 
 $res = 'success';
 return $res;    
 }
 else
 {
  $res = 'Emailid Or Mobileno Already Exist';
 return $res;     
 }
}
else
  $res = 'SMS Not Send';
 return $res;   
}

function LoginCheck($a = '', $b = '', $c = '', $d = '', $e = '') {

    global $db;
    if (($a == '') || ($b == '')) {
        $res = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><i class="icon fa fa-close"></i>Email or Password was empty</div>';
    } else {
        if ($e == '') {
            $stmt = $db->prepare("SELECT * FROM `users` WHERE `val1`=? AND `val3`=?");
            $stmt->execute(array($a, 1));
            $ress = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($ress['id'] != '') {
                if ($ress['val2'] == md5($b)) {
                    $res = "Hurray! You will redirect into dashboard soon";
                    $_SESSION['ALOID'] = $ress['id'];
                    $_SESSION['sitename']=$ress['name'];
                    $_SESSION['Gpassword'] = $ress['orgpassword'];
                    $_SESSION['type'] = 'admin';
                    @extract($ress);
                    if ($id != '') {
                        $e = date('Y-m-d H:i:s');
                        $sql = 'INSERT INTO `admin_history`(admin_uid,ip,checkintime) VALUES(?,?,?)';
                        $stmt1 = $db->prepare($sql);
                        $stmt1->execute(array($id, $c, $e));
                        $_SESSION['admhistoryid'] = $db->lastInsertId();
                        if ($d == '1') {
                            //if rememberme checkbox checked
                            setcookie('lemail', $a, time() + (60 * 60 * 24 * 10)); //Means 10 days change value of 10 to how many days as you want to remember the user details on user's computer
                            setcookie('lpass', $b, time() + (60 * 60 * 24 * 10));  //Here two coockies created with username and password as cookie names, $username,$password (login crediantials) as corresponding values
                        }
                    }
                } elseif ($ress['val3'] == '2') {
                    $res = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><i class="icon fa fa-close"></i> Your Account was deactivated by Admin</div>';
                } else {
                    $res = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><i class="icon fa fa-close"></i> Email or Password was incorrect</div>';
                }
            } else {

                $stmt2 = $db->prepare("SELECT * FROM `usermaster` WHERE `username`=?");
                $stmt2->execute(array($a));
                $sql = $stmt2->fetch(PDO::FETCH_ASSOC);
                if ($sql['uid'] != '') {

                    $stmt3 = $db->prepare("SELECT * FROM `permission` WHERE `pid`=?");
                    $stmt3->execute(array($sql['permissiongroup']));
                    $per = $stmt3->fetch(PDO::FETCH_ASSOC);
                    if ($per['status'] == '1') {
                        if (($a == $sql['username']) && ($b == $sql['password'])) {

                            $_SESSION['ALOID'] = $sql['uid'];
                            $_SESSION['UIDD'] = $sql['userid'];
                            $_SESSION['permissionid'] = $sql['permissiongroup'];
                            $res = "User";
                        }
                    } else {
                        $res = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><i class="icon fa fa-close"></i>Access denied !</div>';
                    }
                } else {
                    $res = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><i class="icon fa fa-close"></i> Invalid login details!</div>';
                }
            }
            return $res;
        }
    }
}

function logout() {
    global $db;
    $sql = $db->prepare("UPDATE `admin_history` SET `checkouttime`='" . date('Y-m-d H:i:s') . "' WHERE `id`=?");
    $sql->execute(array($_SESSION['admhistoryid']));
    // DB("UPDATE `admin_history` SET `checkouttime`='" . date('Y-m-d H:i:s') . "' WHERE `id`='" . $_SESSION['admhistoryid'] . "'");
}

function companylogos($a) {
    //$getlogo = mysql_fetch_array(mysql_query("SELECT `image` FROM `profile_area` WHERE `pid`='" . $a . "'"));
    global $db;
    $getlogo1 = $db->prepare("SELECT `image` FROM `profile_area` WHERE `pid`=?");
    $getlogo1->execute(array($a));
    $getlogo = $getlogo1->fetch(PDO::FETCH_ASSOC);
    if ($getlogo['image'] != '') {
        $res = $getlogo['image'];
    } else {
        $res = $sitename . 'data/profile/logo.png';
    }
    return $res;
}

function addprofile($tax,$title, $firstname, $lastname, $image, $cmpnyname, $recoveryemail, $phonenumber,$mail_option, $caddress, $abn, $ip,$bank_name,$branch_name,$account_name,$account_no,$ifsc_code,$swift_code,$branch_address, $id) {
    global $db;
    if ($id == '') {
        $resa = $db->prepare("INSERT INTO `manageprofile` (`tax`,`title`,`firstname`,`lastname`,`image`,`Company_name`,`recoveryemail`,`phonenumber`,`caddress`,`abn`,`ip`,`mail`,`bank_name`,`branch_name`,`account_name`,`account_no`,`ifsc_code`,`swift_code`,`branch_address`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $resa->execute(array($tax,$title, $firstname, $lastname, $image, $cmpnyname, $recoveryemail, $phonenumber, $caddress, $abn, $ip,$mail_option,$bank_name,$branch_name,$account_name,$account_no,$ifsc_code,$swift_code,$branch_address));
        $res = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><h4><i class="icon fa fa-check"></i>Successfully Inserted</h4></div>';
    } else {
        
        $resa = $db->prepare("UPDATE `manageprofile` SET `tax`=?,`title`=?,`firstname`=?,`lastname`=?,`image`=?,`Company_name`=?,`recoveryemail`=?,`phonenumber`=?,`caddress`=?,`abn`=?,`ip`=?,`mail`=?,`bank_name`=?,`branch_name`=?,`account_name`=?,`account_no`=?,`ifsc_code`=?,`swift_code`=?,`branch_address`=? WHERE `pid`=?");
        $resa->execute(array($tax,$title, $firstname, $lastname, $image, $cmpnyname, $recoveryemail, $phonenumber, $caddress, $abn, $ip,$mail_option,$bank_name,$branch_name,$account_name,$account_no,$ifsc_code,$swift_code,$branch_address, $id));
        $res = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><h4><i class="icon fa fa-check"></i> Successfully Updated</h4></div>';
    }

    return $res;
}

function getprofile($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `manageprofile` WHERE `pid`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}

function gettax($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `tax` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}

function addtax($title, $percentage, $type, $order, $status, $ip, $getid) {
    global $db;
    if ($getid == '') {
        $link22 = FETCH_all("SELECT * FROM `tax` WHERE `title`=?", $title);
        if ($link22['title'] == '') {

            $resa = $db->prepare("INSERT INTO `tax` ( `title`, `percentage`,`type`,`order`, `status`, `ip`, `updated_by`) VALUES(?,?,?,?,?,?,?)");
            $resa->execute(array($title, $percentage, $type, $order, $status, $ip, $_SESSION['ALOID']));
            
            $id=$db->lastInsertId();
            $htry = $db->prepare("INSERT INTO `history` (`page`,`pageid`,`action`,`userid`,`ip`,`actionid`) VALUES (?,?,?,?,?,?)");
            $htry->execute(array('Tax Mgmt', 42, 'INSERT', $_SESSION['ALOID'], $ip, $id));
            
            $res = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><h4><i class="icon fa fa-check"></i>Successfully Inserted</h4></div>';
        } else {
            $res = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><h4><i class="icon fa fa-close"></i>Title already exists!</h4></div>';
        }
    } else {
        $link22 = FETCH_all("SELECT * FROM `tax` WHERE `title`=? AND `id`!=?", $title, $getid);
        if ($link22['title'] == '') {
            $resa = $db->prepare("UPDATE `tax` SET `title`=?,`type`=?,`percentage`=?,`order`=?, `status`=?, `ip`=?, `updated_by`=? WHERE `id`=?");
            $resa->execute(array(trim($title),trim($type), trim($percentage), trim($order), trim($status), trim($ip), $_SESSION['ALOID'], $getid));
			
            $htry = $db->prepare("INSERT INTO `history` (`page`,`pageid`,`action`,`userid`,`ip`,`actionid`) VALUES (?,?,?,?,?,?)");
            $htry->execute(array('Tax Mgmt', 42, 'UPDATE', $_SESSION['ALOID'], $ip, $id));

            $res = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button><h4><i class="icon fa fa-check"></i>Successfully Updated</h4></div>';
        } else {
            $res = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><h4><i class="icon fa fa-close"></i>Title already exists!</h4></div>';
        }
    }
    return $res;
}

function deltax($a) {
    $b = str_replace(".", ",", $a);
    $b = explode(",", $b);
    foreach ($b as $c) {
        global $db;
        $get = $db->prepare("DELETE FROM `tax` WHERE `id` = ? ");
        $get->execute(array($c));
    }
    $res = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><h4><i class="icon fa fa-close"></i> Successfully Deleted!</h4></div>';
    return $res;
}


function words($ammt) {
    $number = $ammt;
    $no = round($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? ucfirst($words[$number]) .
                    " " . $digits[$counter] . $plural . " " . $hundred :
                    ucfirst($words[floor($number / 10) * 10])
                    . " " . ucfirst($words[$number % 10]) . " "
                    . ucfirst($digits[$counter]) . $plural . " " . $hundred;
        } else
            $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    if ($point < 19) {
        $points = ($point) ? " " . ucfirst($words[$point]) : '';
    } else {
        $points = ($point) ? " " . ucfirst($words[floor($point / 10) * 10]) . " " . ucfirst($words[$point = $point % 10]) : '';
    }

    $res .= $result . "";
    if ($points != '') {
        $res .= " and " . $points . ' Paisa Only';
    } else {
        $res .= ' Only';
    }
    return $res;
}

function compress_image($destination_url, $quality) {

    $info = getimagesize($destination_url);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($destination_url);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($destination_url);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($destination_url);

    imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}

function show_toast($type, $msg) {
    return '
    <script id="thissc">
        window.onload = function(){
            toastr.' . $type . '("' . $msg . '");
                $("#thissc").remove();
        }
        
    </script>';
}

function getTable($table, $auto_id, $id) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `$table` WHERE `$auto_id`=?");
    $get1->execute(array($id));
    $get = $get1->fetch();
    return $get;
}

function getValue($table, $auto_id, $id, $field) {
    global $db;
    $get1 = FETCH_all("SELECT * FROM `$table` WHERE `$auto_id`=?", $id);
    $get = $get1[$field];
    return $get;
}

?>