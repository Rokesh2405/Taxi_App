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

 

if($data->amount!='')    {

$upiid=getadminuser('upi_id','1');
$finamount=str_replace(',','',$data->amount)*(10/100);
 // set response code - 404 Not found
    http_response_code(200);
 
    // tell the user no patient found
    echo json_encode(
        array("success" => "true","error"=>"false","upiid" => $upiid,"percentage_amount"=>round($finamount))
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
