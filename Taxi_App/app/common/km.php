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

error_reporting(1);
ini_set('display_errors','1');
error_reporting(E_ALL);

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
 

if($data->pickup_lat!='' && $data->pickup_long!='' && $data->drop_lat!='' && $data->drop_long!='')    {


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
        $distance = json_decode($result)->results->distances[0][1] / 1000;
    } else {
        $data['status'] = 'fail';
        $data['data'] = '';
        echo json_encode($data);
        exit;
    }


    $data11['distance'] = round($distance);
   
 
 // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "true","error"=>"false","distance" => round($distance))
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
