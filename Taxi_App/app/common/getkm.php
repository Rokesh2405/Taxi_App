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
    
     $carid = $data->carid;
    $days = 1;
$query = $db->prepare("SELECT * FROM `cars` WHERE id='" . $carid . "' ORDER BY `id` ASC");
$query->execute();
$result1 = $query->fetch(PDO::FETCH_ASSOC);

    if ($trip_type == 'oneway') {
        $per_day_target = $result1['base_fare_km'];
    } else if ($trip_type == 'round') {
        $per_day_target = $result1['round_base_fare_km'];
    }
 if ($trip_type == 'oneway') {
        if($distance>$result1['base_fare_km']){
            $dis1=$distance-$result1['base_fare_km'];
            
          $data11['base_fare'] = round($result1['base_fare']);
          $data11['additional_distance'] = round($dis1); 
          $data11['additional_fare'] = round($dis1 * $result1['per_km']); 
         // $data11['totfare'] = round($distance * $result1['base_fare']);   
        }
        else
        {
            $data11['base_fare'] = round($result1['base_fare']);
          $data11['additional_distance'] = ''; 
            $data11['additional_fare'] = ''; 
           // $data11['totfare'] = round($distance * $result1['base_fare']);    
        }


}
else
{
    $totdays=getdays($data->trip_date,$data->drop_date)+1; 
    
      if($distance>($result1['round_base_fare_km']*$totdays)){
          $dis1=$distance-($result1['round_base_fare_km']*$totdays);
          $data11['base_fare'] = $result1['round_base_fare']*$totdays;
          $data11['additional_distance'] = round($dis1); 
          $data11['additional_fare'] = round($dis1 * $result1['round_per_km']); 
         // $data11['totfare'] = round($distance * $result1['round_base_fare']);   
        }
        else
        {
            $data11['base_fare']=$result1['round_base_fare']*$totdays;
            $data11['additional_distance'] = ''; 
            $data11['additional_fare'] = ''; 
           // $data11['totfare'] = round($distance * $result1['round_base_fare']);    
        }
}
    $data11['per_Day_target'] = $per_day_target;
    $data11['distance'] = round($distance);
      $totdays=getdays($data->trip_date,$data->drop_date)+1; 
     if ($trip_type == 'oneway') {
    $data11['bataFee'] = $result1['beta_fee'];     
   // $data11['bataFee'] = $result1['beta_fee'] * $days;
    $data11['perKm'] = $result1['per_km'];
     }
     else
     {
         $data11['bataFee'] = $result1['round_beta_fee'] * $totdays;
    // $data11['bataFee'] = $result1['round_beta_fee'] * $days;
    $data11['perKm'] = $result1['round_per_km'];     
     }

   if($totdays>0 && $data->trip_date!='' && $data->drop_date!='') { 
        $data11['total_days']=$totdays;
        $data11['per_day']= round($data11['base_fare'])*$totdays;
    $data11['total_price'] = round($data11['additional_fare']) + $data11['base_fare'] + ($data11['bataFee']);
   }
   else
   {
   $data11['total_days']=0;
   $data11['per_day']=0;
   $data11['total_price'] = round($data11['additional_fare']) + round($data11['base_fare'] + $data11['bataFee']);    
   }
    
 
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
