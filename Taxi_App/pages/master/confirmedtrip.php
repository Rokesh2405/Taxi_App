<?php
$menu = "4";
include ('../../config/config.inc.php');
$dynamic = '1';
//$datepicker = '1';
$datatable = '1';

include ('../../require/header.php');
include_once 'notification.php';


if(isset($_REQUEST['read']))
{
global $db;
global $sitename;
$uquery = "UPDATE `notification` SET
                    read_status='1' WHERE `id`='".$_REQUEST['read']."' ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();  
 //echo '<script>alert("Confirmed Successfully");window.location.href = "'.$sitename.'master/booking.htm";</script>'; 
}

if (isset($_REQUEST['delete']) || isset($_REQUEST['delete_x'])) {
    $chk = $_REQUEST['chk'];
    $chk = implode('.', $chk);
   
    $msg = delregisterform($chk);
}
if($_REQUEST['aid']!=''){
global $db;  

$uquery = "UPDATE `booking` SET
               booking_status='1',`driver_charge`='".getnotification('driver_charge',$_REQUEST['aid'])."',`driver_id`='".getnotification('to',$_REQUEST['aid'])."' WHERE `id`='".getnotification('booking_id',$_REQUEST['aid'])."' ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();

}

if($_REQUEST['bookingid']!='')
{
global $db;
$bkkdetails = FETCH_all("SELECT * FROM `booking` WHERE `id`=?", $_REQUEST['bookingid']);
$uquery = "INSERT INTO `cancelled_trips` (`customer_booking_amount`,`triptype`,`register_id`, `pickup_address`, `drop_address`, `booking_km`, `car_id`, `trip_date`, `customer_paid_booking_amount`) VALUES ('".$bkkdetails['customer_booking_amount']."','".$bkkdetails['triptype']."','".$bkkdetails['register_id']."','".$bkkdetails['pickup_address']."','".$bkkdetails['drop_address']."','".$bkkdetails['booking_km']."','".$bkkdetails['car_id']."','".$bkkdetails['trip_date']."','".$bkkdetails['customer_paid_booking_amount']."') ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();

// User Notification 


$notification = new Notification();
$message="Hi, You Booking is Cancelled By Admin.";
$messagenoti="Hi, You Booking is Cancelled By Admin.";
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
$delete->execute(array($_REQUEST['bookingid']));


$delete1 = $db->prepare("DELETE FROM `notification` WHERE `booking_id` = ? AND `message`=?");
$delete1->execute(array($_REQUEST['bookingid'],'Hi, Your request received. Driver And Vehicle details will be share 3 hours before pickup time. Thank you'));

 echo '<script>alert("Cancelled Successfully");window.location.href = "https://webtoall.in/droptaxi/master/booking.htm";</script>'; 
 
}
if($_REQUEST['sendid']!=''){
    global $db;
 
$title="Booking Acceptance Request from Admin";
$message="Checked and placed your quotation amount";

$readquery = "UPDATE `notification` SET `read_status`='1' WHERE `booking_id`='".$_REQUEST['sendid']."' ";
$readstmt = $db->prepare($readquery);
$readstmt->execute();


$driver = pFETCH("SELECT * FROM `driver` WHERE `status`=? ", 1);
while ($driverfetch = $driver->fetch(PDO::FETCH_ASSOC)) {
$notification = new Notification();
$title1="DROPTAXI";
$message="Booking Acceptance Request from Admin.Checked and placed your quotation amount";
$messagenoti="Booking Acceptance Request from Admin.Checked and placed your quotation amount";
					
			// Add Notification		
$query = "INSERT INTO `notification` SET
`booking_id`='".$_REQUEST['sendid']."',`from`='admin',`to`='".$driverfetch['id']."',`title`='".$title1."',`message`='".$message."',`type`='Admin-Driver' ";
$stmt = $db->prepare($query);
$stmt->execute();
$lasid=$db->lastInsertId();
 $title="DROPTAXI - Booking No :".$lasid;
	// Add Notification		
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
						
					$firebase_token =$driverfetch['device_key'];
				 $firebase_api =getusers('driver_firebase_api_key','1');
						
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
// $query = "INSERT INTO `notification` SET
//                     `booking_id`='".$data->booking_id."',`from`='admin',`to`='".$driverfetch['id']."',`title`='".$title."',`message`='".$message."',`type`='Admin-Driver' ";
// $stmt = $db->prepare($query);
// $stmt->execute();
				}
 // Notification

}

