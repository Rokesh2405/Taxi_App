<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// error_reporting(0);

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

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/form.php';
include_once '../objects/functions.php';
// instantiate database and patient object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$form = new Form($db);


$data = json_decode(file_get_contents("php://input"));

 
$checkvaliduser = $db->prepare("SELECT * FROM `driver` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();

if($checknum>0)
{ 

$stmt = $db->prepare("SELECT * FROM `booking` WHERE `id`='".$data->notification_id."' ");	
$stmt->execute();
$checknum1 = $stmt->rowCount();
$ps_item1=array();
if($checknum1>0)
{
    $ps_arr["success"]="true";
    $ps_arr["error"]="false";
    
$row = $stmt->fetch(PDO::FETCH_ASSOC);

extract($row);


$driverdeta = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$data->notification_id."' AND `driver_name`!='' ");	
$driverdeta->execute();
$driverdetarow = $driverdeta->fetch(PDO::FETCH_ASSOC);

http_response_code(200);

if($drop_date!=''){
 $drpdate=date('d-m-Y',strtotime($drop_date));   
}
else
{
 $drpdate='';   
}

if(is_numeric(getplace('place',$pickup_address)))	
{
$paddress=getplace('place',$pickup_address);
}
else
{
$paddress=$pickup_address;
}
if(is_numeric(getplace('place',$drop_address)))	
{
$daddress=getplace('place',$drop_address);
}
else
{
$daddress=$drop_address;
}
	
    // tell the user no patient found
    echo json_encode(
        array("success" => "true",
        "error" => "false",
        "customer_name"=>getuser('name',$register_id),
        "customer_mobileno"=>getuser('mobileno',$register_id),
        "driver_name"=>$driverdetarow['driver_name'],
        "driver_mobileno"=>$driverdetarow['driver_mobileno'],
        "bidding_amount"=>$driverdetarow['driver_charge'],
        "vehicle_type"=>$driverdetarow['cartype'],
        "pickup_address"=>$paddress,
        "drop_address"=>$daddress,
        "trip_type"=>$triptype,
        "pickup_date"=>date('d-m-Y',strtotime($trip_date)),
        "pickup_time"=>date('h:i a',strtotime($trip_time)),
        "drop_date"=>$drpdate,
        "drop_time"=>date('h:i a',strtotime($drop_time)),
        "totalkm"=>$distance,
        "tottalprice"=>$final_total_amount,
        "rental_amount"=>$amount_from_customer
        )
    ); 
}
else
{
 http_response_code(200);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "false","error" => "true","message" => "No Records Found")
    );   
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
