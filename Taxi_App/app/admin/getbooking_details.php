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

 
$checkvaliduser = $db->prepare("SELECT * FROM `users` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();

if($checknum>0) {
    
    $booking_id = $data->booking_id;
    
    if(getcardetails('image',getbookingdetails('car_id',$booking_id))!='')
{
 $img=$sitename.'images/cars/'.getcardetails('image',getbookingdetails('car_id',$booking_id));  
}
else
{
    $img='';
}

$stmt = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$data->booking_id."' AND `driver_name`!='' ");	
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
extract($row);

http_response_code(200);
if(getbookingdetails('total_amount_to_pay',$booking_id)!='') { 
$reportpath=$sitename.'MPDF/'.$booking_id.'/invoice.htm';
            $cURLConnection = curl_init($reportpath);
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

$bill = curl_exec($cURLConnection);
$download_bill=$sitename.'MPDF/'.$booking_id.'/dinvoice.htm';
echo json_encode(
array("success" => "true","error" => "false",
"booking_id"=>$booking_id,
"car_image"=>$img,
"cutomer_name"=>getuser('name',getbookingdetails('register_id',$booking_id)),
"drivername"=>$driver_name,
"driver_mobileno" =>$driver_mobileno,
"driver_carno" => $driver_carno,
"cartype" =>$cartype,
"pickup_address" => getbookingdetails('pickup_address',$booking_id),
"drop_address" => getbookingdetails('drop_address',$booking_id),
"base_fare"=> getbookingdetails('base_fare',$booking_id),
"additional_distance"=> getbookingdetails('additional_distance',$booking_id),
"additional_fare"=> getbookingdetails('additional_fare',$booking_id),
"per_Day_target"=> getbookingdetails('per_Day_target',$booking_id),
"distance"=> getbookingdetails('distance',$booking_id),
"bataFee"=> getbookingdetails('bataFee',$booking_id),
"perKm"=> getbookingdetails('perKm',$booking_id),
"total_price"=> getbookingdetails('total_price',$booking_id),
"paid_amount"=> getbookingdetails('paid_amount',$booking_id),
"balance_amount"=> getbookingdetails('balance_amount',$booking_id),
"waiting_charge"=> getbookingdetails('waiting_charge',$booking_id),
"total_amount_to_pay"=> getbookingdetails('total_amount_to_pay',$booking_id),
"per_hour_waiting_charge"=>getadminuser('waiting_charge','1'),
"waiting_hours"=>getbookingdetails('waiting_hours',$booking_id),
"bill"=>$bill,
"download_pdf"=>$download_bill,
        "message" => ""
    )); 
       curl_close($cURLConnection);
            
}
else
{
    // tell the user no patient found
    echo json_encode(
        array("success" => "true","error" => "false",
          "notification_id"=>$id,
          "booking_id"=>$booking_id,
          "car_image"=>$img,
          "otp"=>$otp,
          "drivername"=>$driver_name,
          "driver_mobileno" =>$driver_mobileno,
          "driver_carno" => $driver_carno,
          "cartype" =>$cartype,
          "pickup_address" => getbookingdetails('pickup_address',$booking_id),
          "drop_address" => getbookingdetails('drop_address',$booking_id),
          "base_fare"=> "",
"additional_distance"=> "",
"additional_fare"=> "",
"per_Day_target"=> "",
"distance"=> "",
"bataFee"=> "",
"perKm"=> "",
"total_price"=> "",
"paid_amount"=> "",
"balance_amount"=> "",
"waiting_charge"=> "",
"total_amount_to_pay"=> "",
"bill"=>"",
"waiting_hours"=>"",
"per_hour_waiting_charge"=>"",
"message" => ""
    )); 
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
