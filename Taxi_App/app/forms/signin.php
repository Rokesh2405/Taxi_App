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
include_once '../objects/functions.php';

$database = new Database();
$db = $database->getConnection();
 
$form = new Form($db);
 
// get posted data

$data = json_decode(file_get_contents("php://input"));
require '../vendor/autoload.php';
use Twilio\Rest\Client;


// make sure data is not empty
if(
    !empty($data->mobileno)
){
    
 $checkvaliduser = $db->prepare("SELECT * FROM `register` WHERE `mobileno`='".$data->mobileno."'ORDER BY `id` ASC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();

if($checknum>0)
{
  $row = $checkvaliduser->fetch(PDO::FETCH_ASSOC);
    
    // set form property values
    $form->mobileno = $data->mobileno;
    $form->registerid = $row['id'];
$data->otp = generateRandomString();

$sms_msgv = "<#> Your Droptaxi APP code is: ".$data->otp;  

	// Your Account SID and Auth Token from twilio.com/console

$account_sid = "ACba2f1aa93a7df2547b48c0501fec30ce";
$auth_token = "9f5dd03fe496a092894569fda8d80177";

    
$twilio_number = "+14323024963";

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    '+91'.$data->mobileno,
    array(
        'from' => $twilio_number,
        'body' => $sms_msgv
    )
);
	
	
	$query = "UPDATE `register` SET
                    otp='".$data->otp."',mobileno='".$data->mobileno."',device_key='".$data->device_key."' WHERE id='".$row['id']."'";
$stmt = $db->prepare($query);
$stmt->execute();
 
  http_response_code(200);

        // tell the user
 echo json_encode(array("success" => "true", "error" => "false","registerid" => $row['id'],"token" => $row['token'],"otp"=>$data->otp,"message" => "OTP Send to your Mobileno"));    
	
	
}
else
{
  http_response_code(200);
 
        // tell the user
        echo json_encode(array("success" => "false", "error" => "true","message" => "Invalid Details"));  
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