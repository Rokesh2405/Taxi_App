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
include_once 'notification.php';
$database = new Database();
$db = $database->getConnection();
 
$form = new Form($db);
 
// get posted data

$data = json_decode(file_get_contents("php://input"));

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
$checkvaliduser11 = $db->prepare("SELECT * FROM `register` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser11->execute();
 $checknum11 = $checkvaliduser11->rowCount();
 if($checknum11>0) {
// make sure data is not empty
if(
    !empty($data->register_id) &&
    !empty($data->pickup_address) &&
    !empty($data->drop_address)
){

$checkvaliduser = $db->prepare("SELECT * FROM `booking` WHERE `pickup_address`='".$data->pickup_address."' AND `drop_address`='".$data->drop_address."' AND `register_id`='".$data->register_id."' AND `date`='".date('Y-m-d',strtotime($data->pickup_date))."' ORDER BY `id` ASC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();
if($checknum3==0)
{   
$form->register_id=$data->register_id;
$form->pickup_address=$data->pickup_address;
$form->drop_address=$data->drop_address;
$form->triptype=$data->triptype;
$form->car_id=$data->car_id;
if($data->drop_date!='') { 
$form->drop_date=date('Y-m-d',strtotime($data->drop_date));
}
else
{
 $form->drop_date='';   
}
$form->trip_date=date('Y-m-d',strtotime($data->trip_date));
$form->trip_time=$data->trip_time;
$form->booking_amount=$data->customer_total_booking_amount;
$form->quote_amount=$data->quote_amount;
$form->booking_km=$data->booking_km;
$form->customer_paid_booking_amount=$data->booking_amount;

$lastregid=$form->createbooking();

// Notification 

$notification = new Notification();
$message="Hi, We have received an ".$data->triptype." trip booking from ".getuser('name',$data->register_id).". Please assign duty to available driver and confirm this booking.";
$messagenoti="Hi, We have received an ".$data->triptype." trip booking from ".getuser('name',$data->register_id).". Please assign duty to available driver and confirm this booking.";
						$title = 'DROPTAXI : Booking No - '.getbooking('id',$data->register_id);

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
						
				$resultjson = json_decode($result, true);
		//	if($resultjson['success']=='1')
		//	{
	
$query = "INSERT INTO `notification` SET
                    `booking_id`='".getbooking('id',$data->register_id)."',`from`='".$data->register_id."',`to`='admin',`title`='".$title."',`message`='".$message."',`type`='User-Admin' ";
$stmt = $db->prepare($query);
$stmt->execute();
				//}

      // Notification
      
   // User Notification 


$notificationm = new Notification();
$messagem="We have received your booking enquiry.We check and confirm. Thank you";
$messagenotim="We have received your booking enquiry.We check and confirm. Thank you";
						$titlem = 'DROPTAXI : Booking No - '.getbooking('id',$data->register_id);

						//$imageUrl = isset($_POST['image_url'])?$_POST['image_url']:'';
						$imageUrlm = '';
						//$action = isset($_POST['action'])?$_POST['action']:'';
						$actionm ='';
						//$actionDestination = isset($_POST['action_destination'])?$_POST['action_destination']:'';
	                    $actionDestinationm='';
						if($actionDestinationm ==''){
							$actionm = '';
						}
						$notificationm->setTitle($titlem);
						$notificationm->setMessage($messagenotim);
						$notificationm->setImage($imageUrlm);
						$notificationm->setAction($actionm);
						$notificationm->setActionDestination($actionDestinationm);
						
					$firebase_tokenm =getuser('device_key',$data->register_id);
				 $firebase_apim =getadminuser('firebase_api_key','1');
						
					//	$topic = $_POST['topic'];
						
						$requestDatam = $notificationm->getNotificatin();
						
				// 		if($_POST['send_to']=='topic'){
				// 			$fields = array(
				// 				'to' => '/topics/' . $topic,
				// 				'data' => $requestData,
				// 			);
							
				// 		}else{
							
							$fieldsm = array(
								'to' => $firebase_tokenm,
								'data' => $requestDatam,
							);
					//	}
		
		
						// Set POST variables
						$urlm = 'https://fcm.googleapis.com/fcm/send';
 
						$headersm = array(
							'Authorization: key=' . $firebase_apim,
							'Content-Type: application/json'
						);
						
						// Open connection
						$chm = curl_init();
 
						// Set the url, number of POST vars, POST data
						curl_setopt($chm, CURLOPT_URL, $urlm);
 
						curl_setopt($chm, CURLOPT_POST, true);
						curl_setopt($chm, CURLOPT_HTTPHEADER, $headersm);
						curl_setopt($chm, CURLOPT_RETURNTRANSFER, true);
 
						// Disabling SSL Certificate support temporarily
						curl_setopt($chm, CURLOPT_SSL_VERIFYPEER, false);
 
						curl_setopt($chm, CURLOPT_POSTFIELDS, json_encode($fieldsm));
 
						// Execute post
						$resultm = curl_exec($chm);
						if($resultm === FALSE){
							die('Curl failed: ' . curl_error($chm));
						}
 
						// Close connection
						
						
						
						
				$resultjsonm = json_decode($resultm, true);
	
			if($resultjsonm['success']=='1')
			{
	
$query = "INSERT INTO `notification` SET
                    `booking_id`='".getbooking('id',$data->register_id)."',`from`='admin',`to`='".$data->register_id."',`title`='".$titlem."',`message`='".$messagem."',`type`='Admin-User' ";
$stmt = $db->prepare($query);
$stmt->execute();
				}

      // User Notification
         
curl_close($chm);
   http_response_code(200);
   
    // tell the user
    echo json_encode(array("success" => "true", "error" => "false", "message" => "Booked Successfully","booking_id"=>$lastregid));   

}
else{
 
    // set response code - 400 bad request
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => "false", "error" => "true", "message" => "Already Booked"));
}
}
 
// tell the user data is incomplete
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