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

$checkvaliduser = $db->prepare("SELECT * FROM `users` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser->execute();
$checknum = $checkvaliduser->rowCount();

if($checknum>0) {
if(
    !empty($data->amount) &&
    !empty($data->booking_id)
){
    
    
//update read status
$readquery = "UPDATE `notification` SET `read_status`='1' WHERE `booking_id`='".$data->booking_id."' ";
$readstmt = $db->prepare($readquery);
$readstmt->execute();
//update read status


$driver = $db->prepare("SELECT * FROM `driver` WHERE `status`='1' ORDER BY `id` ASC");
$driver->execute();

while ($driverfetch = $driver->fetch(PDO::FETCH_ASSOC)) {  
if($driverfetch['device_key']!='') {    
   // Notification 


$notification = new Notification();

$title1="DROPTAXI";

$message="Hi, There is an ".getbookingdetails('triptype',$data->booking_id)." trip booking travelling on ".date('d-m-Y',strtotime(getbookingdetails('trip_date',$data->booking_id))).". Is there any driver available to pick up this duty?, Reply to confirm this booking.";

$messagenoti="Hi, There is an ".getbookingdetails('triptype',$data->booking_id)." trip booking travelling on ".date('d-m-Y',strtotime(getbookingdetails('trip_date',$data->booking_id))).". Is there any driver available to pick up this duty?, Reply to confirm this booking.";
// Add Notification		

$alreadyexist = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$data->booking_id."' AND `to`='".$driverfetch['id']."' AND `message`='".$message."' ORDER BY `id` ASC");
$alreadyexist->execute();
$alreaddynum = $alreadyexist->rowCount();
if($alreaddynum==0) {
$query = "INSERT INTO `notification` SET
`booking_id`='".$data->booking_id."',`from`='admin',`to`='".$driverfetch['id']."',`title`='".$title1."',`message`='".$message."',`type`='Admin-Driver' ";
$stmt = $db->prepare($query);
$stmt->execute();

$lasid=$db->lastInsertId();
$title="DROPTAXI - Booking No :".$lasid;
	// Add Notification		
						//$imageUrl = isset($_POST['image_url'])?$_POST['image_url']:'';
						$imageUrl = '';
						//$action = isset($_POST['action'])?$_POST['action']:'';
						$action ='';
						//$actionDestination = isset($_POST['action_destination'])?$_POST['action_destination']:'';
	                    $actionDestination='';
						if($actionDestination ==''){
							$action = '';
						}
						$notification->setTitle($title);
						$notification->setMessage($messagenoti);
						$notification->setImage($imageUrl);
						$notification->setAction($action);
						$notification->setActionDestination($actionDestination);
						
					$firebase_token =$driverfetch['device_key'];
				 $firebase_api =getadminuser('driver_firebase_api_key','1');
						
					//	$topic = $_POST['topic'];
						
						$requestData = $notification->getNotificatin();
						
				// 		if($_POST['send_to']=='topic'){
				// 			$fields = array(
				// 				'to' => '/topics/' . $topic,
				// 				'data' => $requestData,
				// 			);
							
				// 		}else{
							
							$fields = array(
								'to' => $firebase_token,
								'data' => $requestData,
							);
					//	}
		
		
						// Set POST variables
						$url = 'https://fcm.googleapis.com/fcm/send';
 
						$headers = array(
							'Authorization: key=' . $firebase_api,
							'Content-Type: application/json'
						);
						
						// Open connection
						$ch = curl_init();
 
						// Set the url, number of POST vars, POST data
						curl_setopt($ch, CURLOPT_URL, $url);
 
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
						// Disabling SSL Certificate support temporarily
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
						// Execute post
						$result = curl_exec($ch);
						if($result === FALSE){
							die('Curl failed: ' . curl_error($ch));
						}
 
						// Close connection
						
						
						curl_close($ch);
						
				// 		echo '<h2>Result</h2><hr/><h3>Request </h3><p><pre>';
				// 		echo json_encode($fields,JSON_PRETTY_PRINT);
				// 		echo '</pre></p><h3>Response </h3><p><pre>';
				// 		echo $result;
				// 		echo '</pre></p>';
				// 		exit;
				$resultjson = json_decode($result, true);
			if($resultjson['success']=='1')
			{
// $query = "INSERT INTO `notification` SET
//                     `booking_id`='".$data->booking_id."',`from`='admin',`to`='".$driverfetch['id']."',`title`='".$title."',`message`='".$message."',`type`='Admin-Driver' ";
// $stmt = $db->prepare($query);
// $stmt->execute();
				}
}
     }  // Notification
     
}

      $uquery = "UPDATE `booking` SET
                    request_status='1',`quote_amount`='".$data->amount."' WHERE `id`='".$data->booking_id."' ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();

   http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => "true", "error" => "false", "message" => "Request Send to Drivers"));     
     
       
}
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("success" => "false", "error" => "true", "message" => "Unable to create user. Data is incomplete."));
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