$uquery = "UPDATE `booking` SET
                    request_status='1' WHERE `id`='".$_REQUEST['sendid']."' ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();

$msg = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Notification Send to Drivers</div>';
  
}

if(isset($_REQUEST['send']))
{
 @extract($_REQUEST);
 global $db;

	
if($driver!='All'){
	$notification = new Notification();
$title1="DROPTAXI";
$message="Booking Acceptance Request from Admin.Checked and placed your quotation amount";
$messagenoti="Booking Acceptance Request from Admin.Checked and placed your quotation amount";
					
	
 $title="DROPTAXI - Booking No :".$_REQUEST['booking_id'];
	// Add Notification		
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
						
					$firebase_token =getdriver('device_key',$driver);
				 $firebase_api =getusers('driver_firebase_api_key','1');
						
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
// $query = "INSERT INTO `notification` SET
//                     `booking_id`='".$data->booking_id."',`from`='admin',`to`='".$driverfetch['id']."',`title`='".$title."',`message`='".$message."',`type`='Admin-Driver' ";
// $stmt = $db->prepare($query);
// $stmt->execute();
				}
}
else{
$driverss = pFETCH("SELECT * FROM `driver` WHERE `status`=? ", 1);
while ($driverfetch = $driverss->fetch(PDO::FETCH_ASSOC)) {
$notification = new Notification();
$title1="DROPTAXI";
$message="Booking Acceptance Request from Admin.Checked and placed your quotation amount";
$messagenoti="Booking Acceptance Request from Admin.Checked and placed your quotation amount";
					
	
 $title="DROPTAXI - Booking No :".$_REQUEST['booking_id'];
	// Add Notification		
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
						
					$firebase_token =$driverfetch['device_key'];
				 $firebase_api =getusers('driver_firebase_api_key','1');
						
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
// $query = "INSERT INTO `notification` SET
//                     `booking_id`='".$data->booking_id."',`from`='admin',`to`='".$driverfetch['id']."',`title`='".$title."',`message`='".$message."',`type`='Admin-Driver' ";
// $stmt = $db->prepare($query);
// $stmt->execute();
				}
 // Notification

}
}
                      $title1="DROPTAXI - Admin Assign to Drive";
                        $message="Confirm to get this booking";
					    $messagenoti="Confirm to get this booking";
					
					$query = "INSERT INTO `notification` SET
                    `booking_id`='".$booking_id."',`from`='admin',`to`='".$driver."',`title`='".$title1."',`message`='".$message."',`type`='Admin-Driver' ";
$stmt = $db->prepare($query);
$stmt->execute();

$lastid=$db->lastInsertId();

$uquery = "UPDATE `booking` SET
                     booking_status='1',`driver_charge`='".$_REQUEST['quote_amount']."',`driver_id`='".$_REQUEST['driver']."', request_status='1', `quote_amount`='".$_REQUEST['quote_amount']."' WHERE `id`='".$_REQUEST['booking_id']."' ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();

$msg = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Notification Send to All Drivers</div>';

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
    function validcheck(name)
    {
        var chObj = document.getElementsByName(name);
        var result = false;
        for (var i = 0; i < chObj.length; i++) {
            if (chObj[i].checked) {
                result = true;
                break;
            }
        }
        if (!result) {
            return false;
        } else {
            return true;
        }
    }

    function checkdelete(name)
    {
        if (validcheck(name) == true)
        {
            if (confirm("Please confirm you want to Delete this User(s)"))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else if (validcheck(name) == false)
        {
            alert("Select the check box whom you want to delete.");
            return false;
        }
    }

</script>
<script type="text/javascript">
    function checkall(objForm) {
        len = objForm.elements.length;
        var i = 0;
        for (i = 0; i < len; i++) {
            if (objForm.elements[i].type == 'checkbox') {
                objForm.elements[i].checked = objForm.check_all.checked;
            }
        }
    }
</script>

<style type="text/css">
    .row { margin:0;}
    #normalexamples tbody tr td:nth-child(1),tbody tr td:nth-child(3), tbody tr td:nth-child(4),tbody tr td:nth-child(5),tbody tr td:nth-child(6),tbody tr td:nth-child(7) {
        text-align:center;
    }
    .modal-content{
    width: 1004px;margin-left: -255px;    
    }
    
