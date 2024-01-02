<?php
//session_start();
  if ($dynamic == '') {
    include ('config/config.inc.php');
}
if ($_SESSION['ALOID'] == '') {
    header("Location:" . $sitename . "pages/");
}
$actual_link = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">
         <head>
              <meta charset="utf-8" />
              <meta http-equiv="X-UA-Compatible" content="IE=edge">
              <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
              <title>Droptaxi - Admin</title>
              <meta content="Admin Dashboard" name="description"/>
              <meta content="webtoall" name="author" />
              <link rel="stylesheet" href="<?php echo $sitename; ?>public/plugins/morris/morris.css">
              <link href="<?php echo $sitename; ?>public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
              <link href="<?php echo $sitename; ?>public/assets/css/metismenu.min.css" rel="stylesheet" type="text/css">
              <link href="<?php echo $sitename; ?>public/assets/css/icons.css" rel="stylesheet" type="text/css">
              <link href="<?php echo $sitename; ?>public/assets/css/style.css" rel="stylesheet" type="text/css">
            <!-- DataTables -->
      <link href="<?php echo $sitename; ?>public/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
      <link href="<?php echo $sitename; ?>public/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
      <!-- Responsive datatable examples -->
      <link href="<?php echo $sitename; ?>public/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
      <!--icons-->
      <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
      <style>
  @font-face {
  font-family: 'Lato';
  font-style: normal;
  font-weight: 700;
  src: url(https://fonts.gstatic.com/s/lato/v17/S6u9w4BMUTPHh6UVSwiPHA.ttf) format('truetype');
}

table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable td:last-child, table.table-bordered.dataTable td:last-child {
    border-right-width: 0;
    white-space: pre-line;
}

.button-default {
  -webkit-transition: 0.25s ease-out 0.1s color;
  -moz-transition: 0.25s ease-out 0.1s color;
  -o-transition: 0.25s ease-out 0.1s color;
  transition: 0.25s ease-out 0.1s color;
  background: transparent;
  border: none;
  cursor: pointer;
  margin: 0;
  outline: none;
  /*position: relative;*/
  position: absolute;
  top: 20px;
}
.show-notifications {
  position: relative;
}
.show-notifications:hover #icon-bell,
.show-notifications:focus #icon-bell,
.show-notifications.active #icon-bell {
  fill: #34495e;
}
.show-notifications #icon-bell {
  fill: #7f8c8d;
}
.show-notifications .notifications-count {
    
  -moz-border-radius: 50%;
  -webkit-border-radius: 50%;
  border-radius: 50%;
  -moz-background-clip: padding-box;
  -webkit-background-clip: padding-box;
  background-clip: padding-box;
  background: #7a6fbe;
  color: #fefefe;
  font: normal 0.85em 'Lato';
  height: 16px;
  line-height: 1.5em;
  position: absolute;
  right: 2px;
  text-align: center;
  top: -2px;
  width: 16px;
}
.show-notifications.active ~ .notifications {
  opacity: 1;
  top: 60px;
}
.notifications {
    display:none;
  -moz-border-radius: 2px;
  -webkit-border-radius: 2px;
  border-radius: 2px;
  -moz-background-clip: padding-box;
  -webkit-background-clip: padding-box;
  background-clip: padding-box;
  -webkit-transition: 0.25s ease-out 0.1s opacity;
  -moz-transition: 0.25s ease-out 0.1s opacity;
  -o-transition: 0.25s ease-out 0.1s opacity;
  transition: 0.25s ease-out 0.1s opacity;
  background: #ecf0f1;
  border: 1px solid #bdc3c7;
  left: 73%;
  /*opacity: 0;*/
  position: fixed;
  top: 81px;
}
.notifications:after {
  border: 10px solid transparent;
  border-bottom-color: #7a6fbe;
  content: '';
  display: block;
  height: 0;
  left: 260px;
  position: absolute;
  top: -20px;
  width: 0;
}
.fa-arrow-alt-circle-right:before{
    font-size: 19px;
    font-size: 15px;
    position: relative;
    top: -17px;
    left: -4px;
}
.notifications h3,
.notifications .show-all {
  background: #7a6fbe;
  color: #fefefe;
  margin: 0;
  padding: 10px;
  width: 350px;
}
.movien{
    left: -56px;
    top: 17px;
    width: 35px;
}
.notifications h3 {
  cursor: default;
  font-size: 1.05em;
  font-weight: normal;
}
.notifications .show-all {
  display: block;
  text-align: center;
  text-decoration: none;
}
.notifications .show-all:hover,
.notifications .show-all:focus {
  text-decoration: underline;
}
.notifications .notifications-list {
  list-style: none;
  margin: 0;
  overflow-x: hidden;
    overflow-y: scroll;
    height: 350px;
  padding: 0;
}
.notifications .notifications-list .item {
  -webkit-transition: -webkit-transform 0.25s ease-out 0.1s;
  -moz-transition: -moz-transform 0.25s ease-out 0.1s;
  -o-transition: -o-transform 0.25s ease-out 0.1s;
  transition: transform 0.25s ease-out 0.1s;
  border-top: 1px solid #bdc3c7;
  color: #7f8c8d;
  cursor: default;
  display: block;
  padding: 10px;
  position: relative;
  white-space: nowrap;
  width: 350px;
}
.notifications .notifications-list .item:before,
.notifications .notifications-list .item .details,
.notifications .notifications-list .item .button-dismiss {
  display: inline-block;
  vertical-align: middle;
}
/*.notifications .notifications-list .item:before {*/
/*  -moz-border-radius: 50%;*/
/*  -webkit-border-radius: 50%;*/
/*  border-radius: 50%;*/
/*  -moz-background-clip: padding-box;*/
/*  -webkit-background-clip: padding-box;*/
/*  background-clip: padding-box;*/
/*  background: #7a6fbe;*/
/*  content: '';*/
/*  height: 8px;*/
/*  width: 8px;*/
/*}*/
.notifications .notifications-list .item .details {
  margin-left: 0px;
  white-space: normal;
  width: 280px;
  text-align: justify;
}
.notifications .notifications-list .item .details .title,
.notifications .notifications-list .item .details .date {
  display: block;
}
.notifications .notifications-list .item .details .date {
  color: #95a5a6;
  font-size: 0.85em;
  margin-top: 3px;
}
.notifications .notifications-list .item .button-dismiss {
color: #f5f5f5;
    font-size: 23px;
   top: -10px;
    right: -2px;
}
.notifications .notifications-list .item .button-dismiss:hover,
.notifications .notifications-list .item .button-dismiss:focus {
  color: #95a5a6;
}
.notifications .notifications-list .item.no-data {
  display: none;
  text-align: center;
}
.notifications .notifications-list .item.no-data:before {
  display: none;
}
.notifications .notifications-list .item.expired {
  /*color: #bdc3c7;*/
      color: #ffffff;
    background: #2b3a4a;
}
/*.notifications .notifications-list .item.expired:before {*/
/*    content: "\f0a9";*/
/*font-family: FontAwesome;*/
/*  background: #bdc3c7;*/
/*}*/
.notifications .notifications-list .item.expired .details .date {
  color: #bdc3c7;
}
.notifications .notifications-list .item.dismissed {
  -webkit-transform: translateX(100%);
  -moz-transform: translateX(100%);
  -ms-transform: translateX(100%);
  -o-transform: translateX(100%);
  transform: translateX(100%);
}
.notifications.empty .notifications-list .no-data {
  display: block;
  padding: 10px;
}
/* variables */
/* mixins */


