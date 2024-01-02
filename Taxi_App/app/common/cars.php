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

$lmt='';
$offset=$data->offset;
$limit=$data->limit;

$offsetval = ($offset - 1) * $limit;
            
if($offset=='1')
{
$lmt .= ' LIMIT 0,'.$limit;    
}
else
{
$lm=($offset-1)*$limit;
$lmt .= ' LIMIT '.$lm.','.$limit;   
}

if($checknum>0)
{ 
if($data->type!='') {
$stmt = $db->prepare("SELECT * FROM `cars` WHERE `status`='1' AND `type`='".$data->type."' ORDER BY `id` ASC $lmt");	    
}   
else
{
$stmt = $db->prepare("SELECT * FROM `cars` WHERE `status`='1' ORDER BY `id` ASC $lmt");	
}
$stmt->execute();
$checknum1 = $stmt->rowCount();
if($checknum1>0)
{
    $ps_arr["success"]="true";
    $ps_arr["error"]="false";
    
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
extract($row);

if($image!=''){
    $cimg=$sitename.'images/cars/'.$image;
}
else
{
   $cimg=''; 
}

$ami=explode(',',$amenities);
if(in_array('AC',$ami)){
  $acstatus='Yes';  
}
else
{
  $acstatus='No';    
}

if($data->trip_type=='oneway'){
$kmprice= $per_km;
$driver_charge=$beta_fee;
}
else
{
 $kmprice= $round_per_km;
$driver_charge=$round_beta_fee;   
}
    $ps_item1[]=array(
            "carid"=>$id,"cartype"=>getcartype('name',$type),"carname" => $name,"image" => $cimg,"sit_count"=>$sit_count,"ac_status"=>$acstatus,"km_per_price"=>$kmprice,"driver_charge"=>$driver_charge
        );
    
}

 if(count($ps_item1)>0) { 
        http_response_code(200);
  echo json_encode(
        array("success" => "true","error" => "false","cardetails" => $ps_item1)
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
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "false","message" => "Invalid Token")
    );
}  
?>
