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
const REST_API_KEY = 'iucwke7bnuxww2qi3kpygsnrkbboliqf';
 

if($data->distance!='')    {

$distance=$data->distance;

$trip_type =$data->tripType;
    $carType = $data->carType;
    $days = 1;
$query = $db->prepare("SELECT * FROM `price_list` WHERE car_type='" . $carType . "' AND trip_type='" . $trip_type . "' ORDER BY `id` ASC");
$query->execute();
$result1 = $query->fetch(PDO::FETCH_ASSOC);

$query1 = $db->prepare("SELECT * FROM `per_day_km` ORDER BY `id` ASC");
$query1->execute();
$per_day_km = $query1->fetch(PDO::FETCH_ASSOC);

    if ($trip_type == 'oneway') {
        $per_day_target = $per_day_km['oneway'];
    } else if ($trip_type == 'round') {
        $per_day_target = $per_day_km['roundtrip'] * $days;
        $distance = $distance * 2;
    }

    if ($distance > $per_day_target) {
        if($distance>130){
            $dis1=$distance-130;
            
          $data11['base_fare'] = round(130 * $result1['per_km']); 
          $data11['additional_fare'] = round($dis1 * $result1['per_km']); 
          
          
          $data11['totfare'] = round($distance * $result1['per_km']);   
        }
        else
        {
            $data11['additional_fare'] = round($distance * $result1['per_km']); 
          
          
          $data11['totfare'] = round($distance * $result1['per_km']);    
        }
       
    } else {
        
        if($per_day_target>130){
            $dis1=$per_day_target-130;
            
          $data11['base_fare'] = round(130 * $result1['per_km']); 
          $data11['additional_fare'] = round($dis1 * $result1['per_km']); 
          
          
          $data11['totfare'] = round($per_day_target * $result1['per_km']);   
        }
        else
        {
            $data11['base_fare'] = round($per_day_target * $result1['per_km']); 
          $data11['additional_fare']='';
          
          $data11['totfare'] = round($per_day_target * $result1['per_km']);    
        }
        
      //  $data11['base_fare'] = round($per_day_target * $result1['per_km']);
        // $data['base_fare'] = ($per_day_target / $days) * $result['per_km'];
    }

    $data11['per_Day_target'] = $per_day_target;
    $data11['distance'] = round($distance);
    $data11['bataFee'] = $result1['bata_fee'] * $days;
    $data11['perKm'] = $result1['per_km'];
    $data11['total_price'] = round($data11['totfare'] + $data11['bataFee']);
    
 
 // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "true","error"=>"false","data" => $data11)
    );
}
else
{
     http_response_code(200);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "false","error"=>"true","message" => "Data is incomplete")
    );
}

?>
