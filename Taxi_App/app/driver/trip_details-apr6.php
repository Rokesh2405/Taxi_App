<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
$token= "";

// error_reporting(1);
// ini_set('display_errors','1');
// error_reporting(E_ALL);

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
    !empty($data->notification_id) && !empty($data->km) && !empty($data->type) && !empty($data->triptime)
){
 
 $booking_id=getnotification('booking_id',$data->notification_id);
 
if($data->type=='start') { 
if(getbookingdetails('otp',$booking_id)==$data->otp) {     
$query = "UPDATE `booking` SET
                    start_km='".$data->km."',pickup_time='".$data->triptime."' WHERE id='".getnotification('booking_id',$data->notification_id)."'";
$stmt = $db->prepare($query);
$stmt->execute();

// Notification to Admin
$notification = new Notification();
$title1="DROPTAXI - Your Trip is Started";
$message="Driver ".getdriver('driver_name',getnotification('to',$data->notification_id))." trip started";

$messagenoti="Driver ".getdriver('driver_name',getnotification('to',$data->notification_id))." trip started";
					
$query = "INSERT INTO `notification` SET
`booking_id`='".getnotification('booking_id',$data->notification_id)."',`from`='".getnotification('to',$data->notification_id)."',`to`='admin',`title`='".$title1."',`message`='".$message."',`type`='Driver-Admin' ";
$stmt = $db->prepare($query);
$stmt->execute();
$lasid=$db->lastInsertId();
 $title="DROPTAXI - Booking No :".$data->notification_id;

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
						
					$firebase_token =getadminuser('device_key','1');;
				 $firebase_api =getadminuser('firebase_api_key','1');
						
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

				}

     // Notification to Admin
 
 
 $getdriver = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".getnotification('booking_id',$data->notification_id)."' AND `driver_charge`!='' AND `confirm_status`='1' ORDER BY `id` ASC");
$getdriver->execute();
$getdriverdetails = $getdriver->fetch(PDO::FETCH_ASSOC);


     // Notification to Driver
$notification = new Notification();
                      $title1="DROPTAXI - Your Trip is Started";
                        $message="Started to Ride";
					   
					$messagenoti="Your Trip is Started";
$query = "INSERT INTO `notification` SET
`booking_id`='".getnotification('booking_id',$data->notification_id)."',`from`='admin',`to`='".$getdriverdetails['from']."',`title`='".$title1."',`message`='".$message."',`type`='Admin-Driver' ";
$stmt = $db->prepare($query); 
$stmt->execute();
 $title="DROPTAXI - Booking No :".$data->notification_id;

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
						
					$firebase_token =getdriver('device_key',$getdriverdetails['from']);
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

			}

     // Notification to Driver
     
http_response_code(200);
    // tell the user
echo json_encode(array("success" => "true", "error" => "false","message"=>"Updated Successfully"));    
}
else
{
 http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => "false", "error" => "true","message"=>"Invalid OTP"));     
         
}
}
else { 
    $waiting_hours=$data->waiting_hours;
$query = "UPDATE `booking` SET
                    end_km='".$data->km."',drop_time='".$data->triptime."' WHERE id='".getnotification('booking_id',$data->notification_id)."'";
$stmt = $db->prepare($query);
$stmt->execute();
$paidamount=getbookingdetails('customer_booking_amount',getnotification('booking_id',$data->notification_id));
$startkm=getbookingdetails('start_km',getnotification('booking_id',$data->notification_id));
$triptpe=getbookingdetails('triptype',getnotification('booking_id',$data->notification_id));
$carid=getbookingdetails('car_id',getnotification('booking_id',$data->notification_id));
$endkm=$data->km;
$distance=$endkm-$startkm;
$bookid=getnotification('booking_id',$data->notification_id);
$kmresponse=balancetripamount($triptpe,$carid,$distance,$paidamount,$waiting_hours,$bookid);
// Notification to User
// $notification = new Notification();
// $title1="DROPTAXI - Your Trip is End";
// $message="Driver ".getdriver('driver_name',getnotification('to',$data->notification_id))." dropped in your location";

// $messagenoti="Driver ".getdriver('driver_name',getnotification('to',$data->notification_id))." dropped in your location";
					
// $query = "INSERT INTO `notification` SET
// `booking_id`='".getnotification('booking_id',$data->notification_id)."',`from`='admin',`to`='".getbookingdetails('register_id',getnotification('booking_id',$data->notification_id))."',`title`='".$title1."',`message`='".$message."',`type`='Admin-User' ";
// $stmt = $db->prepare($query);
// $stmt->execute();
// $lasid=$db->lastInsertId();
//  $title="DROPTAXI - Booking No :".$data->notification_id;

// 						//$imageUrl = isset($_POST['image_url'])?$_POST['image_url']:'';
// 						$imageUrl = '';
// 						//$action = isset($_POST['action'])?$_POST['action']:'';
// 						$action ='';
// 						//$actionDestination = isset($_POST['action_destination'])?$_POST['action_destination']:'';
// 	                    $actionDestination='';
// 						if($actionDestination ==''){
// 							$action = '';
// 						}
// 						$notification->setTitle($title);
// 						$notification->setMessage($messagenoti);
// 						$notification->setImage($imageUrl);
// 						$notification->setAction($action);
// 						$notification->setActionDestination($actionDestination);
						
// 					$firebase_token =getuser('device_key',getbookingdetails('register_id',getnotification('booking_id',$data->notification_id)));
// 				 $firebase_api =getadminuser('firebase_api_key','1');
						
// 					//	$topic = $_POST['topic'];
						
// 						$requestData = $notification->getNotificatin();
						
// 				// 		if($_POST['send_to']=='topic'){
// 				// 			$fields = array(
// 				// 				'to' => '/topics/' . $topic,
// 				// 				'data' => $requestData,
// 				// 			);
							
// 				// 		}else{
							
// 							$fields = array(
// 								'to' => $firebase_token,
// 								'data' => $requestData,
// 							);
// 					//	}
		
		
// 						// Set POST variables
// 						$url = 'https://fcm.googleapis.com/fcm/send';
 
// 						$headers = array(
// 							'Authorization: key=' . $firebase_api,
// 							'Content-Type: application/json'
// 						);
						
// 						// Open connection
// 						$ch = curl_init();
 
// 						// Set the url, number of POST vars, POST data
// 						curl_setopt($ch, CURLOPT_URL, $url);
 
// 						curl_setopt($ch, CURLOPT_POST, true);
// 						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// 						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
// 						// Disabling SSL Certificate support temporarily
// 						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
// 						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
// 						// Execute post
// 						$result = curl_exec($ch);
// 						if($result === FALSE){
// 							die('Curl failed: ' . curl_error($ch));
// 						}
 
// 						// Close connection
						
						
// 						curl_close($ch);
						
// 				// 		echo '<h2>Result</h2><hr/><h3>Request </h3><p><pre>';
// 				// 		echo json_encode($fields,JSON_PRETTY_PRINT);
// 				// 		echo '</pre></p><h3>Response </h3><p><pre>';
// 				// 		echo $result;
// 				// 		echo '</pre></p>';
// 				// 		exit;
// 				$resultjson = json_decode($result, true);
// 			if($resultjson['success']=='1')
// 			{

// 				}

//      // Notification to User
     
//      // Notification to Driver
// $notification = new Notification();
//                       $title1="DROPTAXI - Your Trip is End";
//                         $message="Your Trip is End";
					   
// 					$messagenoti="Your Trip is End";
// $query = "INSERT INTO `notification` SET
// `booking_id`='".getnotification('booking_id',$data->notification_id)."',`from`='admin',`to`='".getnotification('to',$data->notification_id)."',`title`='".$title1."',`message`='".$message."',`type`='Admin-Driver' ";
// $stmt = $db->prepare($query); 
// $stmt->execute();
//  $title="DROPTAXI - Booking No :".$data->notification_id;

// 						//$imageUrl = isset($_POST['image_url'])?$_POST['image_url']:'';
// 						$imageUrl = '';
// 						//$action = isset($_POST['action'])?$_POST['action']:'';
// 						$action ='';
// 						//$actionDestination = isset($_POST['action_destination'])?$_POST['action_destination']:'';
// 	                    $actionDestination='';
// 						if($actionDestination ==''){
// 							$action = '';
// 						}
// 						$notification->setTitle($title);
// 						$notification->setMessage($messagenoti);
// 						$notification->setImage($imageUrl);
// 						$notification->setAction($action);
// 						$notification->setActionDestination($actionDestination);
						
// 					$firebase_token =getdriver('device_key',getnotification('to',$data->notification_id));
// 				 $firebase_api =getadminuser('driver_firebase_api_key','1');
						
// 					//	$topic = $_POST['topic'];
						
// 						$requestData = $notification->getNotificatin();
						
// 				// 		if($_POST['send_to']=='topic'){
// 				// 			$fields = array(
// 				// 				'to' => '/topics/' . $topic,
// 				// 				'data' => $requestData,
// 				// 			);
							
// 				// 		}else{
							
// 							$fields = array(
// 								'to' => $firebase_token,
// 								'data' => $requestData,
// 							);
// 					//	}
		
		
// 						// Set POST variables
// 						$url = 'https://fcm.googleapis.com/fcm/send';
 
// 						$headers = array(
// 							'Authorization: key=' . $firebase_api,
// 							'Content-Type: application/json'
// 						);
						
// 						// Open connection
// 						$ch = curl_init();
 
// 						// Set the url, number of POST vars, POST data
// 						curl_setopt($ch, CURLOPT_URL, $url);
 
// 						curl_setopt($ch, CURLOPT_POST, true);
// 						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// 						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
// 						// Disabling SSL Certificate support temporarily
// 						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
// 						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
// 						// Execute post
// 						$result = curl_exec($ch);
// 						if($result === FALSE){
// 							die('Curl failed: ' . curl_error($ch));
// 						}
 
// 						// Close connection
						
						
// 						curl_close($ch);
						
// 				// 		echo '<h2>Result</h2><hr/><h3>Request </h3><p><pre>';
// 				// 		echo json_encode($fields,JSON_PRETTY_PRINT);
// 				// 		echo '</pre></p><h3>Response </h3><p><pre>';
// 				// 		echo $result;
// 				// 		echo '</pre></p>';
// 				// 		exit;
// 				$resultjson = json_decode($result, true);
// 			if($resultjson['success']=='1')
// 			{

// 				}

//      // Notification to Driver
     
     
http_response_code(200);
 
    // tell the user
echo json_encode($kmresponse);   
}
 

       
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
