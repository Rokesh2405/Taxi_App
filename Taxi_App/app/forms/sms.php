<?php
require '../vendor/autoload.php';
use Twilio\Rest\Client;

$otp="1234";
$mobileno="9025990230";
$sms_msgv = "<#> Your Droptaxi APP code is: ".$otp;  

// Your Account SID and Auth Token from twilio.com/console

/*$account_sid = "ACba2f1aa93a7df2547b48c0501fec30ce";
$auth_token = "9f5dd03fe496a092894569fda8d80177";

    
$twilio_number = "+14323024963";

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    '+91'.$mobileno,
    array(
        'from' => $twilio_number,
        'body' => $sms_msgv
    )
);*/

 $sid    = "ACba2f1aa93a7df2547b48c0501fec30ce";
    $token  = "9f5dd03fe496a092894569fda8d80177";
    $twilio = new Client($sid, $token);

    $message = $twilio->messages
      ->create("+917904190292", // to
        array(
          "from" => "+14323024963",
          "body" => $otp
        )
      );

print($message->sid);
?>



