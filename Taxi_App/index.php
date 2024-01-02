<?php
$dynamic = '';
$menu = '1';
$index='1';
include ('require/header.php');
//print_r($_SESSION);
$_SESSION['mobileno']='';
unset($_SESSION['mobileno']);
if($_SESSION['highrisk']!='unshow' && isset($_SESSION['doctorid']))
{
  $_SESSION['highrisk']='show';  
}
?>
        <div class="content-page">
        
            <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Dashboard</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item active">
                                            Welcome to Droptaxi Dashboard
                                        </li>
                                    </ol>
                           </div>
                            </div>
                        </div>
						
                           <div class="row">
                                 <div class="col-xl-4 col-md-4">
                                    <div class="card mini-stat bg-primary">
                                        <div class="card-body mini-stat-img">
                                            
                                            <div class="text-white">
                                                <div class="row">
                                                  
                                            <div class="col-md-6">
                                                <h6 class="text-uppercase mb-3">Add Booking</h6>
												<h4 class="mb-4">&nbsp;</h4>
                                            </div>
                                              <div class="col-md-6"><div class="mini-stat-icon">
                                                <i class="mdi mdi-tag-text-outline float-right"></i>
                                            </div></div>
                                                </div>
                                                
                                                <a href="<?php echo $sitename; ?>master/addbooking.htm" style="color:#fff;"><span class="ml-2">>&nbsp;&nbsp;Click here to View</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                               <div class="col-xl-4 col-md-4">
                                    <div class="card mini-stat bg-primary">
                                        <div class="card-body mini-stat-img">
                                            
                                            <div class="text-white">
                                                <div class="row">
                                                  
                                            <div class="col-md-6">
                                                <h6 class="text-uppercase mb-3">New Enquiry</h6>
                                                <h4 class="mb-4">  <?php
                           $stmt = $db->prepare("SELECT * FROM `booking` WHERE `confirm_status`='0' ORDER BY `trip_date` ASC, `trip_time`");
                           $stmt->execute();
                           echo $sel = $stmt->rowCount();
                                ?> </h4>
                                            </div>
                                              <div class="col-md-6"><div class="mini-stat-icon">
                                                <i class="mdi mdi-tag-text-outline float-right"></i>
                                            </div></div>
                                                </div>
                                                
                                                <a href="<?php echo $sitename; ?>master/booking.htm" style="color:#fff;"><span class="ml-2">>&nbsp;&nbsp;Click here to View</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>    
							   
							   <div class="col-xl-4 col-md-4">
                                    <div class="card mini-stat bg-primary">
                                        <div class="card-body mini-stat-img">
                                            
                                            <div class="text-white">
                                                <div class="row">
                                                  
                                            <div class="col-md-10">
                                                <h6 class="text-uppercase mb-3">Admin Confirmed Booking</h6>
                                                <h4 class="mb-4">  <?php
                             $stmt = $db->prepare("SELECT * FROM `booking` WHERE `confirm_status`='1' AND `completed_status`='0' AND `driver_id` IS NULL ORDER BY `trip_date` ASC, `trip_time` ");
                               
                           $stmt->execute();
                           echo $sel = $stmt->rowCount();
                                ?> </h4>
                                            </div>
                                              <div class="col-md-2"><div class="mini-stat-icon">
                                                <i class="mdi mdi-tag-text-outline float-right"></i>
                                            </div></div>
                                                </div>
                                                
                                                <a href="<?php echo $sitename; ?>master/confirmedtrip.htm" style="color:#fff;"><span class="ml-2">>&nbsp;&nbsp;Click here to View</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>   
							   
							   
                            </div>
						
                            <div class="row">
								    <div class="col-xl-4 col-md-4">
                                    <div class="card mini-stat bg-primary">
                                        <div class="card-body mini-stat-img">
                                            
                                            <div class="text-white">
                                                <div class="row">
                                                  
                                            <div class="col-md-10">
                                                <h6 class="text-uppercase mb-3">Driver Confirmed Booking</h6>
                                                <h4 class="mb-4">  <?php
                             $stmt = $db->prepare("SELECT * FROM `booking` A, `notification` B WHERE A.`id`=B.`booking_id` AND B.`driver_name`!='' AND A.`request_status`='1' AND A.`completed_status`='0' ");
                               
                           $stmt->execute();
                           echo $sel = $stmt->rowCount();
                                ?> </h4>
                                            </div>
                                              <div class="col-md-2"><div class="mini-stat-icon">
                                                <i class="mdi mdi-tag-text-outline float-right"></i>
                                            </div></div>
                                                </div>
                                                
                                                <a href="<?php echo $sitename; ?>master/confirmedtrip.htm" style="color:#fff;"><span class="ml-2">>&nbsp;&nbsp;Click here to View</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
								
                                 <div class="col-xl-4 col-md-4">
                                    <div class="card mini-stat bg-primary">
                                        <div class="card-body mini-stat-img">
                                            
                                            <div class="text-white">
                                                <div class="row">
                                                  
                                            <div class="col-md-11">
                                                <h6 class="text-uppercase mb-3">Completed Booking</h6>
                                                <h4 class="mb-4">  <?php
                           $ord = $db->prepare("SELECT * FROM `booking` WHERE `completed_status`='1' AND `total_amount_to_pay`!='' ORDER BY `id` DESC ");	
