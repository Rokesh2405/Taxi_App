<?php

include_once '../config/database.php';
 
 
$database = new Database();
$db = $database->getConnection();
function getdays($date1,$date2){
 $date1 = strtotime($date1);  
$date2 = strtotime($date2);  
  
// Formulate the Difference between two dates 
$diff = abs($date2 - $date1);  

// To get the day, subtract it with years and  
// months and divide the resultant date into 
// total seconds in a days (60*60*24) 
$days = floor(($diff - $years * 365*60*60*24 -  
             $months*30*60*60*24)/ (60*60*24)); 
return $days;
}
function getplace($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `place` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}

function datediff($fromdate){
 // Declare and define two dates 
$date1 = strtotime($fromdate);  
$date2 = strtotime(date("Y-m-d H:i:s", strtotime("+5 hours +30 minutes")));  
  
// Formulate the Difference between two dates 
$diff = abs($date2 - $date1);  
  
  
// To get the year divide the resultant date into 
// total seconds in a year (365*60*60*24) 
$years = floor($diff / (365*60*60*24));  
  
  
// To get the month, subtract it with years and 
// divide the resultant date into 
// total seconds in a month (30*60*60*24) 
$months = floor(($diff - $years * 365*60*60*24) 
                               / (30*60*60*24));  
  
  
// To get the day, subtract it with years and  
// months and divide the resultant date into 
// total seconds in a days (60*60*24) 
$days = floor(($diff - $years * 365*60*60*24 -  
             $months*30*60*60*24)/ (60*60*24)); 
  
  
// To get the hour, subtract it with years,  
// months & seconds and divide the resultant 
// date into total seconds in a hours (60*60) 
$hours = floor(($diff - $years * 365*60*60*24  
       - $months*30*60*60*24 - $days*60*60*24) 
                                   / (60*60));  
  
  
// To get the minutes, subtract it with years, 
// months, seconds and hours and divide the  
// resultant date into total seconds i.e. 60 
$minutes = floor(($diff - $years * 365*60*60*24  
         - $months*30*60*60*24 - $days*60*60*24  
                          - $hours*60*60)/ 60);  
  
  
// To get the minutes, subtract it with years, 
// months, seconds, hours and minutes  
$seconds = floor(($diff - $years * 365*60*60*24  
         - $months*30*60*60*24 - $days*60*60*24 
                - $hours*60*60 - $minutes*60));  
if($years!='0'){
return $years.' years ago';    
}
elseif($months!='0'){
return $months.' months ago';    
}
elseif($days!='0'){
return $days.' days ago';    
}
elseif($hours!='0'){
return $hours.' hours ago';    
}
elseif($minutes!='0'){
return $minutes.' minutes ago';    
}
elseif($seconds!='0'){
return $seconds.' seconds ago';    
}
// Print the result 
//printf("%d years, %d months, %d days, %d hours, "
//     . "%d minutes, %d seconds", $years, $months, 
//             $days, $hours, $minutes, $seconds);    
}

function nulltoempty($a)
{
  if(is_null($a))
  {
     $res=''; 
      return $res;
  }
 return $a;
}
function distance($lat1, $lon1, $lat2, $lon2, $unit) {

		  $theta = $lon1 - $lon2;
		  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		  $dist = acos($dist);
		  $dist = rad2deg($dist);
		  $miles = $dist * 60 * 1.1515;
		  $unit = strtoupper($unit);

		  if ($unit == "K") {
			return ($miles * 1.609344);
		  } else if ($unit == "N") {
			  return ($miles * 0.8684);
			} else {
				return $miles;
			  }
}

function totaltrips($a){
   global $db;
  $get1 = $db->prepare("SELECT count(*) as totbooking FROM `booking` WHERE `driver_id`=? ORDER BY `id` DESC");
    $get1->execute(array($a));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totbooking'];
    return $res; 
}


function driverbitingcount($a)
{
   global $db;  
    $get1 = $db->prepare("SELECT count(*) as `totaltrip` FROM `driver_bitting` WHERE `driver`=? ORDER BY `id` DESC");
    $get1->execute(array($a));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totaltrip'];
    return $res;  
}
function totaltrips_count($a)
{
   global $db;  
    $get1 = $db->prepare("SELECT count(*) as `totaltrip` FROM `notification` WHERE `from`=? AND `confirm_status`=? ORDER BY `id` DESC");
    $get1->execute(array($a,'1'));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totaltrip'];
    return $res;
}
function getnotdetails($a, $b , $c) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `notification` WHERE `to`=? AND `booking_id`=? AND `driver_name`!=? ORDER BY `id` DESC");
    $get1->execute(array($b,$c,''));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}
