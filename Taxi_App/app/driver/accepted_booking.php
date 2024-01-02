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
    !empty($data->driver_id)
){

//$stmt = $db->prepare("SELECT * FROM `booking` WHERE `amount_from_customer` IS NULL ORDER BY `id` DESC ");	
$stmt = $db->prepare("SELECT * FROM `booking` WHERE `driver_id`='".$data->driver_id."' AND `completed_status`=1 AND `total_price` IS NULL ORDER BY `id` DESC ");	
$stmt->execute();
$checknum1 = $stmt->rowCount();
if($checknum1>0)
{
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
$nostmt = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$row['id']."'  AND `driver_name`!='' ORDER BY `id` DESC ");	
$nostmt->execute();
$nochecknum1 = $nostmt->rowCount();
if($nochecknum1>0)
{	
if(getcardetails('image',$row['car_id'])!=''){
    $cimg=$sitename.'images/cars/'.getcardetails('image',$row['car_id']);
}
else
{
   $cimg=''; 
}

    
    if($row['triptype']=='oneway'){
       $kmperprice=getcardetails('per_km',$row['car_id']);
   }
   else
   {
      $kmperprice=getcardetails('round_per_km',$row['car_id']);  
   }
   
   
$query = $db->prepare("SELECT * FROM `price_list` WHERE car_type='" .getcardetails('name',$row['car_id']). "' AND trip_type='" .$row['triptype']. "' ORDER BY `id` ASC");
$query->execute();
$result1 = $query->fetch(PDO::FETCH_ASSOC);


$stmt1121 = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$row['id']."' AND `confirm_status`='1' ORDER BY `id` DESC");	
$stmt1121->execute();
$row1121 = $stmt1121->fetch(PDO::FETCH_ASSOC);
// if($row1121['id']!='') { 
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
	
   $ps_item1[]=array("notification_id"=>$row1121['id'],"booking_id"=>$row['id'],
   "cus_name"=>getuser('name',$row['register_id']),
   "pickup_address"=>$paddress,
   "drop_address"=>$daddress,
   "rental_amount"=>$row['customer_booking_amount'],
   "kmprice"=>$kmperprice,
	"vehicletype"=>getcardetails('name',$row['car_id']),
	"triptype"=>$row['triptype'],
   "car_image"=>$cimg,
    "date"=>date('d-m-Y',strtotime($row['date'])),
    "time"=>date('h:i a',strtotime($row['trip_time']
   ))
   );
//}



}
}
if(count($ps_item1)>0) { 
        http_response_code(200);
  echo json_encode(
        array("success" => "true","error" => "false","message"=>"","bookingdetails" => $ps_item1)
    );
   }
   else
   {
        http_response_code(200);
    echo json_encode(
        array("success" => "true","error" => "false","message"=>"No Records Found.","bookingdetails" => array())
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
