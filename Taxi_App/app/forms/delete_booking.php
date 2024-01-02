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
 
$database = new Database();
$db = $database->getConnection();
 
$form = new Form($db);
 
// get posted data

$data = json_decode(file_get_contents("php://input"));


$query = "TRUNCATE TABLE `booking`";
$stmt = $db->prepare($query);
$stmt->execute();

$query1= "TRUNCATE TABLE `notification`";
$stmt1 = $db->prepare($query1);
$stmt1->execute();

 http_response_code(200);

  // tell the user
        echo json_encode(array("success" => "true", "error" => "false","message" => "Empty")); 

?>