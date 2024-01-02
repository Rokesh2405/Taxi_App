<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 


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
include_once '../forms/notification.php';
// instantiate database and patient object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$form = new Form($db);


$data = json_decode(file_get_contents("php://input"));

 
$checkvaliduser = $db->prepare("SELECT * FROM `register` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();

if($checknum>0) {
if(
    !empty($data->notification_id)
){
    
$driverdetails = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".getnotification('booking_id',$data->notification_id)."' AND `driver_name`!='' ORDER BY `id` ASC");
$driverdetails->execute();
$driverrow = $driverdetails->fetch(PDO::FETCH_ASSOC);

//update read status to driver
$readquery = "UPDATE `notification` SET `read_status`='1' WHERE `from`='admin' AND `booking_id`='".getnotification('booking_id',$data->notification_id)."' AND `title`='DROPTAXI - Your Trip is End' ";
$readstmt = $db->prepare($readquery);
$readstmt->execute();
//update read status

    //update read status to customer
$readquery = "UPDATE `notification` SET `read_status`='1' WHERE `booking_id`='".getnotification('booking_id',$data->notification_id)."' AND `from`='admin' AND `title`='DROPTAXI - Your Trip is End' ";
$readstmt = $db->prepare($readquery);
$readstmt->execute();
//update read status

 http_response_code(200);

if(is_numeric(getplace('place',getbookingdetails('pickup_address',getnotification('booking_id',$data->notification_id)))))
{
$paddress=getplace('place',getbookingdetails('pickup_address',getnotification('booking_id',$data->notification_id)));
}
else
{
$paddress=getbookingdetails('pickup_address',getnotification('booking_id',$data->notification_id));
}

if(is_numeric(getplace('place',getbookingdetails('drop_address',getnotification('booking_id',$data->notification_id)))))
{
$daddress=getplace('place',getbookingdetails('drop_address',getnotification('booking_id',$data->notification_id)));
}
else
{
$daddress=getbookingdetails('drop_address',getnotification('booking_id',$data->notification_id));
}
	
    // tell the user
    echo json_encode(array(
        "success" => "true", 
        "error" => "false",
        "cutomer_name"=>getuser('name',getbookingdetails('register_id',getnotification('booking_id',$data->notification_id))),
        "pickup_address"=>$paddress,
        "drop_address"=>$daddress,
        "driver_name" => $driverrow['driver_name'],
"base_fare"=> getbookingdetails('base_fare',getnotification('booking_id',$data->notification_id)),
"additional_distance"=> getbookingdetails('additional_distance',getnotification('booking_id',$data->notification_id)),
"additional_fare"=> getbookingdetails('additional_fare',getnotification('booking_id',$data->notification_id)),
"per_Day_target"=> getbookingdetails('per_Day_target',getnotification('booking_id',$data->notification_id)),
"distance"=> getbookingdetails('distance',getnotification('booking_id',$data->notification_id)),
"bataFee"=> getbookingdetails('bataFee',getnotification('booking_id',$data->notification_id)),
"perKm"=> getbookingdetails('perKm',getnotification('booking_id',$data->notification_id)),
"total_price"=> getbookingdetails('total_price',getnotification('booking_id',$data->notification_id)),
"paid_amount"=> getbookingdetails('paid_amount',getnotification('booking_id',$data->notification_id)),
"balance_amount"=> getbookingdetails('balance_amount',getnotification('booking_id',$data->notification_id)),
"waiting_charge"=> getbookingdetails('waiting_charge',getnotification('booking_id',$data->notification_id)),
"total_amount_to_pay"=> getbookingdetails('total_amount_to_pay',getnotification('booking_id',$data->notification_id)),
        "message" => "",
        ));     
     
       
}
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