</style>
 </head>
    <body>
      <div id="wrapper">
          <!-- Top Bar Start -->
            <div class="topbar">
                <!-- LOGO -->
                <div class="topbar-left">
                    <a href="<?php echo $sitename; ?>" class="logo">
                        <span style="color:#fff;">
                            Drop Taxi
                        </span>
                        <i>
                        <img src="<?php echo $fsitename; ?>images/Logo.png" alt="" height="22">
                        </i>
                    </a>
                </div>
                <nav class="navbar-custom">
                    <ul class="navbar-right d-flex list-inline float-right mb-0">
                        
                        <li class="dropdown notification-list">
                            <!-- partial:index.partial.html -->

      <button type="button" class="button-default show-notifications active js-show-notifications">
         <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="32" viewBox="0 0 30 32">
            <defs>
               <g id="icon-bell">
                  <path class="path1" d="M15.143 30.286q0-0.286-0.286-0.286-1.054 0-1.813-0.759t-0.759-1.813q0-0.286-0.286-0.286t-0.286 0.286q0 1.304 0.92 2.223t2.223 0.92q0.286 0 0.286-0.286zM3.268 25.143h23.179q-2.929-3.232-4.402-7.348t-1.473-8.652q0-4.571-5.714-4.571t-5.714 4.571q0 4.536-1.473 8.652t-4.402 7.348zM29.714 25.143q0 0.929-0.679 1.607t-1.607 0.679h-8q0 1.893-1.339 3.232t-3.232 1.339-3.232-1.339-1.339-3.232h-8q-0.929 0-1.607-0.679t-0.679-1.607q3.393-2.875 5.125-7.098t1.732-8.902q0-2.946 1.714-4.679t4.714-2.089q-0.143-0.321-0.143-0.661 0-0.714 0.5-1.214t1.214-0.5 1.214 0.5 0.5 1.214q0 0.339-0.143 0.661 3 0.357 4.714 2.089t1.714 4.679q0 4.679 1.732 8.902t5.125 7.098z"></path>
               </g>
            </defs>
            <g fill="#000000">
               <use xlink:href="#icon-bell" transform="translate(0 0)"></use>
            </g>
         </svg>
         <div class="notifications-count js-count" data-count="<?php echo getnotificationcount(); ?>" id="noti"><?php echo getnotificationcount(); ?></div>
      </button>
<!-- partial -->
                            
                            
                        </li>
                        <li class="dropdown notification-list">
                            <div class="dropdown notification-list nav-pro-img">
                                <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <img src="<?php echo $sitename; ?>images/admin.jpg" alt="user" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                    <!-- item-->
                                    <a class="dropdown-item text-danger" href="<?php echo $sitename; ?>logout.htm"><i class="mdi mdi-power text-danger"></i> Logout</a>
                                </div>                                                                    
                            </div>
                        </li>
                    </ul>
                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left waves-effect">
                                <i class="mdi mdi-menu"></i>
                            </button>
                        </li>
                    </ul>
                </nav>

            </div>
            <!-- Top Bar End -->
			 <?php include 'sidebar.php'; ?> 