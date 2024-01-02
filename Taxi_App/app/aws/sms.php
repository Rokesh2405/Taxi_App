<?php
  
require 'vendor/autoload.php';
// error_reporting(1);
// ini_set('display_errors','1');
//  error_reporting(E_ALL);
$sns = Aws\Sns\SnsClient::factory(array(
    'credentials' => [
        'key'    => 'AKIA4HYGQG4KS3WMHCPF',
        'secret' => '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',
    ],
    'region' => 'ap-south-1',
    'version'  => 'latest',
));


$result = $sns->publish([
    'Message' => 'Test Message', // REQUIRED
    'MessageAttributes' => [
        'AWS.SNS.SMS.SenderID' => [
            'DataType' => 'String', // REQUIRED
            'StringValue' => 'INproject'
        ],
        'AWS.SNS.SMS.SMSType' => [
            'DataType' => 'String', // REQUIRED
            'StringValue' => 'Transactional' // or 'Promotional'
        ]
    ],
    'PhoneNumber' => '+918524842594',
]);
echo "<pre>";
print_r($result);
if($result['@metadata']['statusCode']=='200'){
    echo "Success";
}
else
{
    echo "Failed";
}
// echo "<pre>";
// echo $result['@metadata']['statusCode'];
//  print_r($result)
?>