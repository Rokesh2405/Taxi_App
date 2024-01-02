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

 
$checkvaliduser = $db->prepare("SELECT * FROM `users` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();

if($checknum>0)
{ 

$stmt = $db->prepare("SELECT * FROM `notification` WHERE `id`='".$data->notification_id."' ");	
$stmt->execute();
$checknum1 = $stmt->rowCount();
$ps_item1=array();
if($checknum1>0)
{
    $ps_arr["success"]="true";
    $ps_arr["error"]="false";
    
$row = $stmt->fetch(PDO::FETCH_ASSOC);

extract($row);

http_response_code(200);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "true","error" => "false","notification_id"=>$id,"driver_id"=>$to,"drivername"=>getdriver('driver_name',$to),"driver_mobileno" => getdriver('driver_mobileno',$to),"licenceno" => getdriver('licence_no',$to),"car_name" =>getcardetails('name',getbookingdetails('car_id',$booking_id)),"car_type" =>getcartype('name',getcardetails('type',getbookingdetails('car_id',$booking_id))),"quoate_amount" =>getbookingdetails('quote_amount',$booking_id),"pickup_address" => getbookingdetails('pickup_address',$booking_id),"drop_address" => getbookingdetails('drop_address',$booking_id))
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
