<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// error_reporting(1);
// ini_set('display_errors','1');
// error_reporting(E_ALL);
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

 
$checkvaliduser = $db->prepare("SELECT * FROM `driver` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser->execute();
$checknum = $checkvaliduser->rowCount();
$datalist=array();
if($checknum>0)
{ 
$regid=$data->register_id;
$smrow11 = $checkvaliduser->fetch(PDO::FETCH_ASSOC);
$stmt11 = $db->prepare("SELECT * FROM `booking` WHERE `total_amount_to_pay` IS NULL AND `completed_status`='0' ORDER BY `id` DESC");	
$stmt11->execute();
$checknum11 = $stmt11->rowCount();
if($checknum11>0) {
while ($row11 = $stmt11->fetch(PDO::FETCH_ASSOC)){
$stmt12 = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$row11['id']."' AND `driver_name` IS NULL AND `from`='".$smrow11['id']."' ORDER BY `id` DESC");	
$stmt12->execute();
$checknum12 = $stmt12->rowCount();
if($checknum12>0) { 

  $row113 = $stmt12->fetch(PDO::FETCH_ASSOC);
  
  
$quaarray=array();
$findquote = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$row11['id']."' AND `driver_name`!='' AND `from`='".$smrow11['id']."' ORDER BY `id` ASC");
$findquote->execute();
$quotenum11 = $findquote->rowCount();  

if($quotenum11>0){
  $quoterow = $findquote->fetch(PDO::FETCH_ASSOC);
  $quaarray[]=array("notification_id"=>$quoterow['id'],"driver_id"=>$smrow11['id'],"driver_name"=>$quoterow['driver_name'],"driver_mobileno"=>$quoterow['driver_mobileno'],"driver_carno"=>$quoterow['driver_carno'],"cartype"=>$quoterow['cartype']);  
}   



  
  if($row11['triptype']=='oneway'){
       $kmperprice=getcardetails('per_km',$row11['car_id']);
   }
   else
   {
      $kmperprice=getcardetails('round_per_km',$row11['car_id']);  
   }
   
if(getcardetails('image',$row11['car_id'])!='')
{
 $img=$sitename.'images/cars/'.getcardetails('image',$row11['car_id']);  
}
else
{
    $img='';
}

$stmt1211 = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$row11['id']."' AND `driver_charge`!='' ORDER BY `id` DESC");	
$stmt1211->execute();
$row11311 = $stmt1211->fetch(PDO::FETCH_ASSOC);

if(is_numeric(getplace('place',$row11['pickup_address']))) 
{
$paddress=getplace('place',$row11['pickup_address']);
}
else
{
$paddress=$row11['pickup_address'];
}

if(is_numeric(getplace('place',$row11['drop_address']))) 
{
$daddress=getplace('place',$row11['drop_address']);
}
else
{
$daddress=$row11['drop_address'];
}
	
$datalist[]=array("driver_details"=>$quaarray,"booking_id"=>$row11['id'],"notification_id"=>$row113['id'],"pickup_address"=>$paddress,"drop_address"=>$daddress,"pickup_date"=>date('d-m-Y',strtotime($row11['trip_date'])),"pickup_time"=>$row11['trip_time'],"customer_name"=>getuser('name',$row11['register_id']),"customer_mobileno"=>getuser('mobileno',$row11['register_id']),"car_name"=>getcardetails('name',$row11['car_id']),"car_image"=>$img,"km_per_price"=>$kmperprice,"rental_amount"=>$row11311['driver_charge']);
}

}
 http_response_code(200);
 
 $tottrips=customertrips($regid);
 
 // tell the user no patient found
    echo json_encode(
        array("success" => "true","error"=>"false","message"=>"","bookings"=>$datalist)
    );
}
else
{
     http_response_code(200);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "true","error"=>"false","message"=>"No Records Available")
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
