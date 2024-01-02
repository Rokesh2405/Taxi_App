<?php
// echo "hi";
// exit;
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 $sitename = "http://localhost:8080/droptaxi/api";
error_reporting(0);

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

if(
    !empty($data->userid)
){
    
$stmt = $db->prepare("SELECT A.`id`,A.`pickup_address`,A.`drop_address` FROM `booking` AS A, `notification` AS B WHERE A.`id`=B.`booking_id` AND B.`confirm_status`='1' AND `completed_status`='0' AND `accept_status`='0'");        

$stmt->execute();
$checknum1 = $stmt->rowCount();
if($checknum1>0)
{
//   {
// "success" : "true",
// "error" : "false",
// "details" : [
// {
// "pickupaddress" : "RS nagar,Keelathoppu,Madurai",
// "dropaddress" : "Kumarampalayam,Kayathaar,Madurai",
// "customername" : "Harish",
// "km_price" : "10"
    //SELECT A.`id`,A.`pickup_address`,A.`drop_address` FROM `booking` AS A, `notification` AS B WHERE A.`id`=B.`booking_id` AND B.`confirm_status`='1' AND `completed_status`='0' GROUP BY A.`id`
// "rendalamount" "2355",
// "mobileno" : "98878786"
// }  
    
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
extract($row);

    $ps_item1[]=array(
            "pickupaddress"=>$pickup_address,
            "dropaddress"=>$drop_address,
            "customername"=>getregister('name',$id),
            "km_price"=>getcardetails('km_per_price',$id),
            "rendalamount"=>getcardetails('rental_amount',$id),
            "customermobile"=>getregister('mobileno',$id)
        );
    
 }

 if(count($ps_item1)>0) { 
        http_response_code(200);
  echo json_encode(
        array("success" => "true","error" => "false","confirm Details" => $ps_item1)
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
