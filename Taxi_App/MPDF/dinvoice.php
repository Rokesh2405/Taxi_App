<?php
require_once __DIR__ . '/vendor/autoload.php';
// error_reporting(1);
// ini_set('display_errors','1');
// error_reporting(E_ALL);
include '../config/config.inc.php';
$link22 = FETCH_all("SELECT * FROM `booking` WHERE `id`=?", $_REQUEST['invoiceid']);
$link23 = FETCH_all("SELECT * FROM `notification` WHERE `booking_id`=? AND `driver_name`!=''", $_REQUEST['invoiceid']);


$message.='<div>
<table style="width:100%;font-size:20px;" cellpadding="10" cellspacing="0" border="1">
  <tr>
   <th colspan="3">
   <h1 style=" margin-bottom: -34px;font-size: 30px;font-family: "Times New Roman", Times, serif;line-height: 30px;word-spacing: 8px;letter-spacing: 2px;">Sri Madurai Meenakshi<br> Tours and Travels</h1><br></th> 
   </tr>
  <tr>
    <td width="50%" align="left"><img src="https://droptaximadurai.in/assets/images/logo/logo.png" width="300"></td>
    <td  width="30%"><h3 style="font-size: 18px;margin-top: 20px;">4B,West Marrat Street,<br>Madurai -625001 </h3></td>
    <td  width="20%" align="right">Mob: +91 8667459121<br>&nbsp;&nbsp;&nbsp;+91 9597174783</td>
  </tr>
 
  <tr>
  <td style="font-weight:bold;" width="60%" valign="top" style="border:none;">
  <table width="100%" border="0" style="border:none;">
  <tr>
  <td style="font-weight:bold;" align="left">Customer Name : '.getregisterform('name',$link22['register_id']).'</td>
  </tr>
  <tr>
  <td style="font-weight:bold;"  align="left">Contact Number : '.getregisterform('mobileno',$link22['register_id']).'</td>
  </tr>
  </table>
 </td>
  <td  valign="top" style="font-weight:bold;" width="50%">
  
  <table width="100%">
  <tr>
  <td align="left"><strong>From :</strong> '.$link22['pickup_address'].'</td>
  </tr>
  <tr>
  <td align="left"><strong>To : </strong>'.$link22['drop_address'].'</td>
  </tr>
  </table>