</style>
       
        <div class="content-page">
        
  
  <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="page-title-box">
                                    <h4 class="page-title">Confirmed Trip</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Master </a></li>
                                        <li class="breadcrumb-item active">Confirmed Trip</li>
                                    </ol>
            
                                  
                                    
                                </div>
                            </div>
                              <div class="col-md-6">
                                  <!--<br>-->
                                  <!--  <a href="<?php echo $sitename.'master/userexport.htm'; ?>" style="color:blue;font-weight:bold;"> <button type="button" name="export" id="export" class="btn btn-success" style="float:left; float:right;">Export as Excel</button>    -->
                                  <!--             </a>    -->
                                    </div>    
                        </div>
                        
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card m-b-20">
                                    <div class="card-body">
                                   
<!--
                                        <h4 class="mt-0 header-title">Default Datatable</h4>
                                        <p class="text-muted m-b-30">DataTables has most features enabled by
                                            default, so all you need to do to use it with your own tables is to call
                                            the construction function: <code>$().DataTable();</code>.
                                        </p>-->
<?php echo $msg; ?>
<form name="listform" id="listform" method="post">
<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                      
                                            <thead>
                                <tr align="center">
                                    <th style="width:5%;">S.id</th>
                                    <th style="width:15%;">Trip Date</th>
                                    <th style="width:20%;">Trip Time</th>
                                    <th style="width:20%;">Pickup</th>
                                      <th style="width:20%;">Drop</th>
                                       <th style="width:20%;">Cab Name</th>
									<th style="width:10%;">Cancel</th>
                                    <th data-sortable="false" align="center" style="text-align: center; padding-right:0; padding-left: 0; width: 10%;">View</th>
                                <!--  <th data-sortable="false" align="center" style="text-align: center; padding-right:0; padding-left: 0; width: 10%;">Action</th> -->
                                </tr>
                            </thead> 
                            <tbody>
                             <?php
                                $o = '1';
