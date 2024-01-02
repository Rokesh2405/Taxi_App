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
    !empty($data->notification_id)
){
 $rowfetchh = $checkvaliduser->fetch(PDO::FETCH_ASSOC);
 $booking_id=$data->notification_id;

$booking_amt=getbookingdetails('customer_booking_amount',$data->notification_id);
$wallet_amt=getdriver('wallet',$rowfetchh['id']);
$wallet_percentage=getadminuser('wallet_percentage','1');	
$checkamt=$booking_amt*($wallet_percentage/100);	
if($checkamt<=$wallet_amt){
$balnc_wallet=$wallet_amt-$checkamt;
 $query = "INSERT `wallet_history` (`booking_id`,`driver_id`,`current_wallet`,`used_wallet`,`balace_wallet`,`paid_wallet`) VALUES ('".$data->notification_id."','".$rowfetchh['id']."','".$wallet_amt."','".$checkamt."','".$balnc_wallet."','".$checkamt."') ";
$stmt = $db->prepare($query);
$stmt->execute();	
	
$uquerys = "UPDATE `driver` SET
                    wallet='".$balnc_wallet."' WHERE `id`='".$rowfetchh['id']."' ";
$ustmts = $db->prepare($uquerys);
$ustmts->execute();

}
else
{
$balnc_wallet=$checkamt-$wallet_amt;
$query = "INSERT `wallet_history` (`booking_id`,`driver_id`,`current_wallet`,`used_wallet`,`balace_wallet`,`paid_wallet`) VALUES ('".$data->notification_id."','".$rowfetchh['id']."','".$wallet_amt."','".$checkamt."','0','".$balnc_wallet."') ";
$stmt = $db->prepare($query);
$stmt->execute();	
	
	
$uquerys = "UPDATE `driver` SET
                    wallet='0',paid_wallet='".$balnc_wallet."' WHERE `id`='".$rowfetchh['id']."' ";
$ustmts = $db->prepare($uquerys);
$ustmts->execute();
	
	
}
	
$waiting_hours=$data->waiting_hours;
	
$image=$data->image;
if($image!='')
{
    $filename = time() . ".jpg";
    $file = fopen('../../images/odameter/'. $filename, 'wb');
    $binary = base64_decode($image);
 //   header('Content-Type: bitmap; charset=utf-8');
    $file = fopen('../../images/odameter/'.$filename, 'wb');
    fwrite($file, $binary);
    fclose($file);
    $profimg=$filename;
}   


	

$query = "UPDATE `booking` SET
                    driver_id='".$rowfetchh['id']."',end_image='".$profimg."',amount_from_customer='".$data->amount_from_customer."',`drop_date`='".date('d-m-Y')."',`drop_time`='".date('h:i a')."' WHERE id='".$data->notification_id."'";
$stmt = $db->prepare($query);
$stmt->execute();


  //update read status to driver
$readquery = "UPDATE `notification` SET `read_status`='1' WHERE `to`='".$rowfetchh['id']."' AND `booking_id`='".$data->notification_id."' ";
$readstmt = $db->prepare($readquery);
$readstmt->execute();

$readquery = "UPDATE `notification` SET `read_status`='1' WHERE `title`='DROPTAXI - Your Trip is Started' AND `booking_id`='".$data->notification_id."' ";
$readstmt = $db->prepare($readquery);
$readstmt->execute();


//update read status

    //update read status to customer
$readquery = "UPDATE `notification` SET `read_status`='1' WHERE `booking_id`='".$data->notification_id."' AND `from`='".$rowfetchh['id']."'  ";
$readstmt = $db->prepare($readquery);
$readstmt->execute();



//update read status

//SEND SMS
	$mobileno=getuser('mobileno',getbookingdetails('register_id',$data->notification_id));
$finduser=getuser('device_key',getbookingdetails('register_id',$data->notification_id));
$bkid=	$data->notification_id;
	//if($finduser=='') {
	
$url_name="http://api.onhandsms.com/api/v2/sendsms?username=8667459121&password=8667459121&senderid=DPTXMD
&number='.$mobileno.'&istamil=0&dlttemplateid=1707168663772534122&message=Your Booking I'd ".$bkid." Completed !
Any Doubt You Can Call 679677981.
Future Booking Call Office number.
Thanks for Choosing DROPTAXIMADURAI
Welcome Again!";

	
		$resp=file_get_contents($url_name);
		$jsonres=json_decode($resp);
//	}
// SEND SMS

// Notification to User
$notification = new Notification();
$title1="DROPTAXI - Your Trip is End";
//$message="Hey ".getuser('name',getbookingdetails('register_id',$data->notification_id)).", You have reached your drop location and the trip is been closed. Hope your journey was pleasant with us. Do book our cabs for your next trips. Thank you.";
//$messagenoti="Hey ".getuser('name',getbookingdetails('register_id',$data->notification_id)).", You have reached your drop location and the trip is been closed. Hope your journey was pleasant with us. Do book our cabs for your next trips. Thank you.";
$message="Your Booking completed. Thanks for choosing my travels. Welcome again";
$messagenoti="Your Booking completed. Thanks for choosing my travels. Welcome again";
$query = "INSERT INTO `notification` SET
`booking_id`='".$data->notification_id."',`from`='admin',`to`='".getbookingdetails('register_id',$data->notification_id)."',`title`='".$title1."',`message`='".$message."',`type`='Admin-User' ";
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
						
					$firebase_token =getuser('device_key',getbookingdetails('register_id',$data->notification_id));
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

//      // Notification to User
     
//      // Notification to Driver
$notification = new Notification();
                      $title1="DROPTAXI - Your Trip is End";
                      
$message="Hi, I am ".getdriver('driver_name',$rowfetchh['id']).". I have reached the drop location and the trip is closed. Thank you.";
$messagenoti="Hi, I am ".getdriver('driver_name',$rowfetchh['id']).". I have reached the drop location and the trip is closed. Thank you.";

$query = "INSERT INTO `notification` SET
`booking_id`='".$data->notification_id."',`from`='admin',`to`='".$rowfetchh['id']."',`title`='".$title1."',`message`='".$message."',`type`='Admin-Driver' ";
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
						
					$firebase_token =getdriver('device_key',$rowfetchh['id']);
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

//      // Notification to Driver
//  $query = "UPDATE `booking`
//             SET
//                 base_fare='".$data11['base_fare']."',total_days='".$totdays."',additional_distance='".$data11['additional_distance']."',additional_fare='".$data11['additional_fare']."',per_Day_target='".$data11['per_Day_target']."',distance='".$data11['distance']."',bataFee='".$data11['bataFee']."',perKm='".$data11['perKm']."',total_price='".$data11['total_price']."',paid_amount='".$data11['paid_amount']."',balance_amount='".$data11['balance_amount']."',waiting_charge='".$data11['waiting_charge']."',total_amount_to_pay='".$data11['total_amount_to_pay']."' WHERE `id`='".$bookid."' ";

//     // prepare query
//     $stmt = $db->prepare($query);
//     $stmt->execute();

     
http_response_code(200);
 
    // tell the user
  echo json_encode(array("success" => "true", "error" => "false", "message" => "Updated Successfully"));
       
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