function totalbooking() {
    global $db;
    $get1 = $db->prepare("SELECT count(*) as totbooking FROM `booking` ORDER BY `id` DESC");
    $get1->execute(array());
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totbooking'];
    return $res;
}
function todaybooking() {
    global $db;
    $get1 = $db->prepare("SELECT count(*) as totbooking FROM `booking` WHERE date(`date`)=? ORDER BY `id` DESC");
    $get1->execute(array(date('Y-m-d')));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totbooking'];
    return $res;
}
function pendingbooking() {
    global $db;
    $get1 = $db->prepare("SELECT count(*) as totbooking FROM `booking` WHERE `completed_status`=? ORDER BY `id` DESC");
    $get1->execute(array(0));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totbooking'];
    return $res;
}
function confirmedbooking() {
    global $db;
    // $get1 = $db->prepare("SELECT count(*) as totbooking FROM `booking` AS A , `notification` AS B WHERE A.`booking_id`=B.`booking_id` AND A.`completed_status`=? AND B.`driver_name`!='' ");
    
    $get1 = $db->prepare("SELECT count(*) as totbooking FROM `booking` WHERE `completed_status`=? AND `request_status`='1' ORDER BY `id` DESC");
    $get1->execute(array(0));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totbooking'];
    return $res;
}
function totaldriverrides($a){
   global $db;
  $get1 = $db->prepare("SELECT count(*) as totbooking FROM `booking` WHERE `driver_id`=? ORDER BY `id` DESC");
    $get1->execute(array($a));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totbooking'];
    return $res; 
}

function cancelrides($a){
   global $db;
  $get1 = $db->prepare("SELECT count(*) as totbooking FROM `notification` WHERE `cancel_status`='1' AND `to`=? ORDER BY `id` DESC");
    $get1->execute(array($a));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totbooking'];
    return $res; 
}

function confirmrides($a){
   global $db;
  $get1 = $db->prepare("SELECT count(*) as totbooking FROM `notification` B WHERE `confirm_status`='1' AND `to`=? ORDER BY `id` DESC");
    $get1->execute(array($a));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totbooking'];
    return $res; 
}


