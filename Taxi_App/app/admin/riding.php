<?php
// echo "hi";
// exit;
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
error_reporting(1);
ini_set('display_errors','1');
error_reporting(E_ALL);


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

$stmt = $db->prepare("SELECT * FROM `booking` WHERE `completed_status`='1' ORDER BY `date` DESC "); 
$stmt->execute();
$checknum1 = $stmt->rowCount();
if($checknum1>0)
{
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
extract($row);

$stmt11 = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$id."' AND `driver_name`!='' "); 
$stmt11->execute();
$row11 = $stmt11->fetch(PDO::FETCH_ASSOC);


$ps_item1[]=array(
         "id"=>$id,
         "customername"=>getuser('name',$id),
         "customermobile"=>getuser('mobileno',$id),
         "drivername"=>$row11['driver_name'],
         "drivermobileno"=>$row11['driver_mobileno'],
         "pickupaddress"=>$pickup_address,
         "dropaddress"=>$drop_address,
         "pickup_time"=>$pickup_time,
         "drop_time"=>$drop_time,
        "triptype"=>$triptype,
         "date"=>date('d-m-Y',strtotime($trip_date)),
         "time"=>$trip_time,
         "tripamount"=>$quote_amount,
         "total_price"=>$final_total_amount
    );
    
 }

 if(count($ps_item1)>0) { 
        http_response_code(200);
  echo json_encode(
        array("success" => "true","error" => "false","BookingDetails" => $ps_item1)
    );
   }
   else
   {
        http_response_code(404);
    echo json_encode(
        array("success" => "false","error" => "true")
    );   
   }
  
  
}
else
{
    // set response code - 404 Not found
    http_response_code(404);
 
 
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
