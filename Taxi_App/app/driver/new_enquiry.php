<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
$token= "";

// Code for enable getallheaders function 
// error_reporting(1);
// ini_set('display_errors','1');
// error_reporting(E_ALL);

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

$stmt = $db->prepare("SELECT A.`trip_time`,A.`customer_booking_amount`,A.`id`,B.`id` AS `notification_id`, A.`register_id`,B.`date`,A.`pickup_address`,A.`drop_address`,A.`car_id`,A.`triptype`,A.`quote_amount` FROM `booking` AS A,`notification` AS B WHERE A.`id`=B.`booking_id` AND B.`to`='".$data->driver_id."' AND B.`confirm_status`='0' AND B.`accept_status`='0' AND B.`cancel_status`='0' GROUP BY A.`id` ORDER BY A.`id` DESC ");	
$stmt->execute();
$checknum1 = $stmt->rowCount();

if($checknum1>0)
{
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
$quaarray=array();
$findquote = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$row['id']."' AND `driver_charge`!='' AND `from`='".$data->driver_id."' ORDER BY `id` ASC");
$findquote->execute();
$quotenum11 = $findquote->rowCount();  

if($quotenum11>0){
  $quoterow = $findquote->fetch(PDO::FETCH_ASSOC);
  $quaarray[]=array("notification_id"=>$quoterow['id'],"booking_id"=>$quoterow['booking_id'],"quote_amount"=>$quoterow['driver_charge']);  
}   

// $checkvaliduser11 = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$row['id']."' AND `from`='".$data->driver_id."' AND `confirm_status`='1' ORDER BY `id` ASC");
$checkvaliduser11 = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$row['id']."' AND `confirm_status`='1' ORDER BY `id` ASC");
$checkvaliduser11->execute();
$checknum11 = $checkvaliduser11->rowCount();
 
if($checknum11==0) { 
    
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

if(getplace('place',$row['pickup_address'])!='')
{	
$paddress=getplace('place',$row['pickup_address']);
}
else
{
$paddress=$row['pickup_address'];
}

if(getplace('place',$row['drop_address']))
{
$daddress=getplace('place',$row['drop_address']);
}
else
{
$daddress=$row['drop_address'];
}
	
	
   $ps_item1[]=array("quotes"=>$quaarray,"notification_id"=>$row['notification_id'],
   "booking_id"=>$row['id'],
   "cus_name"=>getuser('name',$row['register_id']),
   "cus_mobile"=>"",
   "pickup_address"=>$paddress,
   "drop_address"=>$daddress,
   "car_name"=>getcardetails('name',$row['car_id']),
   "car_image"=>$cimg,
   "triptype"=>$row['triptype'],
   "km_per_price"=>$kmperprice,
   "rental_amount"=>$row['customer_booking_amount'],
   "date"=>date('d-m-Y',strtotime($row['date'])),
   "time"=>date('h:i a',strtotime($row['trip_time'])),
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
