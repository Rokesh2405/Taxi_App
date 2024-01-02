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
 global $db;
if(isset($_REQUEST['submit']))
{
@extract($_REQUEST);
global $db;
$uquery = "UPDATE `booking` SET `start_km`='".$start_km."',`end_km`='".$end_km."',`per_Day_target`='".$per_Day_target."',`base_fare`='".$base_fare."',`additional_distance`='".$additional_distance."',`additional_fare`='".$additional_fare."',`distance`='".$distance."',`bataFee`='".$bataFee."',`waiting_charge`='".$waiting_charge."',`perKm`='".$perKm."',`balance_amount`='".$balance_amount."',`total_price`='".$total_price."' WHERE `id`='".$_REQUEST['id']."'  ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();	
	$msg='<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Updated</div>';
    
}


$ord = $db->prepare("SELECT * FROM `booking` WHERE `id`='".$_REQUEST['id']."' ");	
$ord->execute();
$ford = $ord->fetch(PDO::FETCH_ASSOC);

$stmt1121 = $db->prepare("SELECT * FROM `notification` WHERE `booking_id`='".$_REQUEST['id']."' AND `driver_name`!='' ORDER BY `id` DESC");	
$stmt1121->execute();
$rowford = $stmt1121->fetch(PDO::FETCH_ASSOC);

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
                                        <li class="breadcrumb-item"><a href="<?php echo $sitename; ?>master/closedtrips.htm">Booking</a></li>
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
                                    <h4 class="page-title"><a href="<?php echo $sitename; ?>master/closedtrips.htm">Back to Listing</a></h4>
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
                                                    <div class="col-md-12" align="left">
                                                   <h4 class="mt-0 header-title">Customer Details</h4>
														<hr>
                                                    </div> 
													</div>
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
                                                    <div class="col-md-12" align="left">
                                                   <h4 class="mt-0 header-title">Booking Details</h4>
														<hr>
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
                                                  <p><?php if(getplace('place',$ford['pickup_address'])!='')
                                                  {
                                                  	echo getplace('place',$ford['pickup_address']);
                                                  }
                                                  else
                                                  {
                                                  	echo $ford['pickup_address'];
                                                  }
                                                   ?></p>
                                                    </div> 
                                                   </div>
                                                   <div class="row">
                                                    <div class="col-md-3"  align="left">
                                                    <label>Drop Address</label>    
                                                    </div> 
                                                    <div class="col-md-6"  align="left">
                                                   <p><?php if(getplace('place',$ford['drop_address'])!='')
                                                  {
                                                  	echo getplace('place',$ford['drop_address']);
                                                  }
                                                  else
                                                  {
                                                  	echo $ford['drop_address'];
                                                  }
                                                   ?> </p>
                                                    </div> 
                                                   </div>
                                                    <br>
                                                     
                                                   <div class="row">
                                                   <!-- <div class="col-md-3" align="left">
                                                    <label>Quote Amount</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
                                                   Rs. <?php echo $ford['quote_amount']; ?>
                                                    </div> -->
                                                    <div class="col-md-3" align="left">
                                                    <label>Booking Km</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
                                                   <?php echo $ford['booking_km']; ?>
                                                    </div> 
                                                   </div>
                                                 <hr>
													 <div class="row">
                                                    <div class="col-md-12" align="left">
                                                   <h4 class="mt-0 header-title">Driver Details</h4>
														<hr>
                                                    </div> 
													</div>
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
                                                   <br>
                                                  
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
                                                        <input type="hidden" name="notification_id" value="<?php echo $rowford['id']; ?>">
                                                   <?php echo $rowford['cartype']; ?>
                                                    </div> 
                                                   </div>
                                                   <br>
                                                      <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Driver Charge</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
                                                   Rs. <?php echo $ford['bataFee']; ?>
                                                    </div> 
                                                    </div>
                                                   
                                                    <hr>
													 <div class="row">
                                                    <div class="col-md-12" align="left">
                                                   <h4 class="mt-0 header-title">Amount Details</h4>
														<hr>
                                                    </div> 
													</div>
                                                      <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Start Km</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
														<input type="text" name="start_km" class="form-control" value="<?php echo $ford['start_km']; ?>">
                                                 
                                                    </div> 
                                                     <div class="col-md-3" align="left">
                                                    <label>End Km</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
														<input type="text" name="end_km" class="form-control" value="<?php echo $ford['end_km']; ?>">
                                                  </div> 
                                                   </div>
                                                   <br>
                                                 <div class="row">
													   <div class="col-md-3" align="left">
                                                    <label>Minimum km</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
														<input type="text" name="per_Day_target" class="form-control" value="<?php echo $ford['per_Day_target']; ?>">
                                                    </div>
                                                    <div class="col-md-3" align="left">
                                                    <label>Base Fare</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
												<input type="text" name="base_fare" class="form-control" value="<?php echo $ford['base_fare']; ?>">
                                                    </div> 
                                                    
                                                   </div>
                                                   <br>
                                                  <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Additional km</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
														<input type="text" name="additional_distance" class="form-control" value="<?php echo $ford['additional_distance']; ?>">
                                                 
                                                    </div> 
 <div class="col-md-3" align="left">
                                                    <label>Additional Fare</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
													 <input type="text" name="additional_fare" class="form-control" value="<?php echo $ford['additional_fare']; ?>">
                                                    </div> 
                                                   </div>
                                                   <br>
                                                   <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Total Distance</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
														 <input type="text" name="distance" class="form-control" value="<?php echo $ford['distance']; ?>">
                                                  
                                                    </div> 
                                                     <div class="col-md-3" align="left">
                                                    <label>Driver Beta</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
														 <input type="text" name="bataFee" class="form-control" value="<?php echo $ford['bataFee']; ?>">
                                                   
                                                    </div> 
                                                   </div>
                                                   <br>
                                                   <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Waiting Charge</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
														 <input type="text" name="waiting_charge" class="form-control" value="<?php echo $ford['waiting_charge']; ?>">
                                                    </div> 
                                                     <div class="col-md-3" align="left">
                                                    <label>Price Per Kilometer</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
														<input type="text" name="perKm" class="form-control" value="<?php echo $ford['perKm']; ?>">
                                                  
                                                    </div> 
                                                   </div>
                                                   <br>
                                                   <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Paid &#38; Balance Amount</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
														<input type="text" name="balance_amount" class="form-control" value="<?php echo $ford['balance_amount']; ?>">
                                                   <?php //if($ford['paid_amount']!='') { echo 'Rs. '.$ford['paid_amount']; } 
														 //if($ford['balance_amount']!='') { echo 'Rs. '.$ford['balance_amount']; }
														?>
                                                    </div> 
                                                     <div class="col-md-3" align="left">
                                                    <label>Total Price</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
														<input type="text" name="total_price" class="form-control" value="<?php echo $ford['total_price']; ?>"> </div> 
                                                   </div>
                                                   <br>
                                                   <!-- <div class="row">
                                                    <div class="col-md-3" align="left">
                                                    <label>Balance Amount</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left">
                                                   <?php //if($ford['balance_amount']!='') { echo 'Rs. '.$ford['balance_amount']; } ?>
                                                    </div> 
                                                    
                                                   </div>
                                                   <br>-->
											
											
                                        </div><!-- /.box-body -->

                                      <div class="row">
										  <div class="col-md-12" align="left">
										        <button type="submit" name="submit" id="submit" class="btn btn-primary waves-effect waves-light"><?php
                                if ($_REQUEST['id'] != '') {
                                    echo 'UPDATE';
                                } else {
                                    echo 'SUBMIT';
                                }
                                ?></button>
										  </div>
										</div>
                                        
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