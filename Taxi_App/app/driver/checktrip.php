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

 

if($data->driver_id!='') {	
$chktrip = $db->prepare("SELECT * FROM `booking` WHERE `driver_id`='".$data->driver_id."'  AND `start_km`!='' AND `end_km` IS NULL");
$chktrip->execute();
$checknum11 = $chktrip->rowCount();
	if($checknum11>0)
	{
		$chktriprow = $chktrip->fetch(PDO::FETCH_ASSOC);
		http_response_code(200);
 
    // tell the user
echo json_encode(array("success" => "true", "error" => "false","booking_id"=>$chktriprow['id']));       

	}
	else
{
		http_response_code(200);
 
    // tell the user
echo json_encode(array("success" => "false", "error" => "true"));     
}
}
else
{
		http_response_code(200);
 
    // tell the user
echo json_encode(array("success" => "false", "error" => "true"));     
}


?>