$ord = $db->prepare("SELECT * FROM `booking` WHERE `confirm_status`='1' AND `completed_status`='0' AND `accept_status`='0' ORDER BY `trip_date` ASC, `trip_time` ");	
$ord->execute();
$ordnum = $ord->rowCount(); 
if($ordnum>0) { 
                                while ($ford = $ord->fetch(PDO::FETCH_ASSOC)) {
                                    if($ford['view_status']=='0') {
                                   $vsttaus='style="font-weight:bold;"';
                                   }
                                   else
                                   {
                                   	$vsttaus='';
                                   }
                                    ?>
                                    <tr <?php echo $vsttaus; ?>>
                                    <td><?php echo $o; ?></td> 
                                    <td><?php echo date('d-M-Y',strtotime($ford['trip_date'])); ?></td>   
                                       <td><?php echo date('h:i a',strtotime($ford['trip_time'])); ?></td> 
                                          <td><?php if(getplace('place',$ford['pickup_address'])!='')
                                                  {
                                                  	echo getplace('place',$ford['pickup_address']);
                                                  }
                                                  else
                                                  {
                                                  	echo $ford['pickup_address'];
                                                  }
                                                   ?></td> 
<td><?php if(getplace('place',$ford['drop_address'])!='')
                                                  {
                                                  	echo getplace('place',$ford['drop_address']);
                                                  }
                                                  else
                                                  {
                                                  	echo $ford['drop_address'];
                                                  }
                                                   ?></td> 
<td><?php echo getcar('name',$ford['car_id']); ?></td>
										 <td>
											   <a href="<?php echo $sitename.'master/'.$ford['id'].'/editbooking.htm'; ?>" style="color:blue;">
											  <!-- <a data-toggle="modal" data-target="#book<?php echo $ford['id']; ?>" style="color:#62A3FF;cursor:pointer;">-->
												   Click to Cancel</a>
										</td>
                                           <td>
											   <a href="<?php echo $sitename.'master/'.$ford['id'].'/viewconfirm_trip.htm'; ?>" style="color:blue;">
											  <!-- <a data-toggle="modal" data-target="#book<?php echo $ford['id']; ?>" style="color:#62A3FF;cursor:pointer;">-->
												   Booking Details</a>
                                            <div id="book<?php echo $ford['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <form name="modalform" id="modalform" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Booking Details</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                </div>
                                                <div class="modal-body" style="line-height: 10px;">
                                                   <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Customer Name</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left">
                                                       <input type="hidden" name="booking_id" value="<?php echo $ford['id']; ?>">
                                                  <?php echo getregisterform('name',$ford['register_id']); ?>   
                                                    </div> 
                                                    <div class="col-md-3" align="left">
                                                    <label>Contact Number</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left">
                                                  <?php echo getregisterform('mobileno',$ford['register_id']); ?>   
                                                    </div> 
                                                   </div>
                                                   
                                                   <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Car Name</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left">
                                                  <?php echo getcar('name',$ford['car_id']); ?>   
                                                    </div> 
                                                    <div class="col-md-3" align="left">
                                                    <label>Trip Type</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left">
                                                  <?php echo $ford['triptype']; ?>   
                                                    </div> 
                                                   </div>
                                                
                                                   <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Trip Date</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left">
                                                  <?php echo date('d-M-Y',strtotime($ford['trip_date'])); ?>   
                                                    </div> 
                                                     <div class="col-md-3" align="left">
                                                    <label>Trip Time</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left">
                                                  <?php echo date('h:i a',strtotime($ford['trip_time'])); ?>   
                                                    </div>
                                                </div>
                                               
                                                <div class="row">
                                                       <div class="col-md-3" align="left">
                                                    <label>Rental Amount</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left">
                                                  <?php echo $ford['customer_booking_amount']; ?>   
                                                    </div> 
                                                   </div>
                                                  
                                                   <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Pickup Address</label>    
                                                    </div> 
                                                    <div class="col-md-8"  align="left">
                                               <?php if(getplace('place',$ford['pickup_address'])!='')
                                                  {
                                                  	echo getplace('place',$ford['pickup_address']);
                                                  }
                                                  else
                                                  {
                                                  	echo $ford['pickup_address'];
                                                  }
                                                   ?>
                                                    </div> 
                                                </div>
                                                <div class="row">
                                                     <div class="col-md-3"  align="left">
                                                    <label>Drop Address</label>    
                                                    </div> 
                                                    <div class="col-md-8"  align="left">
                                                  <?php if(getplace('place',$ford['drop_address'])!='')
                                                  {
                                                  	echo getplace('place',$ford['drop_address']);
                                                  }
                                                  else
                                                  {
                                                  	echo $ford['drop_address'];
                                                  }
                                                   ?>
														
                                                    </div>
                                                   </div>
                                                 
                                                 

                                                   <div class="row">

													   <div class="col-md-3" align="left">
                                                    <label>Assign Driver</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
														<select name="driver" class="form-control select2" required="required">
															<option value="">Select</option>
															<option value="All">All</option>
															<?php
									$driver = pFETCH("SELECT * FROM `driver` WHERE `status`=? ", 1);
                                    while ($driverfetch = $driver->fetch(PDO::FETCH_ASSOC)) {
									?>
															<option value="<?php echo $driverfetch['id']; ?>" <?php if($ford['driver_id']==$driverfetch['id']) { ?> selected="selected" <?php } ?>><?php echo $driverfetch['driver_name']; ?></option>
															<?php } ?>
														</select>
                                                 
														
                                                    </div> 
                                                   </div>
                                                  
                                                </div>
                                                 <?php if($ford['request_status']=='0') { ?>
                                                <div class="modal-footer">
                                                   <input type="hidden" name="booking_id" class="form-control" value="<?php echo $ford['id']; ?>">
                                                    <button type="submit" class="btn btn-info waves-effect waves-light" name="send" id="send">Send to Driver</button>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </form>
                                        </div>
                                    </div><!-- /.modal -->
                                           </td>
                                          <!--  <td>
                                        
                                              <a href="<?php echo $sitename.'master/'.$ford['id'].'/booking.htm'; ?>"><button type="button" class="btn btn-info waves-effect waves-light" name="cancel" id="cancel">Cancel</button></a>  
                                           </td> -->
                                    </tr>
                                    <?php $o++; } } else { ?>
                                     <tr>
                                    <td colspan="8" align="center">No Records Found</td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="8">&nbsp;</th>
                                    <!--<th align="center"><button type="submit" class="btn btn-danger" name="delete" id="delete" style="width:100%;" value="Delete" onclick="return checkdelete('chk[]');"> DELETE </button></th>-->
                                </tr>
                            </tfoot>
                                    </table>
</form>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        

                    </div> <!-- container-fluid -->

                </div> <!-- content -->

                
        </div>


<!-- Content Wrapper. Contains page content -->

<script type="text/javascript">
    function viewthis(a)
    {
        var did = a;
        window.location.href = '<?php echo $sitename; ?>master/' + a + '/viewuser.htm';
    }     
</script>
 <!-- Select2 -->
 <link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet">
<script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>

    <!-- Select2 -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
         
<script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
</script>
<?php
include ('../../require/footer.php');
?>