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

$checkvaliduser = $db->prepare("SELECT * FROM `notification` WHERE `type`='Admin-User' AND `read_status`='0' AND `to`='".$data->register_id."' ORDER BY `id` DESC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();
if($checknum>0)
{   
while ($row = $checkvaliduser->fetch(PDO::FETCH_ASSOC)){
    if($row['title']=='DROPTAXI - Your Trip is End' || $row['message']=='Hi, Your request received. Driver And Vehicle details will be share 3 hours before pickup time. Thank you')
    {
    $datalist[]=array("notification_id"=>$row['booking_id'],"title"=>$row['title'],
    "details"=>$row['message'],
    "time"=>date("d-m-Y g:i a",strtotime($row['date']))
    );
    }
    else
    {
     $datalist[]=array("notification_id"=>$row['id'],"title"=>$row['title'],
    "details"=>$row['message'],
    "time"=>date("d-m-Y g:i a",strtotime($row['date']))
    );
    }
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