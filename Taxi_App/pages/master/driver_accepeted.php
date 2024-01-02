<?php
$menu = "4";
include ('../../config/config.inc.php');
$dynamic = '1';
//$datepicker = '1';
$datatable = '1';

include ('../../require/header.php');
include_once 'notification.php';

// error_reporting(1);
// ini_set('display_errors','1');
// error_reporting(E_ALL);

if(isset($_REQUEST['send'])){
    global $db;
@extract($_REQUEST);
	
	//set otp
$otp = generateRandomString3();
	$query = "UPDATE `booking` SET
                    otp='".$otp."' WHERE id='".getnotification('booking_id',$notification_id)."'";
$stmt = $db->prepare($query);
$stmt->execute();
		//set otp
	
//SEND SMS
	
	$bkid=getnotification('booking_id',$notification_id);
	$finduser=getregisterform('device_key',getbooking('register_id',$bkid));
$mobileno=	getregisterform('mobileno',getbooking('register_id',$bkid));
$otp=getbooking('otp',$bkid);
	//if($finduser=='') {
	// SEND SMS
$postRequest = array();

$stmt1121 = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$bkid."' AND `driver_name`!='' ORDER BY `id` DESC");	
$stmt1121->execute();
$rowford = $stmt1121->fetch(PDO::FETCH_ASSOC);
		
$url_name='http://api.onhandsms.com/api/v2/sendsms?username=8667459121&password=8667459121&senderid=DPTXMD&number='.$mobileno.'&istamil=0&dlttemplateid=1707168689374371052&message=Driver and vehicle Details Confirmed ! Driver Name: '.$rowford['driver_name'].' Contact Number: '.$rowford['driver_mobileno'].' Vehicle Type: '.$rowford['cartype'].' Vehicle Number: '.$rowford['driver_carno'].' OTP: '.$otp.' DROPTAXIMADURAI';
	
		$resp=file_get_contents($url_name);
		$jsonres=json_decode($resp);
		//print_r($resp);
		//	echo $jsonres->status;
		//exit;
  
	//}


//SEND SMS
	
// Notification to User
$notification = new Notification();
$title1="DROPTAXI - Your Booking is Confirmed";
//$message="Driver ".getdriver('driver_name',getnotification('to',$notification_id))." will reach you soon.";
$message="Driver and vehicle details confirmed. Driver name : ".$rowford['driver_name']." ,Contact number : ".$rowford['driver_mobileno']." ,Vehicle type : ".$rowford['cartype']." ,Vehicle number : ".$rowford['driver_carno']." ,OTP : ".$otp;
//$messagenoti="Driver ".getdriver('driver_name',getnotification('to',$notification_id))." will reach you soon.";
$messagenoti="Driver and vehicle details confirmed. Driver name : ".$rowford['driver_name']." ,Contact number : ".$rowford['driver_mobileno']." ,Vehicle type : ".$rowford['cartype']." ,Vehicle number : ".$rowford['driver_carno']." ,OTP : ".$otp;				
$query = "INSERT INTO `notification` SET
`booking_id`='".getnotification('booking_id',$notification_id)."',`from`='admin',`to`='".getbooking('register_id',getnotification('booking_id',$notification_id))."',`title`='".$title1."',`message`='".$message."',`type`='Admin-User' ";
$stmt = $db->prepare($query);
$stmt->execute();
$lasid=$db->lastInsertId();
 $title="DROPTAXI - Booking No :".$notification_id;

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
						
					$firebase_token =getusers('device_key',getbooking('register_id',getnotification('booking_id',$notification_id)));
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

     // Notification to User
     
     // Notification to Driver
$notification = new Notification();
                      $title1="DROPTAXI - Your Request is Confirmed";
                        $message="Get Start to Ride";
					   
					$messagenoti="Get Start to Ride";
$query = "INSERT INTO `notification` SET
`booking_id`='".getnotification('booking_id',$notification_id)."',`from`='admin',`to`='".getnotification('to',$notification_id)."',`title`='".$title1."',`message`='".$message."',`type`='Admin-Driver' ";
$stmt = $db->prepare($query); 
$stmt->execute();
$lasid=$db->lastInsertId();
 $title="DROPTAXI - Booking No :".$notification_id;

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
// $mcheckvaliduser = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$data->notification_id."' AND `confirm_status`='1' ORDER BY `id` ASC");
// $mcheckvaliduser->execute();
// $msrow1121 = $mcheckvaliduser->fetch(PDO::FETCH_ASSOC);

					$firebase_token =getdriver('device_key',getnotification('to',$notification_id));
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
			   
				}

     // Notification to User
      $uquery = "UPDATE `booking` SET
                    completed_status='1' WHERE `id`='".getnotification('booking_id',$notification_id)."' ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();


