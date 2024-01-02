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
    !empty($data->notification_id)
){
if(is_numeric(getplace('place',getbookingdetails('pickup_address',$data->notification_id)))) 
{
$paddress=getplace('place',getbookingdetails('pickup_address',$data->notification_id));
}
else
{
$paddress=getbookingdetails('pickup_address',$data->notification_id);
}

if(is_numeric(getplace('place',getbookingdetails('drop_address',$data->notification_id)))) 
{
$daddress=getplace('place',getbookingdetails('drop_address',$data->notification_id));
}
else
{
$daddress=getbookingdetails('drop_address',$data->notification_id);
}
 http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => "true", "error" => "false","trip_type"=>getbookingdetails('triptype',$data->notification_id),"driver_quote_amount"=>getbookingdetails('driver_charge',$data->notification_id),"cutomer_name"=>getuser('name',getbookingdetails('register_id',$data->notification_id)),"pickup_address"=>$paddress,"drop_address"=>$daddress,"message" => ""));     
     
       
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
