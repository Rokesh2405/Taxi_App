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
include_once 'notification.php';
$database = new Database();
$db = $database->getConnection();
 
$form = new Form($db);
 
// get posted data

$data = json_decode(file_get_contents("php://input"));

$token= "";

// Code for enable getallheaders function 


if (!function_exists('getallheaders')) {
    function getallheaders() {
    $headers = [];
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
    return $headers;
    }
}

// Code for enable getallheaders function 


foreach(getallheaders() as $name => $value)
{
 if($name=="Token")
 {
 $token=$value;    
 }
}
$checkvaliduser11 = $db->prepare("SELECT * FROM `register` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser11->execute();
 $checknum11 = $checkvaliduser11->rowCount();
 if($checknum11>0) {
// make sure data is not empty
if(
    !empty($data->register_id)
){
$datalist=array();
$checkvaliduser = $db->prepare("SELECT * FROM `booking` WHERE `register_id`='".$data->register_id."' ORDER BY `id` DESC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();
if($checknum>0)
{   
while ($row = $checkvaliduser->fetch(PDO::FETCH_ASSOC)){
$bbcheckvaliduser = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$row['id']."' AND `confirm_status`='1' AND `driver_name`!='' ORDER BY `id` DESC");
 //$bbcheckvaliduser = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$row['id']."'  AND `driver_name`!='' ORDER BY `id` DESC");	
	//$bbcheckvaliduser = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$row['id']."' AND (`confirm_status`!='1' OR `confirm_status` IS NULL) ORDER BY `id` DESC");
	
$bbcheckvaliduser->execute();
$bbchecknum = $bbcheckvaliduser->rowCount();
	if($bbchecknum>0){
$bbrow = $bbcheckvaliduser->fetch(PDO::FETCH_ASSOC);
		$nid=$bbrow['id'];
	}
	else
	{
		$nid='';
	}
if(is_numeric(getplace('place',$row['pickup_address'])))
{
$paddress=getplace('place',$row['pickup_address']);
}
else
{
$paddress=$row['pickup_address'];
}
if(is_numeric(getplace('place',$row['drop_address'])))
{
$daddress=getplace('place',$row['drop_address']);
}
else
{
$daddress=$row['drop_address'];
}
	
 //if($bbchecknum>0) {
    $datalist[]=array("notification_id"=>$nid,
	"pickupaddress"=>$paddress,
    "dropaddress"=>$daddress,
    "triptype"=>$row['triptype'],
    "trip_date"=>date('d-M-Y',strtotime($row['trip_date'])),
    "trip_time"=>date('h:i a',strtotime($row['trip_time'])),
    "tripamount"=>$row['customer_booking_amount']
    );
 //}
 
}
  http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => "true", "error" => "false", "data" => $datalist));
}
else{
 
    // set response code - 400 bad request
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => "false", "error" => "true", "message" => "No Records Found"));
}
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("success" => "false", "error" => "true", "message" => "Unable to create user. Data is incomplete."));
}
}
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "false","message" => "Invalid Token")
    );
}  
?>