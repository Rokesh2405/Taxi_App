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

 
$checkvaliduser = $db->prepare("SELECT * FROM `driver` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();

if($checknum>0) {
if(
    !empty($data->notification_id) && !empty($data->driver_id)  && !empty($data->driver_name)  && !empty($data->driver_mobileno)  && !empty($data->driver_carno) 
){

 $mstmt11213 = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".getnotification('booking_id',$data->notification_id)."' AND `confirm_status`='1' AND `driver_charge`!='' ORDER BY `id` DESC");	
$mstmt11213->execute();
$mrow11214 = $mstmt11213->fetch(PDO::FETCH_ASSOC);

   // Notification 
$notification = new Notification();

$title1="DROPTAXI - Booking Accepted from ".getdriver('driver_name',$data->driver_id);
    $message="He is to Picked up";
    $messagenoti="He is to Picked up";


$title="DROPTAXI - Bookingno :".$mrow11214['id'];
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
						
					$firebase_token =getadminuser('device_key','1');
				 $firebase_api =getadminuser('admin_firebase_api_key','1');
						
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
$query = "INSERT INTO `notification` SET
                    `booking_id`='".getnotification('booking_id',$data->notification_id)."',`from`='".$data->driver_id."',`to`='admin',`title`='".$title1."',`message`='".$message."',`type`='Driver-Admin' ";
$stmt = $db->prepare($query);
$stmt->execute();
}

   // Notification
     
// $uquery = "UPDATE `notification` SET
//                     accept_status='1',`driver_name`='".$data->driver_name."',`driver_mobileno`='".$data->driver_mobileno."',`driver_carno`='".$data->driver_carno."',`cartype`='".$data->cartype."',`confirm_status`='1' WHERE `id`='".$data->notification_id."' ";
$uquery = "UPDATE `notification` SET
                    accept_status='1',`driver_name`='".$data->driver_name."',`driver_mobileno`='".$data->driver_mobileno."',`driver_carno`='".$data->driver_carno."',`cartype`='".$data->cartype."',`confirm_status`='1' WHERE `booking_id`='".getnotification('booking_id',$data->notification_id)."' AND `confirm_status`='1' AND `driver_charge`!=''";
$ustmt = $db->prepare($uquery);
$ustmt->execute();

   http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => "true", "error" => "false", "message" => "Acceptance Send to Admin"));     
     
       
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
