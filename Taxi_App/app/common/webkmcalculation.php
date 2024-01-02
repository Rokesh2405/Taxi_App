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


if($data->pickup_lat!='' && $data->pickup_long!='' && $data->drop_lat!='' && $data->drop_long!='' && $data->tripType!='' && $data->carType!='')    {

    $fromLatitude = $data->pickup_lat; //'13.082881'
    $fromLongitude = $data->pickup_long; //'80.276002'
    $toLatitude = $data->drop_lat; //'9.920105'
    $toLongitude = $data->drop_long; //'78.110683'

$url = "https://apis.mapmyindia.com/advancedmaps/v1/" . REST_API_KEY . "/distance_matrix/driving/" . $fromLongitude . "," . $fromLatitude . ";" . $toLongitude . "," . $toLatitude . "?rtype=0&region=ind";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($curl);

    curl_close($curl);

    if (trim($result) != '') {
        $data['status'] = 'success';
        $distance = round(json_decode($result)->results->distances[0][1] / 1000);
    } else {
        $data['status'] = 'fail';
        $data['data'] = '';
        echo json_encode($data);
        exit;
    }

    $trip_type =$data->tripType;
    $carType = $data->carType;
    $days = 1;

    $query = mysqli_query($con, "SELECT * FROM price_list WHERE car_type='" . $carType . "' AND trip_type='" . $trip_type . "'");
    $result = mysqli_fetch_array($query);

    $query1 = mysqli_query($con, "SELECT * FROM per_day_km");
    $per_day_km = mysqli_fetch_array($query1);

    // echo "<pre>";print_r($per_day_km);exit;

    if ($trip_type == 'oneway') {
        $per_day_target = $per_day_km['oneway'];
    } else if ($trip_type == 'round') {
        $per_day_target = $per_day_km['roundtrip'] * $days;
        $distance = $distance * 2;
    }

    if ($distance > $per_day_target) {
        $data['base_fare'] = $distance * $result['per_km'];
    } else {
        $data['base_fare'] = $per_day_target * $result['per_km'];
        // $data['base_fare'] = ($per_day_target / $days) * $result['per_km'];
    }

    $data['per_Day_target'] = $per_day_target;
    $data['distance'] = $distance;
    $data['bataFee'] = $result['bata_fee'] * $days;
    $data['perKm'] = $result['per_km'];
    $data['total_price'] = $data['base_fare'] + $data['bataFee'];

    $_SESSION['droptaximadurai']['fares'] = $data;

    echo json_encode($data);

 // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "true","error"=>"false","calculation"=>$data)
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
