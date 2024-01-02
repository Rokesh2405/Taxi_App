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

include ('../../require/header.php');
include_once 'notification.php';
if(isset($_REQUEST['send']))
{
 @extract($_REQUEST);
 global $db;

$uquery = "UPDATE `booking` SET
                    view_status='1' WHERE `id`='".$_REQUEST['booking_id']."' ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();
	
	
if($driver!='All'){
	$notification = new Notification();
$title1="DROPTAXI";
//$message="Booking Acceptance Request from Admin.Checked and Accept the Trip";
//$messagenoti="Booking Acceptance Request from Admin.Checked and Accept the Trip";
$message="New booking received";					
$messagenoti="New booking received";					
	
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
//$message="Booking Acceptance Request from Admin.Checked and Accept the Trip";
//$messagenoti="Booking Acceptance Request from Admin.Checked and Accept the Trip";
$message="New booking received";					
$messagenoti="New booking received";	
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


$msg = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Notification Send to Drivers</div>';
}

if (isset($_REQUEST['delete']) || isset($_REQUEST['delete_x'])) {
    $chk = $_REQUEST['id'];
   
    $msg = delregisterform($chk);
     $_SESSION['msg']= $msg;
        header('Location:../regsiteruser.htm');
}
if (isset($_REQUEST['statusupdate'])) {
    @extract($_REQUEST);
    global $db;
    $ip = $_SERVER['REMOTE_ADDR'];
    $resa = $db->prepare("UPDATE `register` SET `verify_status`=?,`status`=? WHERE `id`=?");
    $resa->execute(array($_REQUEST['verify_status'],$_REQUEST['status'],$_REQUEST['id']));
    $msg = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h5><i class="icon fa fa-exclamation-tick"></i>Successfully Updated</h5></div>';
    // header("location:https://www.jiovio.com/allokitadmin/master/1/settings.htm");   
}
$ord = $db->prepare("SELECT * FROM `booking` WHERE `id`='".$_REQUEST['id']."' ORDER BY `id` DESC ");	
$ord->execute();
$ford = $ord->fetch(PDO::FETCH_ASSOC);
?>
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

  <div class="content-page">
        
<div class="content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <h4 class="page-title"><?php
                            if (isset($_REQUEST['id'])) {
                                echo "View";
                            } else {
                                echo "Add";
                            }
                            ?> Confirm Trip </h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Confirm Trip</a></li>
                                        <li class="breadcrumb-item active"><?php
                            if (isset($_REQUEST['id'])) {
                                echo "View";
                            } else {
                                echo "Add";
                            }
                            ?> User </li>
                                    </ol>
            
                                <div class="state-information d-none d-sm-block">
                                    <h4 class="page-title"><a href="<?php echo $sitename; ?>master/confirmedtrip.htm">Back to Listing</a></h4>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12"><?php echo $msg; ?>
                                <div class="card m-b-20">
                                    <div class="card-body">
      
                                        <h4 class="mt-0 header-title">Customer Details</h4>
                                       
                                        
                                            <form name="department" id="department" action="#" method="post" enctype="multipart/form-data" autocomplete="off" >
                                    <div class="box box-info">
                                        <div class="box-body">
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <div class="panel-title">&nbsp;</div>
                                                </div>
                                                <div class="panel-body">
                                                 
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
                                                   <br>
													 <h4 class="mt-0 header-title">Booking Details</h4>
													<br>
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
                                                <br>
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
                                               <br>
                                                <div class="row">
                                                       <div class="col-md-3" align="left">
                                                    <label>Rental Amount</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left">
                                                  <?php echo $ford['customer_booking_amount']; ?>   
                                                    </div> 
                                                   </div>
                                                  <br>
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
													
													<br>
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
                                                 
                                                 
<br>
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
															<option value="<?php echo $driverfetch['id']; ?>" <?php if($ford['driver_id']==$driverfetch['id']) { ?> selected="selected" <?php } ?>><?php echo $driverfetch['driver_name']; ?>-<?php echo $driverfetch['driver_mobileno']; ?></option>
															<?php } ?>
														</select>
                                                 
														
                                                    </div> 
                                                   </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">&nbsp;</div>
                            <div class="col-md-6">
								<input type="hidden" name="booking_id" class="form-control" value="<?php echo $ford['id']; ?>">
                                 <button type="submit" name="send" id="send" class="btn btn-success" style="float:right;">Send to Driver Status</button>
                            </div>    
                            </div>
                                                     
                                                </div>
                                            </div>
                                        </div><!-- /.box-body -->

                                      
                                        
                                    </div>
                                </form>     
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
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
</script>
<script>
    function click1()
    {

        $('#demo').css("display", "block");

    }
</script>