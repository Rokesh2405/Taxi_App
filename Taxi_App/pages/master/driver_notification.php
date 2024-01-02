<?php
$menu = "4";
include ('../../config/config.inc.php');
$dynamic = '1';
//$datepicker = '1';
$datatable = '1';

include ('../../require/header.php');

if (isset($_REQUEST['delete']) || isset($_REQUEST['delete_x'])) {
    $chk = $_REQUEST['chk'];
    $chk = implode('.', $chk);
   
    $msg = deldriver($chk);
}

include_once 'notification.php';
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
if (isset($_REQUEST['send'])) {
@extract($_REQUEST);

$users = pFETCH("SELECT * FROM `driver` WHERE `device_key`!=? ", '');
while ($usersfetch = $users->fetch(PDO::FETCH_ASSOC)) {
// Notification 
$notification = new Notification();
$title1="Hi Driver";
               $messagenoti=$title1.':'.$message;
					
						//$imageUrl = isset($_POST['image_url'])?$_POST['image_url']:'';
						$imageUrl = '';
						//$action = isset($_POST['action'])?$_POST['action']:'';
						$action ='';
						//$actionDestination = isset($_POST['action_destination'])?$_POST['action_destination']:'';
	                    $actionDestination='';
						if($actionDestination ==''){
							$action = '';
						}
						$notification->setTitle($title1);
						$notification->setMessage($messagenoti);
						$notification->setImage($imageUrl);
						$notification->setAction($action);
						$notification->setActionDestination($actionDestination);
						
				// 		echo $usersfetch['device_key'];
				// 		echo "<br><br>";
				// 		echo getusers('firebase_api_key','1');
					$firebase_token =$usersfetch['device_key'];
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

      // Notification
}
$query = "INSERT INTO `admin_notification` SET
                    `type`='driver',`title`='".$title."',`message`='".$message."' ";
                 
                 
$stmt = $db->prepare($query);
$stmt->execute();
 $msg = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Inserted</div>';
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
            if (confirm("Please confirm you want to Delete this Driver(s)"))
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
</style>
       
        <div class="content-page">
        
  
  <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">
<div class="row">
    
      <div class="col-sm-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Driver Notification List</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Master </a></li>
                                        <li class="breadcrumb-item active">Driver Notification List</li>
                                    </ol>
            
                                  
                                     <div class="state-information d-none d-sm-block">
                                        <div class="state-graph">
                                           
                                           <!--<a href="<?php echo $sitename; ?>master/adddriver.htm"><button class="btn btn-success waves-effect waves-light" type="submit">Add New</button></a>-->
                                        </div>
                                       
                                       
                                    </div>
                                </div>
                            </div>
                            
                           
                           
                        </div>
                        
                        
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card m-b-20">
                                    <div class="card-body">
<!--
<!--                                        <h4 class="mt-0 header-title">Default Datatable</h4>-->
<!--                                        <p class="text-muted m-b-30">DataTables has most features enabled by-->
<!--                                            default, so all you need to do to use it with your own tables is to call-->
<!--                                            the construction function: <code>$().DataTable();</code>.-->
<!--                                        </p>-->
<?php echo $msg; ?>
<form name="listform" id="listform" method="post">
    <div class="row">
    <div class="col-md-12"><h5>Send Notification to Drivers</h5></div>    
    </div>
   <hr>
    <div class="row">
    <div class="col-md-3"><label>Title</label></div>   
    <div class="col-md-9"><input type="text" name="title" class="form-control"></div>
    </div>
    <br>
    <div class="row">
    <div class="col-md-3"><label>Message</label></div>   
    <div class="col-md-9">
    <textarea name="message" class="form-control"></textarea>
    </div>
    </div>
    <br>
    <div class="row">
    <div class="col-md-3"> <button type="send" name="send" id="submit" class="btn btn-primary waves-effect waves-light">SEND</button></div>   
   
    </div>
    <hr>
<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                      
                                            <thead>
                                <tr align="center">
                                    <th style="width:5%;">S.id</th>
                                   
                                   <th>Date</th>
                                   <th>Title</th>
                                    <th>Message</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $o = '1';
                                  $drivcharge = DB("SELECT * FROM `admin_notification` WHERE `type`='driver' ORDER BY `id` DESC ");
            $dcount = mysqli_num_rows($drivcharge);
            if ($dcount != '0') {      
                                  while ($ford = mysqli_fetch_array($drivcharge)) { 
                              
                                    ?>
                                    <tr>
                                    <td><?php echo $o; ?></td> 
                                    <td><?php echo date('d-m-Y',strtotime($ford['date'])); ?></td>
                                     <td><?php echo $ford['title']; ?></td>
                                     
                                     <td><?php echo $ford['message']; ?></td>
                                    </tr>
                                    <?php $o++; } } else { ?>
                                    <td colspan="5" align="center">No Records Found</td>
                                    <?php } ?>
                                    </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5">&nbsp;</th>
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


<?php
include ('../../require/footer.php');
?>
