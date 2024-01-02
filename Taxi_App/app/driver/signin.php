<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 

// get database connection
include_once '../config/database.php';

 
// instantiate product object
include_once '../objects/form.php';
include_once '../objects/functions.php';
$database = new Database();
$db = $database->getConnection();
 
$form = new Form($db);
 
// get posted data

$data = json_decode(file_get_contents("php://input"));


// make sure data is not empty
if(
    !empty($data->username) && !empty($data->password)
){
    
 $checkvaliduser = $db->prepare("SELECT * FROM `driver` WHERE `login_username`='".$data->username."' AND `login_password`='".$data->password."' ORDER BY `id` ASC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();

if($checknum>0)
{
  $row = $checkvaliduser->fetch(PDO::FETCH_ASSOC);
  if($data->device_key == $row['device_key'] || $row['device_key']=='') {
  $uquery = "UPDATE `driver` SET
                    device_key='".$data->device_key."' WHERE `login_username`='".$data->username."' AND `login_password`='".$data->password."' ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();   

  http_response_code(200);

        // tell the user
 echo json_encode(array("success" => "true", "error" => "false","registerid" => $row['id'],"token" => $row['token'],"message" => "Login Successfully"));     }
	else
	{
		 http_response_code(200);
 
        // tell the user
        echo json_encode(array("success" => "false", "error" => "true","message" => "Sorry this id already login another mobile"));
	}
	

}
else
{
  http_response_code(200);
 
        // tell the user
        echo json_encode(array("success" => "false", "error" => "true","message" => "Invalid Details"));  
}

}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("success" => "false", "error" => "true", "message" => "Unable to create user. Data is incomplete."));
}
?>