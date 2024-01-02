<?php
$menu = "4";
if (isset($_REQUEST['coid'])) {
    $thispageeditid = 47;
} else {
    $thispageaddid = 47;
}
$franchisee = 'yes';
include ('../../config/config.inc.php');
$dynamic = '1';
include_once 'notification.php';
include ('../../require/header.php');

if(isset($_REQUEST['cancel']))
{
global $db;
global $sitename;
$bkkdetails = FETCH_all("SELECT * FROM `booking` WHERE `id`=?", $_REQUEST['id']);
$uquery = "INSERT INTO `cancelled_trips` (`cancel_reason`,`customer_booking_amount`,`triptype`,`register_id`, `pickup_address`, `drop_address`, `booking_km`, `car_id`, `trip_date`, `customer_paid_booking_amount`) VALUES ('".$_REQUEST['cancel_reason']."','".$bkkdetails['customer_booking_amount']."','".$bkkdetails['triptype']."','".$bkkdetails['register_id']."','".$bkkdetails['pickup_address']."','".$bkkdetails['drop_address']."','".$bkkdetails['booking_km']."','".$bkkdetails['car_id']."','".$bkkdetails['trip_date']."','".$bkkdetails['customer_paid_booking_amount']."') ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();

	
// User Notification 


$notification = new Notification();
$message="Sorry, our service is currently unavailable in your area. So the booking is rejected";
$messagenoti="Sorry, our service is currently unavailable in your area. So the booking is rejected";
						$title = 'DROPTAXI';

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
						
				$firebase_token = getregisterform('device_key',$bkkdetails['register_id']);
				 $firebase_api =getusers('firebase_api_key','1');
						
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
			
			if($resultjson['success']=='1')
			{
	
$query = "INSERT INTO `notification` SET
                    `booking_id`='".$bkkdetails['id']."',`from`='admin',`to`='".$bkkdetails['register_id']."',`title`='".$title."',`message`='".$message."',`type`='Admin-User' ";
$stmt = $db->prepare($query);
$stmt->execute();
				}

      // User Notification
      
$delete = $db->prepare("DELETE FROM `booking` WHERE `id` = ? ");
$delete->execute(array($_REQUEST['id']));

$delete1 = $db->prepare("DELETE FROM `notification` WHERE `booking_id` = ? AND `message`=?");
$delete1->execute(array($_REQUEST['id'],'Hi, Your request received. Driver And Vehicle details will be share 3 hours before pickup time. Thank you'));

 echo '<script>alert("Cancelled Successfully");window.location.href = "'.$sitename.'master/cancelledtrips.htm";</script>'; 


}



if (!function_exists('compressImage')) {

    function compressImage($source, $destination, $quality) {
        // Get image info 
        $imgInfo = getimagesize($source);
        $mime = $imgInfo['mime'];
        // Create a new image from file 
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($source);
                break;
            default:
                $image = imagecreatefromjpeg($source);
        }

        // Save image 
        imagejpeg($image, $destination, $quality);
        // Return compressed image 
        return $destination;
    }

}

if(isset($_REQUEST['createaccount'])) {
global $db;
@extract($_REQUEST);
    
$status=1;

	
$resa = $db->prepare("INSERT INTO `register` (`name`,`mobileno`,`address`,`status`,`additional_no`) VALUES (?,?,?,?,?)");
$resa->execute(array($name,$mobileno,$address,$status,$additional_no));
 $insid = $db->lastInsertId();    
 if($_REQUEST['id']=='') {
$url="addbooking.htm?pid=".$insid; 
}
else
{
//$url=$_REQUEST['id']/"edittrip.htm?pid=".$insid;  
}
 echo "<script>window.location.assign('".$url."')</script>";  
}