$msg = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Confirmed Successfully</div>';
  
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
                                    <h4 class="page-title">Driver Confirmed Trips List</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Master </a></li>
                                        <li class="breadcrumb-item active">Driver Confirmed Trips List</li>
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
									<th style="width:10%;">Driver Name</th>
									<th style="width:10%;">Driver Mobileno</th>
                                    <th style="width:15%;">Trip Date</th>
                                    <th style="width:10%;">Trip Time</th>
                                    <th style="width:10%;">Pickup</th>
                                      <th style="width:10%;">Drop</th>
                                       <th style="width:10%;">Cab Name</th>
                                    <th data-sortable="false" align="center" style="text-align: center; padding-right:0; padding-left: 0; width: 10%;">Action</th>
                                 
                                </tr>
                            </thead> 
                            <tbody>
                             <?php
                                $o = '1';
                                 $ord = $db->prepare("SELECT * FROM `booking` WHERE `request_status`='1' AND `completed_status`='0' ORDER BY `trip_date` ASC, `trip_time` ");	
$ord->execute();
 $ordnum = $ord->rowCount(); 
 if($ordnum>0) { 
                                while ($ford = $ord->fetch(PDO::FETCH_ASSOC)) {
                                  $stmt1121 = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$ford['id']."' AND `driver_name`!='' ORDER BY `id` DESC");	
$stmt1121->execute();
 $fnchecknum = $stmt1121->rowCount(); 
 if($fnchecknum>0) {
     $rowford = $stmt1121->fetch(PDO::FETCH_ASSOC);
     ?>
                                    <tr>
                                    <td><?php echo $o; ?></td> 
										<td><?php echo getdriver('driver_name',$ford['driver_id']); ?></td>   
										<td><?php echo getdriver('driver_mobileno',$ford['driver_id']); ?></td> 
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

                                           <td><a data-toggle="modal" data-target="#book<?php echo $ford['id']; ?>" style="color:#62A3FF;cursor:pointer;">Final Confirmation</a>
                                            <div id="book<?php echo $ford['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <form name="modalform" id="modalform" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">View Details</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                </div>
                                                <div class="modal-body" style="line-height:8px;">
													
													 <div class="row">
                                                    <div class="col-md-3" align="left">
                                                  <h4 class="mt-0 header-title">Customer Details</h4> 
														<hr>
                                                    </div>  </div> 
                                                   <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Customer Name</label>    
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
													<hr>
                                                  <div class="row">
                                                    <div class="col-md-3" align="left">
                                                  <h4 class="mt-0 header-title">Booking Details</h4> 
														<hr>
                                                    </div>  </div> 
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
                                                     
                                                 <!--   <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Quote Amount</label>    
                                                    </div> 
                                                    <div class="col-md-6"  align="left">
                                                   <?php echo $ford['quote_amount']; ?>
                                                    </div> 
                                                   </div>
                                                   <br> -->
                                                   <hr>
													<div class="row">
                                                    <div class="col-md-3" align="left">
                                                  <h4 class="mt-0 header-title">Driver Details</h4> 
														<hr>
                                                    </div>  </div> 
                                                 <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Driver Name</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
                                                   <?php echo $rowford['driver_name']; ?>
                                                    </div> 
                                                     <div class="col-md-3" align="left">
                                                    <label>Driver Mobileno</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
                                                   <?php echo $rowford['driver_mobileno']; ?>
                                                    </div> 
                                                   </div>
                                                
                                                     <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Driver Carno</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
                                                   <?php echo $rowford['driver_carno']; ?>
                                                    </div> 
                                                    <div class="col-md-3" align="left">
                                                    <label>Car Type</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
                                                       
                                                   <?php echo $rowford['cartype']; ?>
                                                    </div> 
                                                   </div>
                                                  
                                                </div>
                                               <div class="modal-footer">
                                                   <input type="hidden" name="notification_id" value="<?php echo $rowford['id']; ?>">
                                                    <button type="submit" class="btn btn-info waves-effect waves-light" name="send" id="send">Final Confirmation</button>
                                                </div>
                                              
                                            </div>
                                        </form>
                                        </div>
                                    </div><!-- /.modal -->
                                           </td>
                                    </tr>
                                    <?php $o++; } } }  else { ?>
                                     <tr>
                                    <td colspan="9" align="center">No Records Found</td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="9">&nbsp;</th>
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