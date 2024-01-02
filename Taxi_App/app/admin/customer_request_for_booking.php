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

if($checknum>0) {
$stmt = $db->prepare("SELECT * FROM `booking` WHERE `booking_status`='0' ORDER BY `id` DESC ");	    

$stmt->execute();
$checknum1 = $stmt->rowCount();
if($checknum1>0)
{
    $ps_arr["success"]="true";
    $ps_arr["error"]="false";
    
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  extract($row);
$checkvaliduser11 = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$id."' AND `type`='Admin-Driver' ORDER BY `id` ASC");
$checkvaliduser11->execute();
 $checknum11 = $checkvaliduser11->rowCount();
 
if($checknum11==0) { 
      

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
   }
   else
   {
      $kmperprice=getcardetails('round_per_km',$car_id);  
   }
    $ps_item1[]=array(
            "booking_id"=>$id,
            "cus_name" => getuser('name',$register_id),
            "cus_mobileno" => getuser('mobileno',$register_id),
            "pickup_address"=>$pickup_address,
            "drop_address"=>$drop_address,
            "triptype" => $triptype,
            "car_id" => $car_id,
            "car_name" => getcardetails('name',$car_id),
            "car_image" => $img,
            "km_per_price" => $kmperprice,
            "rental_amount" => $customer_booking_amount,
            "trip_date"=>$trip_date,
            "trip_time"=>$trip_time
            );
}
}

 if(count($ps_item1)>0) { 
        http_response_code(200);
  echo json_encode(
        array("success" => "true","error" => "false","message"=>"Listing","Bookingdetails" => $ps_item1)
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
    // set response code - 404 Not found
      http_response_code(200);
     echo json_encode(
        array("success" => "true","error" => "false","message"=>"No Records Found")
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
