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
$row = $checkvaliduser->fetch(PDO::FETCH_ASSOC);
$notification = $db->prepare("SELECT * FROM `notification` WHERE `to`='".$row['id']."' AND `read_status`='0' ORDER BY `id` DESC");
$notification->execute();
$notificationnum = $notification->rowCount();
if($notificationnum>0)
{
    while ($notificationrow = $notification->fetch(PDO::FETCH_ASSOC)){
        
        if($notificationrow['title']=='DROPTAXI - Admin Accept your Quote' || $notificationrow['title']=='DROPTAXI - Your Trip is End') {
      
            $notid=$notificationrow['booking_id'];
        }
        else
        {
            $notid=$notificationrow['id'];
        }
  $ps_item1[]=array("notification_id"=>$notid,
   "notification_title"=>$notificationrow['title'],
   "notification_message"=>$notificationrow['message'],
   "date"=>date('d-m-Y g:i a',strtotime($notificationrow['date']))
   );   
    }
    http_response_code(200);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "true","error"=>"false","message" => "Listing","data"=>$ps_item1)
    );     
}
else
{
   http_response_code(200);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "false","error"=>"true","message" => "No Records Found.")
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
