<?php

 $loginaccess2 = $db->prepare("SELECT * FROM `users` WHERE `orgpassword` = ? AND `id`=? ");
$loginaccess2->execute(array($_SESSION['Gpassword'],$_SESSION['ALOID']));
 $loginaccess2 = $loginaccess2->fetch();
 if($loginaccess2['id']=='')
 {
  logout();
session_destroy();
session_unset();
header("location:https://ttbilling.in/taxi_app/pages/");   
 }



?>
 <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="slimscroll-menu" id="remove-scroll">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu" id="side-menu">
                            <li class="menu-title">Main</li>
                            <li>
                                <a href="<?php echo $sitename; ?>" class="waves-effect">
                                    <i class="mdi mdi-view-dashboard"></i>
                                    
<!--                                    <span class="badge badge-primary badge-pill float-right">2</span> -->
                                    
                                    <span> Dashboard </span>
                                </a>
                            </li>
                           
                           <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-clipboard-outline"></i><span> Master <span class="badge badge-pill badge-success float-right">3</span> </span></a>
                                <ul class="submenu">
                                    <!--<li><a href="<?php echo $sitename; ?>master/types.htm">Car Models</a></li>-->
                                    <li><a href="<?php echo $sitename; ?>master/packages.htm">Packages </a></li>
                                     <li><a href="<?php echo $sitename; ?>master/cars.htm">Cars List</a></li>
                                      <li><a href="<?php echo $sitename; ?>master/places.htm">Places List</a></li>
                                     
                                     <!--<li><a href="<?php echo $sitename; ?>master/trip_types.htm">Trip Types</a></li>-->
                                     <!-- <li><a href="<?php echo $sitename; ?>master/price_list.htm">Price List </a></li>-->
                                </ul>
                            </li>
                        
                          <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-clipboard-outline"></i><span> Booking <span class="badge badge-pill badge-success float-right">6</span> </span></a>
                                <ul class="submenu">
									 <li><a href="<?php echo $sitename; ?>master/addbooking.htm">Add Booking</a></li>
                                      <li><a href="<?php echo $sitename; ?>master/booking.htm">New Enquiry</a></li>
                                       <li><a href="<?php echo $sitename; ?>master/confirmedtrip.htm">Admin Confimed Trip</a></li>
                                     <!--  <li><a href="<?php echo $sitename; ?>master/driver_bittings.htm">Driver Bittings</a></li> -->
                                      <li><a href="<?php echo $sitename; ?>master/driver_accepeted.htm">Driver Confirmed Trip</a></li>
									 <li><a href="<?php echo $sitename; ?>master/onprocesstrip.htm">Onprocess Trips</a></li>
                                    <li><a href="<?php echo $sitename; ?>master/closedtrips.htm">Completed Trips</a></li>
                                     <li><a href="<?php echo $sitename; ?>master/cancelledtrips.htm">Cancelled Trips</a></li>
                                </ul>
                            </li>
                         
                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-clipboard-outline"></i><span> Users <span class="badge badge-pill badge-success float-right">2</span> </span></a>
                                <ul class="submenu">
                                    <li><a href="<?php echo $sitename; ?>master/driver.htm">Drivers List </a></li>
                                    <li><a href="<?php echo $sitename; ?>master/registeruser.htm">Registered Users</a></li>
                                  
                                </ul>
                            </li>

                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-clipboard-outline"></i><span> Notification <span class="badge badge-pill badge-success float-right">3</span> </span></a>
                                <ul class="submenu">
                                     <li><a href="<?php echo $sitename; ?>master/notificationlist.htm">Notification List</a></li>
                                    <li><a href="<?php echo $sitename; ?>master/customer_notification.htm">Send to Customer</a></li>
                                  <li><a href="<?php echo $sitename; ?>master/driver_notification.htm">Send to Driver</a></li>
                                </ul>
                            </li>

<li>
                                <a href="<?php echo $sitename; ?>master/settings.htm" class="waves-effect"><i class="mdi mdi-clipboard-outline"></i><span> Settings </span></a>
                               
                            </li>
							<li>
                                <a href="<?php echo $sitename; ?>master/change_password.htm" class="waves-effect"><i class="mdi mdi-clipboard-outline"></i><span> Change Password </span></a>
                               
                            </li>
<li>
                                <a href="<?php echo $sitename; ?>logout.htm" class="waves-effect"><i class="mdi mdi-clipboard-outline"></i><span> Logout </span></a>
                               
                            </li>
                        </ul>

                    </div>
                    <!-- Sidebar -->
                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->
