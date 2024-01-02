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

 
$checkvaliduser = $db->prepare("SELECT * FROM `driver` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();

if($checknum>0) {
if(
    !empty($data->bookingid) 
){


$ckid=$data->bookingid;
	
// update as admin confirmed trip
$selectrip = $db->prepare("SELECT * FROM `booking` WHERE `id`=? ");	
$selectrip->execute($ckid);
$bkkdetails = $selectrip->fetch(PDO::FETCH_ASSOC);	
	
	
$uquery1 = "UPDATE `booking` SET
                     booking_status='0',`driver_charge`='',`driver_id`='', request_status='0', `quote_amount`='',`view_status`='0' WHERE `id`='".$ckid."' ";
$ustmt1 = $db->prepare($uquery1);
$ustmt1->execute();
	
	
	
$uquery = "INSERT INTO `cancelled_trips` (`cancel_reason`,`customer_booking_amount`,`triptype`,`register_id`, `pickup_address`, `drop_address`, `booking_km`, `car_id`, `trip_date`, `customer_paid_booking_amount`) VALUES ('".$_REQUEST['cancel_reason']."','".$bkkdetails['customer_booking_amount']."','".$bkkdetails['triptype']."','".$bkkdetails['register_id']."','".$bkkdetails['pickup_address']."','".$bkkdetails['drop_address']."','".$bkkdetails['booking_km']."','".$bkkdetails['car_id']."','".$bkkdetails['trip_date']."','".$bkkdetails['customer_paid_booking_amount']."') ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();
	
	
// update as admin confirmed trip
	
// Delete Already Assigned  Trip
$deltrip = $db->prepare("DELETE FROM `notification` WHERE `booking_id`='".$ckid."' AND `title`='DROPTAXI - Admin Assign to Drive'");	
$deltrip->execute();
$deltriprow = $deltrip->fetch(PDO::FETCH_ASSOC);
// Delete Already Assigned  Trip




   http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => "true", "error" => "false", "message" => "Cancelled Successfully."));     
     
       
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
