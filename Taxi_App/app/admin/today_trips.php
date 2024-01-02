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

 
$checkvaliduser = $db->prepare("SELECT * FROM `users` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();

if($checknum>0)
{ 
$regid=$data->register_id;

$stmt11 = $db->prepare("SELECT * FROM `booking` WHERE date(`trip_date`)='".date('Y-m-d')."' ORDER BY `id` DESC");	
$stmt11->execute();
$checknum11 = $stmt11->rowCount();
if($checknum11>0) {
while ($row11 = $stmt11->fetch(PDO::FETCH_ASSOC)){

    
       if($row11['triptype']=='oneway'){
       $kmperprice=getcardetails('per_km',$row11['car_id']);
   }
   else
   {
      $kmperprice=getcardetails('round_per_km',$row11['car_id']);  
   }
   
$datalist[]=array("pickup_address"=>$row11['pickup_address'],"drop_address"=>$row11['drop_address'],"pickup_date"=>date('d-m-Y',strtotime($row11['trip_date'])),"pickup_time"=>$row11['trip_time'],"customer_name"=>getuser('name',$row11['register_id']),"customer_mobileno"=>getuser('mobileno',$row11['register_id']),"car_name"=>getcardetails('name',$row11['car_id']),"km_per_price"=>$kmperprice,"rental_amount"=>getcardetails('rental_amount',$row11['car_id']));

}
 http_response_code(200);
 
 $tottrips=customertrips($regid);
 
 // tell the user no patient found
    echo json_encode(
        array("success" => "true","error"=>"false","message"=>"listing","bookings"=>$datalist)
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