if (isset($_REQUEST['submit'])) {
    @extract($_REQUEST);
    $getid = $_REQUEST['banid'];
    $ip = $_SERVER['REMOTE_ADDR'];
  $link1 = FETCH_all("SELECT `id` FROM `register` WHERE `mobileno`=?", $contact_Number);
$cid=$link1['id'];
	
if($_REQUEST['id']!='')
{
	$uid=$_REQUEST['id'];
$resa = $db->prepare("UPDATE `booking` SET `customer_booking_amount`=?,`register_id`=?,`contact_Number`=?,`additional_number`=?,`pickup_address`=?,`drop_address`=?,`passenger`=?,`trip_date`=?,`trip_time`=?,`car_id`=?,`triptype`=?,`comments`=? WHERE `id`=? ");
$resa->execute(array($estimated_fare,$cid,$contact_Number,$additional_number,$pickup_place,$drop_place,$passenger,date('Y-m-d',strtotime($travel_date)),$pickup_time,$cartype,$trip_type,$comments,$uid));
$lasid=$db->lastInsertId();	
			
// update user alternate no
$auquery = "UPDATE `register` SET `additional_no`='".$additional_number."' WHERE `id`='".$cid."' ";
$austmt = $db->prepare($auquery);
$austmt->execute();
// update user alternate no
}
else
{		
$resa = $db->prepare("INSERT INTO `booking` (`customer_booking_amount`,`register_id`,`contact_Number`,`additional_number`,`pickup_address`,`drop_address`,`passenger`,`trip_date`,`trip_time`,`car_id`,`triptype`,`comments`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
$resa->execute(array($estimated_fare,$cid,$contact_Number,$additional_number,$pickup_place,$drop_place,$passenger,date('Y-m-d',strtotime($travel_date)),$pickup_time,$cartype,$trip_type,$comments));
$lasid=$db->lastInsertId();

	
		
// update user alternate no
$auquery = "UPDATE `register` SET `additional_no`='".$additional_number."' WHERE `id`='".$cid."' ";
$austmt = $db->prepare($auquery);
$austmt->execute();
// update user alternate no
	
	
	
	// User Notification 


$notification = new Notification();
$message="Hi, You Booking is Added By Admin.";
$messagenoti="Hi, You Booking is Added By Admin.";
						$title = 'DROPTAXI';

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
						
				$firebase_token = getregisterform('device_key',$cid);
				 $firebase_api =getusers('firebase_api_key','1');
						
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
			
			if($resultjson['success']=='1')
			{
	
$query = "INSERT INTO `notification` SET
                    `booking_id`='".$lasid."',`from`='admin',`to`='".$cid."',`title`='".$title."',`message`='".$message."',`type`='Admin-User' ";
$stmt = $db->prepare($query);
$stmt->execute();
				}

      // User Notification
      
}
$msg='<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Updated</div>';
    
}
if($_REQUEST['pid']!='')
{
    $pid=$_REQUEST['pid'];
$link1 = FETCH_all("SELECT * FROM `register` WHERE `id`=?", $pid);
$cname=$link1['name'].'-'.$link1['mobileno'];
$cname1=$link1['name'];
$cmobile1=$link1['mobileno'];
$cano=$link1['additional_no'];
}
?>

<style>

.content-wrapper, .right-side {
    min-height: 100%;
    background-color: #fff;
    z-index: 800;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 28px;
    font-size: 13px;
    font-weight: bold;
}
</style>
  <!-- Select2 -->

     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" >
   function checkdelete(name)
    {
        if (confirm("Do you want to delete the User"))
            {
                return true;
            }
            else
            {
                return false;
            }
   }
</script>

<!-- Clock Picker -->
 <link rel="stylesheet" href="<?php echo $sitename; ?>clockpicker/clockpicker-12-hour-option.css" />
<!-- Clock Picker -->
  <div class="content-page">
        
<div class="content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Booking</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Booking</a></li>
                                        <li class="breadcrumb-item active"><?php
                            if (isset($_REQUEST['id'])) {
                                echo "View";
                            } else {
                                echo "Add";
                            }
                            ?> Booking </li>
                                    </ol>
            <?php if($_REQUEST['id']!='') {?>
                               <div class="state-information d-none d-sm-block">
                                    <h4 class="page-title"><a href="<?php echo $sitename; ?>master/booking.htm">Back to Listing</a></h4>
                                    </div>
                                <?php } ?>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card m-b-20">
                                    <div class="card-body">
        
                                        <?php echo $msg; ?>
                                            <form name="department" id="department" action="#" method="post" enctype="multipart/form-data" autocomplete="off" >
                                    <div class="box box-info">
                                        <div class="box-body">
                                             
                                            <div class="row">
                                            <div class="col-md-12"><h5>Booking Details</h5></div>    
                                            </div>
                                             <br>
                                            <div class="row">
                                            <div class="col-md-2"><label>Search Customer</label> </div> 
                                             <div class="col-md-4">
                                            <table width="100%">
                     
                        <tr>
                        <td  style="margin-top: 5px;">
                    
                             <select name="customer_name" <?php if($_REQUEST['type']=='view') { ?> readonly="readonly" <?php } ?> class="form-control select2"  style="font-weight: bold; font-size:13px;" onchange="getno(this.value);">
                             <option value="">Select</option>
                             <?php
$customer = pFETCH("SELECT * FROM `register` WHERE `status`=?", '1');
while ($customerfetch = $customer->fetch(PDO::FETCH_ASSOC)) 
{
?>
 <option value="<?php echo $customerfetch['name'].'-'.$customerfetch['mobileno']; ?>" <?php if($customerfetch['name'].'-'.$customerfetch['mobileno']==getbooking('register_id',$_REQUEST['id']) || $cname==$customerfetch['name'].'-'.$customerfetch['mobileno']) { ?> selected="selected" <?php } ?>><?php echo $customerfetch['name'].'-'.$customerfetch['mobileno']; ?></option>
<?php } ?>                          
                             </select>
                             
                   
                        </td>
                        <td style="vertical-align:bottom;">
                        <?php if($_REQUEST['type']!='view') { ?>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal" style="height:36px;"><i class="fa fa-plus" aria-hidden="true" alt="Add Customer" title="Add Customer"></i></button>
                        <?php } ?>
                        </td>
                        </tr>
                        </table>
                
                                                </div> 
                                            </div>
                                            <br>
                                            <div class="row">
                                                 <div class="col-md-2"><label>Customer Name&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                              <div class="col-md-4">
                                            
                                                  <input type="text" name="cname" id="customer_name" class="form-control" value="<?php if($cname1!='') { echo $cname1; } else { echo getregisterform('name',getbooking('register_id',$_REQUEST['id'])); } ?>">
                                          </div>
                                                 <div class="col-md-2"><label>Contact Number&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                              <div class="col-md-4">
                                            
                                                  <input type="text" name="contact_Number" id="contact_Number" class="form-control" required="required" value="<?php if($cmobile1!='') { echo $cmobile1; } else { echo getregisterform('mobileno',getbooking('register_id',$_REQUEST['id']));   } ?>">
                                          </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                              
                                                 <div class="col-md-2"><label>Additional Number</label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text"  name="additional_number" value="<?php if($cano!='') { echo $cano; } else { echo getbooking('additional_number',$_REQUEST['id']); } ?> "></div> 
                                            
                                             <div class="col-md-2"><label>Pickup Place&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                              <div class="col-md-4">
												  <?php if($_REQUEST['id']=='')
                                                  {
                                                  ?>
												   <select name="pickup_place" class="form-control select2" required="required">
                                                <option value="">Select</option>
 <?php
$place = pFETCH("SELECT * FROM `place` WHERE `status`=?", '1');
while ($placefetch = $place->fetch(PDO::FETCH_ASSOC)) 
{
?>
 <option value="<?php echo $placefetch['id']; ?>" <?php if($placefetch['id']==getbooking('pickup_address',$_REQUEST['id']) ) { ?> selected="selected" <?php } ?>><?php echo $placefetch['place']; ?></option>
<?php } ?>                     
                                            </select>
												  <?php
                                                  } elseif(getplace('place',$ford['pickup_address'])!='')
                                                  {
                                                  ?>
												   <select name="pickup_place" class="form-control select2" required="required">
                                                <option value="">Select</option>
 <?php
$place = pFETCH("SELECT * FROM `place` WHERE `status`=?", '1');
while ($placefetch = $place->fetch(PDO::FETCH_ASSOC)) 
{
?>
 <option value="<?php echo $placefetch['id']; ?>" <?php if($placefetch['id']==getbooking('pickup_address',$_REQUEST['id']) ) { ?> selected="selected" <?php } ?>><?php echo $placefetch['place']; ?></option>
<?php } ?>                     
                                            </select>
												  <?php
                                                  }
                                                  else
                                                  {
                                                  	?>
												  <textarea name="pickup_place" class="form-control"><?php echo getbooking('pickup_address',$_REQUEST['id']); ?></textarea>
												 
												  <?php 
                                                  }
                                                   ?>
												  
                                           
                                                 

                                          </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                              
                                                 <div class="col-md-2"><label>Drop Place&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4">
  <?php if($_REQUEST['id']=='')
                                                  {
                                                  ?>
												   <select name="drop_place" class="form-control select2" required="required">
                                                <option value="">Select</option>
 <?php
$place = pFETCH("SELECT * FROM `place` WHERE `status`=?", '1');
while ($placefetch = $place->fetch(PDO::FETCH_ASSOC)) 
{
?>
 <option value="<?php echo $placefetch['id']; ?>" <?php if($placefetch['id']==getbooking('drop_address',$_REQUEST['id']) ) { ?> selected="selected" <?php } ?>><?php echo $placefetch['place']; ?></option>
<?php } ?>                     
                                            </select>
												  <?php
                                                  } elseif(getplace('place',$ford['drop_address'])!='')
                                                  {
                                                  ?>
												   <select name="drop_place" class="form-control select2" required="required">
                                                <option value="">Select</option>
 <?php
$place = pFETCH("SELECT * FROM `place` WHERE `status`=?", '1');
while ($placefetch = $place->fetch(PDO::FETCH_ASSOC)) 
{
?>
 <option value="<?php echo $placefetch['id']; ?>" <?php if($placefetch['id']==getbooking('drop_address',$_REQUEST['id']) ) { ?> selected="selected" <?php } ?>><?php echo $placefetch['place']; ?></option>
<?php } ?>                     
                                            </select>
												  <?php
                                                  }
                                                  else
                                                  {
                                                  	?>
												 	  <textarea name="drop_place" class="form-control"><?php echo getbooking('drop_address',$_REQUEST['id']); ?></textarea>
												 
											
												  <?php 
                                                  }
                                                   ?>
                                        </div>
                                                
                                                <div class="col-md-2"><label>How many passengers&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" required="required" name="passenger" value="<?php echo getbooking('passenger',$_REQUEST['id']); ?>"></div> 
                                            </div>
                                            <br>
                                            <div class="row">
                                                
                                                 <div class="col-md-2"><label>What Type Of Cab&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                              <div class="col-md-4">
                                            <select name="cartype" class="form-control">
                             <option value="">Select</option>
                             <?php
$customer = pFETCH("SELECT * FROM `cars` WHERE `status`=?", '1');
while ($customerfetch = $customer->fetch(PDO::FETCH_ASSOC)) 
{
?>
 <option value="<?php echo $customerfetch['id']; ?>" <?php if($customerfetch['id']==getbooking('car_id',$_REQUEST['id'])) { ?> selected="selected" <?php } ?>><?php echo $customerfetch['name']; ?></option>
<?php } ?>                          
                             </select>
                                          </div>
                                                
                                                <div class="col-md-2"><label>Type of Trip&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4">
                                             <select name="trip_type" required="required" class="form-control">
                                                 <option value="">Select</option>
                                                 <option value="oneway" <?php if("oneway"==getbooking('triptype',$_REQUEST['id'])) { ?> selected="selected" <?php } ?>>Oneway</option>
                                                  <option value="round" <?php if("round"==getbooking('triptype',$_REQUEST['id'])) { ?> selected="selected" <?php } ?>>Round</option>
                                                 </select>
                                             </div> 
                                          <!--  <div class="col-md-2"><label>Drop Date<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="date" required="required" name="drop_date"></div>-->
                                           
                                                  
                                                
                                            </div>
                                             <br>

                                            <div class="row">
                                   
                                                  <div class="col-md-2"><label>Travel Date<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="date" required="required" name="travel_date" value="<?php echo getbooking('trip_date',$_REQUEST['id']); ?>"></div>
                                                
                                                <div class="col-md-2"><label>Pickup Time<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control"  data-default="08:45" id="input-12-hour" required="required" name="pickup_time" value="<?php echo date('h:i A',strtotime(getbooking('trip_time',$_REQUEST['id']))); ?>"></div>
                                            </div>
                                            <br>
                                                                                            <div class="row">
                                                    <div class="col-md-2"><label>Estimated fare<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" required="required" name="estimated_fare" value="<?php echo getbooking('customer_booking_amount',$_REQUEST['id']); ?>"></div>
                                                    
                                                    <div class="col-md-2"><label>Comments</label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text"  name="comments" value="<?php echo getbooking('comments',$_REQUEST['id']); ?>"></div>
                                         
                                            </div>
                                            <br>
											<div class="row">
											 <div class="col-md-2"  align="left" style="display: none;" id="c1<?php echo $_REQUEST['id']; ?>">
                                                    <label>Cancel Reason</label>    
                                                    </div> 
                                                    <div class="col-md-4" style="display: none;" id="c2<?php echo $_REQUEST['id']; ?>" align="left">
                                                  <textarea name="cancel_reason" id="cancel_reason" class="form-control"></textarea>
                                                    </div>
											</div>
											
                                          <!--  <div class="col-md-2"><label>Booking KM<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" required="required" name="booking_km"></div> 
                                           
                                                    
                                                 <div class="col-md-2"><label>Booking Amount<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" required="required" name="customer_booking_amount"></div> 
                                           
                                            </div>-->
                                             <br>
                                                <!--<div class="row">
                                                    <div class="col-md-2"><label>Customer Paid Booking Amount<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" required="required" name="cuspaidamt"></div> 
                                           
                                            
                                                 <div class="col-md-2"><label>Estimated fare<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" required="required" name="quote_amt"></div> 
                                         
                                            </div>
                                             <br>-->
                                        <!-- <div class="row">-->
                                        <!--    <div class="col-md-2"><label>Having AC ?&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> -->
                                        <!--     <div class="col-md-4">-->
                                        <!--     <select name="ac_status" class="form-control">-->
                                        <!--         <option value="">Select</option>-->
                                        <!--    <option value="Yes">Yes</option>        <option value="No">No</option>-->
                                        <!--     </select>-->
                                        <!--     </div> -->
                                          
                                        <!--    </div>-->
                                          
                                        <!--<br>-->
                                        
                                        <div>
                                             <button type="submit" name="submit" id="submit" class="btn btn-primary waves-effect waves-light"><?php
                                if ($_REQUEST['id'] != '') {
                                    echo 'UPDATE';
                                } else {
                                    echo 'SUBMIT';
                                }
                                ?></button>
                                <?php if($_REQUEST['id']!='') { ?>
                                
                                        <button type="button" class="btn btn-secondary waves-effect m-l-5" name="cancel" id="cancel" onclick="getcan(<?php echo $_REQUEST['id']; ?>);">CANCEL</button>
											<?php } ?>
                                    </div>
                                        </div><!-- /.box-body -->

                                      
                                        
                                    </div>
                                </form>     
                                        
        <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->

      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><strong>Add New Customer</strong></h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
        
        </div>
        <form name="mform" method="post">
       <div class="row" style="padding:10px;">
                        
                        
                        <div class="col-md-6">
                            <label>Name <span style="color:#FF0000;">*</span></label>
                            <input type="text"  required="required" name="name" id="name" placeholder="Enter Name" class="form-control" />
                        </div>
                         <div class="col-md-6">
                            <label>Mobile Number <span style="color:#FF0000;">*</span></label>
                            <input type="text"  required="required" name="mobileno" id="mobileno" placeholder="Enter Mobile No" class="form-control"  />
                        </div>
                       

                        </div>
                        <div class="row" style="padding:10px;">
                             <div class="col-md-6">
                            <label>Additional Number</label>
                            <input type="text"  name="additional_no" placeholder="Enter Additional Number" class="form-control" />
                        </div>
                        </div>
                      
            <div class="row" style="padding:10px;">
                        
                        
                        <div class="col-md-12">
                            <label>Address <span style="color:#FF0000;">*</span></label>
                            <textarea name="address" required="required" class="form-control"></textarea>
                        </div>
            </div>
        <div class="modal-footer">
          <button type="submit" name="createaccount" class="btn btn-primary waves-effect waves-light" name="newcustomer">Save</button> &nbsp; &nbsp;&nbsp; <button type="button" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">Close</button>
        </div>
       </form>
      </div>
      
    </div>
  </div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
        

                    </div> <!-- container-fluid -->

                </div> <!-- content -->



        </div>
<?php include ('../../require/footer.php'); ?>
 <!-- Select2 -->
 <link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet">
<script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>

    <!-- Select2 -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
         
<script>
		function getcan(a){
        $("#c1"+a).css("display","block")
        $("#c2"+a).css("display","block")
        $('#cancel_reason').prop('required',true);
      $('#cancel').removeAttr("type").attr("type", "submit");
     // return confirm('Do you really want to confirm the action?');
        // $("#").show();   
    }
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
</script>
<script>
    function getno(a)
    {
var result = a.split('-');
$('#customer_name').val(result[0]);
$('#contact_Number').val(result[1]);
$.ajax({
            url: "<?php echo $sitename; ?>pages/master/proprice.php",
            data: {mobileno: result[1]},
            success: function (data) {
           
                $('#additional_no').val(data);
               
             
            }
        });


 }
    function click1()
    {

        $('#demo').css("display", "block");

    }
</script>

<!-- Clockpicker-->
    <script src="<?php echo $sitename; ?>clockpicker/clockpicker-12-hour-option.js"></script>
    <script>
	$(function() {
		$(".input-12-hour-icon-button").button({
			icons: {
				primary: ".ui-icon-clock"
		},
			text: false
		})
	});
  </script>
<script>
	var input1 = $('#input-12-hour');
	input1.clockpicker({
	    twelvehour: true,
	    donetext: 'Done'
	});

	</script>

<!-- Clockpicker-->