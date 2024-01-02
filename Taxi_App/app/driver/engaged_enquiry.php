<?php // required headers
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
    !empty($data->driver_id)
){

$stmt = $db->prepare("SELECT A.`trip_time`,B.`id`,A.`register_id`,B.`date`,A.`pickup_address`,A.`drop_address`,A.`car_id`,A.`triptype`,B.`booking_id`,A.`quote_amount` FROM `booking` AS A,`notification` AS B WHERE A.`id`=B.`booking_id` AND B.`to`='".$data->driver_id."' ORDER BY A.`id` DESC ");		
$stmt->execute();
$checknum1 = $stmt->rowCount();
if($checknum1>0)
{
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
 
$stmt1 = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$row['booking_id']."' AND `driver_name`!='' ORDER BY `id` DESC ");	
$stmt1->execute();
$checknum11 = $stmt1->rowCount();
if($checknum11>0)
{
    
    if($row['triptype']=='oneway'){
       $kmperprice=getcardetails('per_km',$row['car_id']);
   }
   else
   {
      $kmperprice=getcardetails('round_per_km',$row['car_id']);  
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
	
  $ps_item1[]=array("notification_id"=>$row['id'],
   "cus_name"=>getuser('name',$row['register_id']),
   "pickup_address"=>$paddress,
   "drop_address"=>$daddress,
   "km_per_price"=>$kmperprice,
   "rental_amount"=>$row['quote_amount'],
   "date"=>date('d-m-Y',strtotime($row['date'])),
   "time"=>date('h:i a',strtotime($row['trip_time']))
   );  
}


   
}
if(count($ps_item1)>0) { 
        http_response_code(200);
  echo json_encode(
        array("success" => "true","error" => "false","message"=>"listing","bookingdetails" => $ps_item1)
    );
   }
   else
   {
        http_response_code(200);
    echo json_encode(
        array("success" => "true","error" => "false","message"=>"No Records Found")
    );   
   }
}
else
{
http_response_code(200);
 
    // tell the user
echo json_encode(array("success" => "true", "error" => "false", "message" => "No Records Found"));       
}
     
     
       
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