</td>
  <td  valign="top" style="font-weight:bold; text-align:right;">
   <table width="100%">
  <tr>
  <td style="font-weight:bold;" align="left">Pickup Date : '.date('d/m/Y',strtotime($link22['trip_date'])).'</td>
  </tr>';
  if($link22['drop_date']!='') { 
  $message.='<tr>
  <td style="font-weight:bold;"  align="left">Drop Date&nbsp;&nbsp;&nbsp;&nbsp;: '.date('d/m/Y',strtotime($link22['drop_date'])).'</td>
  </tr>';
  }
  
  if($link22['drop_time']!=''){
      $ddtime=explode(":",$link22['drop_time']);
      $nnoon=explode(" ",$ddtime['2']);
      $fdtime=$ddtime['0'].':'.$ddtime['1']." ".$nnoon['1'];
  }
  $message.='<tr>
  <td style="font-weight:bold;"  align="left">Pickup Time : '.$link22['trip_time'].'</td>
  </tr>
   <tr>
  <td style="font-weight:bold;"  align="left">Drop Time&nbsp;&nbsp;&nbsp;&nbsp;: '.$fdtime.'</td>
  </tr>
  </table>
  
  
  </td>
  </tr>

  <tr style="background: #4BA9E6; font-weight: bold;">
    <td height="60" style="font-size: 20px;"><strong>Description</strong></td>
    <td style="font-size: 20px;">&nbsp;</td>
    <td style="font-size: 20px;" align="right"><strong>Amount</strong></td>
 </tr>

  <tr>
    <td style="float: left;position: relative;left: 21px;font-weight: bold;">Day Rent</td>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td style="position: relative;left: 21px; ">Base Fare (First '.$link22['per_Day_target'].' km)</td>
    <td>&nbsp;</td>
    <td align="right" style="font-weight: bold; font-size: 22px;"><strong>Rs. '.$link22['base_fare'].'</strong></td>
  </tr>';
    if($link22['additional_distance']!='') { 
  $message.='<tr>
    <td style="float: left;position: relative;left: 21px;">Kilometer Charge  perkm ('.$link22['perKm'].' Rs x '.$link22['additional_distance'].' km)</td>
    <td>&nbsp;</td>
    <td align="right" style="font-weight: bold; font-size: 22px;"><strong>Rs. '.$link22['additional_fare'].'</strong></td>
  </tr>';
  }
   if($link22['waiting_charge']!='') { 
       $perchrg=getusers('waiting_charge',1);
  $message.='<tr>
    <td style="float: left;position: relative;left: 21px;">Waiting Charge (Rs.'.$perchrg.' x '.$link22['waiting_hours'].' hour)</td>
    <td>&nbsp;</td>
    <td align="right" style="font-weight: bold; font-size: 22px;"><strong>Rs. '.$link22['waiting_charge'].'</strong></td>
  </tr>';
  }
  if($link22['drop_date']!='') {
     $totdays=$link22['total_days']; 
  }
  else
  {
    $totdays='1';   
  }
  $bfare=$link22['bataFee']/$totdays;
  $totlaldays=$bfare*$totdays;
  
  $message.='<tr>
    <td colspan="2" style="float: left;position: relative;left: 21px; ">Driver Allowance perday (Rs. '.$bfare.' x '.$totdays.' days)</td>
   <td align="right" style="font-weight: bold; font-size: 22px;"><strong>Rs.'.$totlaldays.'</strong></td>
  </tr>
  
 <tr>
    <td style="float: left;position: relative;left: 21px; margin-top:32px;
    font-weight: bold;">Travel Details</td>
    <td></td>
    <td></td>
  </tr>

  <tr>
  <td colspan="2">
  <table width="70%" cellpadding="10">
  <tr>
  <td><strong>Starting km</strong></td>
  <td> : </td>
  <td>'.$link22['start_km'].'</td>
  </tr>
   <tr>
  <td><strong>Closing km</strong></td>
  <td> : </td>
  <td>'.$link22['end_km'].'</td>
  </tr>
   <tr>
  <td><strong>Total km</strong></td>
  <td> : </td>
  <td>'.$link22['distance'].' km </td>
  </tr>
  </table>
  </td>
 
  <td>&nbsp;</td>
  </tr>
  <tr style="background: #4BA9E6;height: 24px;">
    <td width="50%" colspan="2" align="left" style="padding-left:20px; font-weight: bold; font-size: 20px;" height="60">Total Amount</td>
    <td width="50%" align="right" style="font-weight: bold; font-size: 22px;"><strong>Rs. '.$link22['final_total_amount'].'<strong></td>
  </tr>
  <tr>
 	<td colspan="3">&nbsp;</td>
 </tr>
  <tr style="background: #4BA9E6;">
    <td colspan="3" style="padding-left:20px; font-weight: bold; font-size: 20px;" height="60" align="center">Driver and Vehicle Details</td>
   </tr>
   <tr>
   <td colspan="3">
   <table width="80%" cellpadding="10">
    <tr>
    <td style="float: left;position: relative;left: 20px;font-weight:bold;">Driver Name</td>
    <td> : </td>
    <td>'.$link23['driver_name'].'</td>
  </tr>
    <tr>
    <td style="float: left;position: relative;left: 20px;font-weight:bold;">Driver Mobile</td>
    <td>:</td>
    <td>'.$link23['driver_mobileno'].'</td>
   
  </tr>
    <tr>
    <td style="float: left;position: relative;left: 20px;font-weight:bold;">Vehicle Type</td>
    <td>:</td>
    <td>'.$link23['cartype'].'</td>
   
  </tr>
    <tr>
    <td style="float: left;position: relative;left: 20px; font-weight:bold;">Vehicle Number</td>
    <td>:</td>
    <td>'.$link23['driver_carno'].'</td>
   
  </tr>
   </table>
   </td>
   </tr>
   
   

</table></div>';             

$mpdf = new mPDF();
$mpdf->SetDisplayMode('default');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
$filename = "test.txt";

$file = fopen($filename, "w");
fwrite($file, $message);
$mpdf->SetTitle('Invoice Report');
$mpdf->keep_table_proportions = false;
$mpdf->shrink_this_table_to_fit = 0;
$mpdf->SetAutoPageBreak(true, 10);
$mpdf->WriteHTML(file_get_contents($filename));
//$mpdf->SetWatermarkImage('jiovio.png', 0.10, 'F');
//$mpdf->showWatermarkImage = true;
$mpdf->setAutoBottomMargin = 'stretch';
//$mpdf->setHTMLFooter('<div style="border-top: 0.1mm solid #000000;"><table width="100%"><tr><td colspan="2" align="center"><b>Healthcare</b></td></tr><tr><td><b>E-mail : </b>'.gethospital('emailid',$appointment['hospitalid']).'</td><td align="right"><b>For Support</b><br>'.gethospital('contactno',$appointment['hospitalid']).'</td></tr></table>');

$mpdf->Output('invoice.pdf', 'D');
// $mpdf->Output(date('d-m-Y').'_'.time().'_Invoice-Report.pdf', 'F');
// $myObj->bill = $fsitename.'MPDF/'.date('d-m-Y').'_'.time().'_Invoice-Report.pdf';
// $myJSON = json_encode($myObj);
// echo $myObj->bill;

?>
