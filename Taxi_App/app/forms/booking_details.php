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

 
$checkvaliduser = $db->prepare("SELECT * FROM `register` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();

if($checknum>0)
{ 
$stmt = $db->prepare("SELECT * FROM `booking` WHERE `id`='".$data->booking_id."' ");	
$stmt->execute();
$checknum1 = $stmt->rowCount();
$ps_item1=array();
if($checknum1>0)
{
  http_response_code(200);
 if(getcardetails('image',getbookingdetails('car_id',$data->booking_id))!='')
{
 $img=$sitename.'images/cars/'.getcardetails('image',getbookingdetails('car_id',$data->booking_id));  
}
else
{
    $img='';
}
if(getbookingdetails('drop_date',$data->booking_id)!=''){
$drop_date=date('d-m-Y',strtotime(getbookingdetails('drop_date',$data->booking_id)));
}
else
{
$drop_date='';    
}
    // tell the user no patient found
    
    
    $ami=explode(',',getcardetails('amenities',getbookingdetails('car_id',$data->booking_id)));
if(in_array('AC',$ami)){
  $acstatus='Yes';  
}
else
{
  $acstatus='No';    
}

if(getbookingdetails('triptype',$data->booking_id)=='oneway')
{
  $kmperprice= getcardetails('per_km',getbookingdetails('car_id',$data->booking_id));
   $driver_charge= getcardetails('beta_fee',getbookingdetails('car_id',$data->booking_id));
}
else
{
 $kmperprice= getcardetails('round_per_km',getbookingdetails('car_id',$data->booking_id));
   $driver_charge= getcardetails('round_beta_fee',getbookingdetails('car_id',$data->booking_id));   
}

	if(is_numeric(getbookingdetails('pickup_address',$data->booking_id)))
{
$paddress=getplace('place',getbookingdetails('pickup_address',$data->booking_id));
}
else
{
$paddress=getbookingdetails('pickup_address',$data->booking_id);
}

if(is_numeric(getbookingdetails('drop_address',$data->booking_id)))
{
$daddress=getplace('place',getbookingdetails('drop_address',$data->booking_id));
}
else
{
$daddress=getbookingdetails('drop_address',$data->booking_id);
}
	
	
    echo json_encode(
        array("success" => "true","error" => "false",
		"pickup_address" => $paddress,
        "drop_address" => $daddress,
        "booking_km" => getbookingdetails('booking_km',$data->booking_id),
        "triptype" => getbookingdetails('triptype',$data->booking_id),
        "car_name" => getcardetails('name',getbookingdetails('car_id',$data->booking_id)),
        "car_image" => $img,
        "trip_date" => date('d-m-Y',strtotime(getbookingdetails('trip_date',$data->booking_id))),
        "drop_date" => $drop_date,
        "customer_booking_amount" => getbookingdetails('customer_booking_amount',$data->booking_id),
        "customer_paid_booking_amount" => getbookingdetails('customer_paid_booking_amount',$data->booking_id),
        "kmperprice"=>$kmperprice,
        "pickuptime"=>getbookingdetails('trip_time',$data->booking_id),
        "acstatus"=>$acstatus,
        "driver_charge"=>$driver_charge,
         "sit_count"=>getcardetails('sit_count',getbookingdetails('car_id',$data->booking_id))
        )
    );      
}
else
{
 http_response_code(200);
 
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