$ord->execute();
echo $ordnum = $ord->rowCount(); 
                                ?> </h4>
                                            </div>
                                              <div class="col-md-1"><div class="mini-stat-icon">
                                                <i class="mdi mdi-tag-text-outline float-right"></i>
                                            </div></div>
                                                </div>
                                                
                                                <a href="<?php echo $sitename; ?>master/closedtrips.htm" style="color:#fff;"><span class="ml-2">>&nbsp;&nbsp;Click here to View</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                         
                                <div class="col-xl-4 col-md-4">
                                    <div class="card mini-stat bg-primary">
                                        <div class="card-body mini-stat-img">
                                            <div class="mini-stat-icon">
                                                <i class="mdi mdi-buffer float-right"></i>
                                            </div>
                                            <div class="text-white">
                                                <h6 class="text-uppercase mb-3">Users</h6>
                                                <?php
                             $stmt = $db->prepare("SELECT * FROM `register` ");
                               
                           $stmt->execute();
                           
                                ?>   <h4 class="mb-4"><?php echo $sel = $stmt->rowCount(); ?></h4>
                                               <a href="<?php echo $sitename; ?>master/registeruser.htm" style="color:#fff;"><span class="ml-2">>&nbsp;&nbsp;Click here to View</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						
						
						 <div class="row">
                               
							   
                               <div class="col-xl-4 col-md-4">
                                    <div class="card mini-stat bg-primary">
                                        <div class="card-body mini-stat-img">
                                            <div class="mini-stat-icon">
                                                <i class="mdi mdi-tag-text-outline float-right"></i>
                                            </div>
                                            <div class="text-white">
                                                <h6 class="text-uppercase mb-3">Driver</h6>
                                                <h4 class="mb-4">  <?php
                             $stmt = $db->prepare("SELECT * FROM `driver` ");
                               
                           $stmt->execute();
                           echo $sel = $stmt->rowCount();
                                ?> </h4>
                                                <a href="<?php echo $sitename; ?>master/driver.htm" style="color:#fff;"><span class="ml-2">>&nbsp;&nbsp;Click here to View</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              <div class="col-xl-4 col-md-4">
                                    <div class="card mini-stat bg-primary">
                                        <div class="card-body mini-stat-img">
                                            
                                            <div class="text-white">
                                                <div class="row">
                                                  
                                            <div class="col-md-6">
                                                <h6 class="text-uppercase mb-3">Cars</h6>
                                                <h4 class="mb-4">  <?php
                             $stmt = $db->prepare("SELECT * FROM `cars` ");
                               
                           $stmt->execute();
                           echo $sel = $stmt->rowCount();
                                ?> </h4>
                                            </div>
                                              <div class="col-md-6"><div class="mini-stat-icon">
                                              <i class="mdi mdi-tag-text-outline float-right"></i>
                                            </div></div>
                                                </div>
                                                
                                                <a href="<?php echo $sitename; ?>master/cars.htm" style="color:#fff;"><span class="ml-2">>&nbsp;&nbsp;Click here to View</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               <div class="col-xl-4 col-md-4">
                                    <div class="card mini-stat bg-primary">
                                        <div class="card-body mini-stat-img">
                                            
                                            <div class="text-white">
                                                <div class="row">
                                                  
                                            <div class="col-md-6">
                                                <h6 class="text-uppercase mb-3">Package</h6>
                                                <h4 class="mb-4">  <?php
                             $stmt = $db->prepare("SELECT * FROM `packages` ");
                               
                           $stmt->execute();
                           echo $sel = $stmt->rowCount();
                                ?> </h4>
                                            </div>
                                              <div class="col-md-6"><div class="mini-stat-icon">
                                               <i class="mdi mdi-tag-text-outline float-right"></i>
                                            </div></div>
                                                </div>
                                                
                                                <a href="<?php echo $sitename; ?>master/packages.htm" style="color:#fff;"><span class="ml-2">>&nbsp;&nbsp;Click here to View</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                                </div>
                            </div>
                            <!-- end row -->
            
                          
						  </div> <!-- container-fluid -->
                </div> <!-- content -->

                
        </div>
       <?php include 'require/footer.php'; ?>    