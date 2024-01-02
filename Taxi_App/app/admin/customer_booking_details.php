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

$checkvaliduser1 = $db->prepare("SELECT * FROM `driver` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser1->execute();
 $checknum1 = $checkvaliduser1->rowCount();
 
if($checknum>0 || $checknum1>0) {
    
    $driverdetails = $checkvaliduser1->fetch(PDO::FETCH_ASSOC);
    
    
    if($data->notification_id!='') {
    $booking_id=getnotification('booking_id',$data->notification_id);
    }
    else
    {
     $booking_id=$data->booking_id;   
    }
  
$stmt = $db->prepare("SELECT * FROM `booking` WHERE `id`='".$booking_id."' ORDER BY `id` ASC ");	    

$stmt->execute();
$checknum1 = $stmt->rowCount();
if($checknum1>0)
{
    $ps_arr["success"]="true";
    $ps_arr["error"]="false";
    
$row = $stmt->fetch(PDO::FETCH_ASSOC);
       
        extract($row);

if(getcardetails('image',$car_id)!='')
{
 $img=$sitename.'images/cars/'.getcardetails('image',$car_id);  
}
else
{
    $img='';
}
   
   if($triptype=='oneway'){
       $kmperprice=getcardetails('per_km',$car_id);
       $limitkmperprice=getcardetails('base_fare_km',$car_id);
   }
   else
   {
      $kmperprice=getcardetails('round_per_km',$car_id);  
      $limitkmperprice=getcardetails('round_base_fare_km',$car_id);
   }

    if($checknum>0) {
       $qamount=$customer_booking_amount;
   }
   else {
       $qamount=$customer_booking_amount;
   }
   
   $quaarray=array();
  
$findquote = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$booking_id."' AND `driver_charge`!='' AND `from`='".$driverdetails['id']."' ORDER BY `id` ASC");
$findquote->execute();
$quotenum11 = $findquote->rowCount();  

if($quotenum11>0){
  $quoterow = $findquote->fetch(PDO::FETCH_ASSOC);
  $quaarray[]=array("notification_id"=>$quoterow['id'],"booking_id"=>$quoterow['booking_id'],"quote_amount"=>$quoterow['driver_charge']);  
$cartype=$quoterow['cartype'];
    
} 
else
{
$cartype=getcardetails('name',$car_id);    
}
if($drop_date!='') {
$ddate=date('d-m-Y',strtotime($drop_date));    
}
else
{
$ddate='';    
}

if(is_numeric(getplace('place',$pickup_address)))  
{
$paddress=getplace('place',$pickup_address);
}
else
{
$paddress=$pickup_address;
}

if(is_numeric(getplace('place',$drop_address))) 
{
$daddress=getplace('place',$drop_address);
}
else
{
$daddress=$drop_address;
}
	
	http_response_code(200);
      echo json_encode(
        array("success" => "true","error" => "false",
        "driver_quotes"=>$quaarray, 
        "booking_id"=>$id,
            "cus_name" => getuser('name',$register_id),
            "cus_mobileno" => getuser('mobileno',$register_id),
            "pickup_address"=>$paddress,
            "drop_address"=>$daddress,
            "triptype" => $triptype,
            "car_id" => $car_id,
            "car_name" => getcardetails('name',$car_id),
            "car_image" => $img,
            "km_per_price" => $kmperprice,
            "booking_km" => $booking_km,
            "limited_km" => $limitkmperprice,
            "rental_amount" => $qamount,
            "vehicle_type" => $cartype,
            "min_driver_charge" => getadminuser('min_driver_charge',1),
            "trip_date"=>date('d-m-Y',strtotime($trip_date)),
            "trip_time"=>date('h:i a',strtotime($trip_time)),
            "drop_date"=>$ddate
    ));
  
  
  
}
else
{
    // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "true","error" => "false","message" => "No Records Found")
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
