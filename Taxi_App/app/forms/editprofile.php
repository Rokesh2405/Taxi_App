<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 

// get database connection
include_once '../config/database.php';
 require '../aws/vendor/autoload.php';
 
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

// instantiate product object
include_once '../objects/form.php';
 include_once '../objects/functions.php';
$database = new Database();
$db = $database->getConnection();
 
$form = new Form($db);
 
// get posted data

$data = json_decode(file_get_contents("php://input"));

$checkvaliduser = $db->prepare("SELECT * FROM `register` WHERE `token`='".$token."' ORDER BY `id` ASC");
$checkvaliduser->execute();
 $checknum = $checkvaliduser->rowCount();

if($checknum>0)
{ 
// make sure data is not empty
if(
    !empty($data->registerid)
){
   
   $data->otp = generateRandomString();

$sns = Aws\Sns\SnsClient::factory(array(
    'credentials' => [
        'key'    => 'AKIA6OFTZJPNXVICRLM7',
        'secret' => 'L2SGbowkvbaTKIHL2X3q9OKCbx2O/aAglTUlF8PH',
    ],
    'region' => 'us-east-1',
    'version'  => 'latest',
));


 $sms_msgv = "<#> Your Droptaxi APP code is: ".$data->otp;  

$result = $sns->publish([
    'Message' => $sms_msgv, // REQUIRED
    'MessageAttributes' => [
        'AWS.SNS.SMS.SenderID' => [
            'DataType' => 'String', // REQUIRED
            'StringValue' => 'INUpload'
        ],
        'AWS.SNS.SMS.SMSType' => [
            'DataType' => 'String', // REQUIRED
            'StringValue' => 'Transactional' // or 'Promotional'
        ]
    ],
    'PhoneNumber' => '+91'.trim($data->mobileno),
]);
// echo "<pre>";
// echo $result['@metadata']['statusCode'];

// Send SMS End
if($result['@metadata']['statusCode']=='200')
{
    // Add Report
$postRequest = array(
    'site' => 'Droptaxi App',
    'mobileno' => $data->mobileno,
    'countrycode' => '+91',
    'message' => $sms_msgv
);

$cURLConnection = curl_init('https://webtoall.in/smslog/api/addlog');
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

$apiResponse = curl_exec($cURLConnection);
curl_close($cURLConnection);

// Add Report

}
$query = "UPDATE `register` SET
                    otp='".$data->otp."'  WHERE id='".$data->registerid."'";
$stmt = $db->prepare($query);
$stmt->execute();
  // set response code - 400 bad request
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("success" => "true", "error" => "false","registerid"=>$data->registerid, "message" => "SMS Send to your Mobileno"));
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