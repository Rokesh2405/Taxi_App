<?php
$menu = "4";
include ('../../config/config.inc.php');
$dynamic = '1';
//$datepicker = '1';
$datatable = '1';

include ('../../require/header.php');
include_once 'notification.php';

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

$msg = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Notification Send to All Drivers</div>';
  
}

if(isset($_REQUEST['send']))
{
 @extract($_REQUEST);
 global $db;

$title="Booking Acceptance Request from Admin";
$message="Checked and placed your quotation amount";

$driver = pFETCH("SELECT * FROM `driver` WHERE `status`=? ", 1);
while ($driverfetch = $driver->fetch(PDO::FETCH_ASSOC)) {
    $notification = new Notification();
                         $title1="DROPTAXI";
                        $message="Booking Acceptance Request from Admin.Checked and placed your quotation amount";
					    $messagenoti="Booking Acceptance Request from Admin.Checked and placed your quotation amount";
					
			// Add Notification		
$query = "INSERT INTO `notification` SET
`booking_id`='".$_REQUEST['booking_id']."',`from`='admin',`to`='".$driverfetch['id']."',`title`='".$title1."',`message`='".$message."',`type`='Admin-Driver' ";
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
                    request_status='1', `quote_amount`='".$_REQUEST['quote_amount']."' WHERE `id`='".$_REQUEST['booking_id']."' ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();

$msg = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Notification Send to All Drivers</div>';

}
?>
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
                                    <h4 class="page-title">Booking List</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Master </a></li>
                                        <li class="breadcrumb-item active">Booking List</li>
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
                                    <th style="width:15%;">Date</th>
                                    <th style="width:20%;">Username</th>
                                    <th style="width:20%;">Contact No</th>
                                    <th data-sortable="false" align="center" style="text-align: center; padding-right:0; padding-left: 0; width: 10%;">View & Quote Amount</th>
                                 <th data-sortable="false" align="center" style="text-align: center; padding-right:0; padding-left: 0; width: 10%;">Action</th>
                                </tr>
                            </thead> 
                            <tbody>
                             <?php
                                $o = '1';
$ord = $db->prepare("SELECT * FROM `booking` WHERE `request_status`='0' ORDER BY `id` DESC ");	
$ord->execute();
$ordnum = $ord->rowCount(); 
if($ordnum>0) { 
                                while ($ford = $ord->fetch(PDO::FETCH_ASSOC)) {
                                   
                                    ?>
                                    <tr>
                                    <td><?php echo $o; ?></td> 
                                    <td><?php echo date('d-M-Y',strtotime($ford['date'])); ?></td>   
                                       <td><?php echo getregisterform('name',$ford['register_id']); ?></td> 
                                          <td><?php echo getregisterform('mobileno',$ford['register_id']); ?></td> 

                                           <td><a data-toggle="modal" data-target="#book<?php echo $ford['id']; ?>" style="color:#62A3FF;cursor:pointer;">Booking Details</a>
                                            <div id="book<?php echo $ford['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <form name="modalform" id="modalform" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Booking Details</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                </div>
                                                <div class="modal-body">
                                                   <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Username</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left">
                                                        <input type="hidden" name="booking_id" class="form-control" value="<?php echo $ford['id']; ?>">
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
                                                    <div class="col-md-6"  align="left">
                                                  <p><?php echo $ford['pickup_address']; ?>   </p>
                                                    </div> 
                                                   </div>
                                                    <br>
                                                   <div class="row">
                                                    <div class="col-md-3"  align="left">
                                                    <label>Drop Address</label>    
                                                    </div> 
                                                    <div class="col-md-6"  align="left">
                                                   <p><?php echo $ford['drop_address']; ?>  </p>
                                                    </div> 
                                                   </div>
                                                    <br>
                                                     
                                                   <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Quote Amount</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
                                                   <input type="text" name="quote_amount" id="quote_amount" required="required" class="form_control" value="<?php echo $ford['quote_amount']; ?>">
                                                    </div>
													   <div class="col-md-3" align="left">
                                                    <label>Assign Driver</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
														<select name="driver" class="form-control">
															<option value="">Select</option>
															<?php
									$driver = pFETCH("SELECT * FROM `driver` WHERE `status`=? ", 1);
                                    while ($driverfetch = $driver->fetch(PDO::FETCH_ASSOC)) {
									?>
															<option value="<?php echo $driverfetch['id']; ?>" <?php if($ford['driver_id']==$driverfetch['id']) { ?> selected="selected" <?php } ?>><?php echo $driverfetch['driver_name']; ?></option>
															<?php } ?>
														</select>
                                                  
                                                    </div> 
                                                   </div>
                                                   <br>
                                                  
                                                </div>
                                                 <?php if($ford['request_status']=='0') { ?>
                                                <div class="modal-footer">
                                                  
                                                    <button type="submit" class="btn btn-info waves-effect waves-light" name="send" id="send">Send to Driver</button>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </form>
                                        </div>
                                    </div><!-- /.modal -->
                                           </td>
                                           <td>
                                        
                                              <a href="<?php echo $sitename.'master/'.$ford['id'].'/booking.htm'; ?>"><button type="button" class="btn btn-info waves-effect waves-light" name="cancel" id="cancel">Cancel</button></a>  
                                           </td>
                                    </tr>
                                    <?php $o++; } } else { ?>
                                     <tr>
                                    <td colspan="6" align="center">No Records Found</td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6">&nbsp;</th>
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
<?php
include ('../../require/footer.php');
?>