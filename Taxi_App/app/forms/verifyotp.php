<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 

// get database connection
include_once '../config/database.php';

 
// instantiate product object
include_once '../objects/form.php';
 
$database = new Database();
$db = $database->getConnection();
 
$form = new Form($db);
 
// get posted data

$data = json_decode(file_get_contents("php://input"));


// make sure data is not empty
if(
    !empty($data->mobileno) &&
    !empty($data->otp) &&
    !empty($data->registerid) && 
    !empty($data->type)
){
if($data->type=='register') {    
 $checkvaliduser = $db->prepare("SELECT * FROM `verifyregister` WHERE `mobileno`='".$data->mobileno."' AND  `otp`='".$data->otp."' AND  `id`='".$data->registerid."' ORDER BY `id` ASC");
$checkvaliduser->execute();
$checknum = $checkvaliduser->rowCount();

if($checknum>0)
{
$row = $checkvaliduser->fetch(PDO::FETCH_ASSOC);
if($row['otp']==$data->otp)
{
    
$query = "UPDATE `verifyregister` SET
                    otpused=1 WHERE id='".$data->registerid."'";
$stmt = $db->prepare($query);
$stmt->execute();
 
$checkvaliduser1 = $db->prepare("SELECT * FROM `register` WHERE `mobileno`='".$data->mobileno."' ORDER BY `id` ASC");
$checkvaliduser1->execute();
$checknum1 = $checkvaliduser1->rowCount();
$row1 = $checkvaliduser1->fetch(PDO::FETCH_ASSOC);

if($row1['id']=='')
{
$token=md5(uniqid(rand()));
$query1 = "INSERT INTO `register` (`name`,`emailid`,`mobileno`,`token`,`device_key`) VALUES ('".$row['name']."','".$row['emailid']."','".$data->mobileno."','".$token."','".$data->device_key."')";
$stmt1 = $db->prepare($query1);
$stmt1->execute();

$registerid = $db->lastInsertId();
}
else
{
    
$token=md5(uniqid(rand()));
$query1 = "UPDATE `register` SET `name`='".$row['name']."',`emailid`='".$row['emailid']."',`token`='".$token."' ,`device_key`='".$data->device_key."' WHERE `id`='".$row1['id']."' ";
$stmt1 = $db->prepare($query1);
$stmt1->execute();


 $registerid=$row1['id'];
 $token=$token;
}

$dquery1 = "DELETE FROM `verifyregister` WHERE `id`='".$data->registerid."' ";
$dstmt1 = $db->prepare($dquery1);
$dstmt1->execute();

 http_response_code(200);
 

        // tell the user
        echo json_encode(array("success" => "true", "error" => "false", "registerid" => $registerid,"token" => $token, "message" => "Verified Successfully.")); 

}
else
{

    
http_response_code(200);
 
        // tell the user
 echo json_encode(array("success" => "false", "error" => "true", "message" => "Invalid OTP"));       
}
}
else
{
http_response_code(200);
 
        // tell the user
 echo json_encode(array("success" => "false", "error" => "true", "message" => "Invalid OTP or Mobileno"));       
}
}
elseif($data->type=='editprofile')
{
 $checkvaliduser = $db->prepare("SELECT * FROM `register` WHERE `mobileno`='".$data->mobileno."' AND  `otp`='".$data->otp."' AND  `id`='".$data->registerid."' ORDER BY `id` ASC");
$checkvaliduser->execute();
$checknum = $checkvaliduser->rowCount();

if($checknum>0)
{
$row = $checkvaliduser->fetch(PDO::FETCH_ASSOC);
if($row['otp']==$data->otp)
{
$query = "UPDATE `register` SET
                    name='".$data->cusname."',mobileno='".$data->mobileno."',address='".$data->address."',emailid='".$data->emailid."',otpused=1,`device_key`='".$data->device_key."' WHERE id='".$data->registerid."'";
$stmt = $db->prepare($query);
$stmt->execute();

 http_response_code(200);
 

        // tell the user
        echo json_encode(array("success" => "true", "error" => "false", "registerid" => $row['id'],"token" => $row['token'], "message" => "Verified Successfully.")); 

}
else
{

    
http_response_code(200);
 
        // tell the user
 echo json_encode(array("success" => "false", "error" => "true", "message" => "Invalid OTP"));       
}
}
else
{
http_response_code(200);
 
        // tell the user
 echo json_encode(array("success" => "false", "error" => "true", "message" => "Invalid OTP or Mobileno"));       
}   
}
else
{
 $checkvaliduser = $db->prepare("SELECT * FROM `register` WHERE `mobileno`='".$data->mobileno."' AND  `otp`='".$data->otp."' AND  `id`='".$data->registerid."' ORDER BY `id` ASC");
$checkvaliduser->execute();
$checknum = $checkvaliduser->rowCount();

if($checknum>0)
{
$row = $checkvaliduser->fetch(PDO::FETCH_ASSOC);
if($row['otp']==$data->otp)
{
$query = "UPDATE `register` SET
                    otpused=1,`device_key`='".$data->device_key."' WHERE id='".$data->registerid."'";
$stmt = $db->prepare($query);
$stmt->execute();

 http_response_code(200);
 

        // tell the user
        echo json_encode(array("success" => "true", "error" => "false", "registerid" => $row['id'],"token" => $row['token'], "message" => "Verified Successfully.")); 

}
else
{

    
http_response_code(200);
 
        // tell the user
 echo json_encode(array("success" => "false", "error" => "true", "message" => "Invalid OTP"));       
}
}
else
{
http_response_code(200);
 
        // tell the user
 echo json_encode(array("success" => "false", "error" => "true", "message" => "Invalid OTP or Mobileno"));       
}   
}
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("success" => "false", "error" => "true", "message" => "Unable to create user. Data is incomplete."));
}
?>