function customertrips($a){
   global $db;
    $get1 = $db->prepare("SELECT count(*) as totbooking FROM `booking` WHERE `register_id`=? AND `completed_status`=? ORDER BY `id` DESC");
    $get1->execute(array($a,'1'));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totbooking'];
    return $res;  
}
function completedbooking() {
    global $db;
    $get1 = $db->prepare("SELECT count(*) as totbooking FROM `booking` WHERE `completed_status`=? ORDER BY `id` DESC");
    $get1->execute(array(1));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get['totbooking'];
    return $res;
}
function getnotification($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `notification` WHERE `id`=? ORDER BY `id` DESC");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}
function balancetripamount($trip_type,$carid,$distance,$paidamount,$waiting_hours,$bookid,$toll_extra_fess){
 global $db;
 $days=1;
$query = $db->prepare("SELECT * FROM `cars` WHERE id='" . $carid . "' ORDER BY `id` ASC");
$query->execute();
$result1 = $query->fetch(PDO::FETCH_ASSOC);
$trip_date=getbookingdetails('trip_date',$bookid);
$drop_date=getbookingdetails('drop_date',$bookid);
$totdays=getdays($trip_date,$drop_date)+1; 

    if ($trip_type == 'oneway') {
        $per_day_target = $result1['base_fare_km'];
    } else if ($trip_type == 'round') {
        $per_day_target = $result1['round_base_fare_km'];
    }
 if ($trip_type == 'oneway') {
        if($distance>$result1['base_fare_km']){
            $dis1=$distance-$result1['base_fare_km'];
            
          $data11['base_fare'] = round($result1['base_fare']);
          $data11['additional_distance'] = round($dis1); 
          $data11['additional_fare'] = round($dis1 * $result1['per_km']); 
         // $data11['totfare'] = round($distance * $result1['base_fare']);   
        }
        else
        {
            $data11['base_fare'] = round($result1['base_fare']);
          $data11['additional_distance'] = ''; 
            $data11['additional_fare'] = ''; 
           // $data11['totfare'] = round($distance * $result1['base_fare']);    
        }


}
else
{
   if($distance>($result1['round_base_fare_km']*$totdays)){
          $dis1=$distance-($result1['round_base_fare_km']*$totdays);
          $data11['base_fare'] = $result1['round_base_fare']*$totdays;
          $data11['additional_distance'] = round($dis1); 
          $data11['additional_fare'] = round($dis1 * $result1['round_per_km']); 
         // $data11['totfare'] = round($distance * $result1['round_base_fare']);   
        }
        else
        {
            $data11['base_fare']=$result1['round_base_fare']*$totdays;
            $data11['additional_distance'] = ''; 
            $data11['additional_fare'] = ''; 
           // $data11['totfare'] = round($distance * $result1['round_base_fare']);    
        }
}
    $data11['per_Day_target'] = $per_day_target;
    $data11['distance'] = round($distance);
  if ($trip_type == 'oneway') {
    $data11['bataFee'] = $result1['beta_fee'];     
   // $data11['bataFee'] = $result1['beta_fee'] * $days;
    $data11['perKm'] = $result1['per_km'];
     }
     else
     {
        $data11['bataFee'] = $result1['round_beta_fee'] * $totdays;
    // $data11['bataFee'] = $result1['round_beta_fee'] * $days;
    $data11['perKm'] = $result1['round_per_km'];     
     }

   if($totdays>0) { 
       
   
    $data11['paid_amount']=str_replace(' Rs','',$paidamount);
  
    if($waiting_hours!='') {
    $data11['waiting_charge']=$waiting_hours*getadminuser('waiting_charge','1');
    $data11['total_price'] = round($data11['additional_fare']) + $data11['base_fare'] + ($data11['bataFee']) + $data11['waiting_charge'];
      $data11['balance_amount']=$data11['total_price']-$data11['paid_amount'];
 if($distance<=($data11['per_Day_target']*$totdays)){ 
    $data11['total_amount_to_pay']=($data11['total_price']-$data11['paid_amount']);
 }
 else
 {
     $data11['total_amount_to_pay']=$data11['total_price']-$data11['paid_amount']; 
     
    //$data11['total_amount_to_pay']=getbookingdetails('customer_booking_amount',$bookid)-$data11['paid_amount']; 
    
 }
    //$data11['total_amount_to_pay']=getbookingdetails('customer_booking_amount',$bookid)-$data11['paid_amount'];
   
   }
   else
   {
        $data11['total_price'] = round($data11['additional_fare']) + $data11['base_fare'] + ($data11['bataFee']);
          $data11['balance_amount']=$data11['total_price']-$data11['paid_amount'];
    $data11['total_days']=$totdays;
    $data11['paid_amount']=str_replace(' Rs','',$paidamount);
     
        $data11['per_day']= round($data11['base_fare'])*$totdays;
   $data11['total_price'] = round($data11['additional_fare']) + round($data11['base_fare'] + $data11['bataFee']);    
    $data11['waiting_charge']='';
    // $data11['total_amount_to_pay']=$data11['balance_amount'];
    // $data11['total_amount_to_pay']=getbookingdetails('customer_booking_amount',$bookid)-$data11['paid_amount'];
    
    
 if($distance<=($data11['per_Day_target']*$totdays)){ 
    $data11['total_amount_to_pay']=($data11['total_price']-$data11['paid_amount']);
 }
 else
 {
    //$data11['total_amount_to_pay']=(getbookingdetails('customer_booking_amount',$bookid)-$data11['paid_amount']); 
    $data11['total_amount_to_pay']=$data11['total_price']-$data11['paid_amount']; 

 }
 
 
   }
   }
   if($data11['balance_amount']<=0){
   $data11['balance_amount']=0;    
   }
   
$data11['per_hour_waiting_charge']=getadminuser('waiting_charge','1');
$data11['toll_extra_fess']=$toll_extra_fess;
$final_total_amount=$data11['paid_amount']+$data11['balance_amount']+$data11['toll_extra_fess'];
 $data11['final_total_amount']=$final_total_amount; 
 $query = "UPDATE `booking`
            SET
            toll_extra_fess='".$toll_extra_fess."',base_fare='".$data11['base_fare']."',total_days='".$totdays."',additional_distance='".$data11['additional_distance']."',additional_fare='".$data11['additional_fare']."',per_Day_target='".$data11['per_Day_target']."',distance='".$data11['distance']."',bataFee='".$data11['bataFee']."',perKm='".$data11['perKm']."',total_price='".$data11['total_price']."',paid_amount='".$data11['paid_amount']."',balance_amount='".$data11['balance_amount']."',waiting_charge='".$data11['waiting_charge']."',total_amount_to_pay='".$data11['total_amount_to_pay']."',final_total_amount='".$final_total_amount."' WHERE `id`='".$bookid."' ";

    // prepare query
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    return array("success" => "true", "error" => "false","data"=>$data11);

}
 
function balancetripamount11($trip_type,$carid,$distance,$paidamount,$waiting_hours,$bookid){
 global $db;   
 $days=1;
 $query = $db->prepare("SELECT * FROM `cars` WHERE id='" . $carid . "' ORDER BY `id` ASC");
$query->execute();
$result1 = $query->fetch(PDO::FETCH_ASSOC);
$waiting_charge=getadminuser('waiting_charge',1);
$waiting_amount=$waiting_charge*$waiting_hours;
    if ($trip_type == 'oneway') {
        $per_day_target = $result1['base_fare_km'];
    } else if ($trip_type == 'round') {
        $per_day_target = $result1['round_base_fare_km'];
    }
 if ($trip_type == 'oneway') {
  
  
        if($distance>$result1['base_fare_km']){
            $dis1=$distance-$result1['base_fare_km'];
            
          $data11['base_fare'] = round($result1['base_fare']);
          $data11['additional_distance'] = round($dis1); 
          $data11['additional_fare'] = round($dis1 * $result1['per_km']); 
         // $data11['totfare'] = round($distance * $result1['base_fare']);   
        }
        else
        {
            $data11['base_fare'] = round($result1['base_fare']);
          $data11['additional_distance'] = ''; 
            $data11['additional_fare'] = ''; 
           // $data11['totfare'] = round($distance * $result1['base_fare']);    
        }


}
else
{
     
        if($distance>$result1['round_base_fare_km']){
            $dis1=$distance-$result1['round_base_fare_km'];
            
          $data11['base_fare'] = $result1['round_base_fare'];
          $data11['additional_distance'] = round($dis1); 
          $data11['additional_fare'] = round($dis1 * $result1['round_per_km']); 
         // $data11['totfare'] = round($distance * $result1['round_base_fare']);   
        }
        else
        {
             $data11['base_fare']=$result1['round_base_fare'];
              $data11['additional_distance'] = ''; 
            $data11['additional_fare'] = ''; 
           // $data11['totfare'] = round($distance * $result1['round_base_fare']);    
        }
   
   
}
    $data11['per_Day_target'] = $per_day_target;
    $data11['distance'] = round($distance);
     if ($trip_type == 'oneway') {
    $data11['bataFee'] = $result1['beta_fee'] * $days;
    $data11['perKm'] = $result1['per_km'];
     }
     else
     {
     $data11['bataFee'] = $result1['round_beta_fee'] * $days;
    $data11['perKm'] = $result1['round_per_km'];     
     }
    $data11['total_price'] = round($data11['additional_fare']) 
    + round($data11['base_fare'] + $data11['bataFee']);
    $data11['paid_amount']=str_replace(' Rs','',$paidamount);
    $data11['balance_amount']=$data11['total_price']-$data11['paid_amount'];
    if($waiting_amount!='') {
    $data11['waiting_charge']=$waiting_amount;
     $data11['total_amount_to_pay']=$data11['balance_amount']+$data11['waiting_charge'];
    }
    else
    {
     $data11['waiting_charge']='';
     $data11['total_amount_to_pay']=$data11['balance_amount'];
    }
     $data11['per_hour_waiting_charge']=getadminuser('waiting_charge','1');
     $query = "UPDATE `booking`
            SET
                base_fare='".$data11['base_fare']."', additional_distance='".$data11['additional_distance']."',additional_fare='".$data11['additional_fare']."',per_Day_target='".$data11['per_Day_target']."',distance='".$data11['distance']."',bataFee='".$data11['bataFee']."',perKm='".$data11['perKm']."',total_price='".$data11['total_price']."',paid_amount='".$data11['paid_amount']."',balance_amount='".$data11['balance_amount']."',waiting_charge='".$data11['waiting_charge']."',total_amount_to_pay='".$data11['total_amount_to_pay']."' WHERE `id`='".$bookid."' ";

    // prepare query
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    return array("success" => "true", "error" => "false","data"=>$data11);
}
function getbookingdetails($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `booking` WHERE `id`=? ORDER BY `id` DESC");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}
function getcardetails($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `cars` WHERE `id`=? ORDER BY `id` DESC");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}
function getbooking($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `booking` WHERE `register_id`=? ORDER BY `id` DESC");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}

function getcartype($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `types` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}

function getuser($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `register` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}

function getdriver($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `driver` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}
function getadminuser($a, $b) {
    global $db;
    $get1 = $db->prepare("SELECT * FROM `users` WHERE `id`=?");
    $get1->execute(array($b));
    $get = $get1->fetch(PDO::FETCH_ASSOC);
    $res = $get[$a];
    return $res;
}
?>