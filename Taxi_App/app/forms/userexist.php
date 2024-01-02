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
 require '../aws/vendor/autoload.php';
$database = new Database();
$db = $database->getConnection();
 
$form = new Form($db);
 
// get posted data

$data = json_decode(file_get_contents("php://input"));


// make sure data is not empty
if(
    !empty($data->name) && !empty($data->registerid)
){
    
 $checkvaliduser1 = $db->prepare("SELECT * FROM `register` WHERE `name`='".$data->name."' AND `id`='".$data->registerid."' ORDER BY `id` ASC");
$checkvaliduser1->execute();
 $checknum1 = $checkvaliduser1->rowCount();
 
 
 
     $checkvaliduser3 = $db->prepare("SELECT * FROM `register` WHERE `name`='".$data->name."' ORDER BY `id` ASC");
$checkvaliduser3->execute();
 $checknum3 = $checkvaliduser3->rowCount();

if($checknum1==0)
{  
if($checknum3==0)
{   
 // set response code - 400 bad request
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => "true", "error" => "false", "message" => "Username Not Exist"));
}
else{
 
    // set response code - 400 bad request
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => "false", "error" => "true", "message" => "Username Already Exist"));
}
}
else
{
 http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => "true", "error" => "false", "message" => "Same Username"));    
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