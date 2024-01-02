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
                            ?> Booking Details </h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Pending Trips</a></li>
                                        <li class="breadcrumb-item active"><?php
                            if (isset($_REQUEST['id'])) {
                                echo "View";
                            } else {
                                echo "Add";
                            }
                            ?> Booking Details </li>
                                    </ol>
            
                                <div class="state-information d-none d-sm-block">
                                    <h4 class="page-title"><a href="<?php echo $sitename; ?>master/pending_trip.htm">Back to Listing</a></h4>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card m-b-20">
                                    <div class="card-body">
      
                                        <h4 class="mt-0 header-title">Booking Details</h4>
                                       <?php echo $msg; ?>
                                        
                                            <form name="department" id="department" action="#" method="post" enctype="multipart/form-data" autocomplete="off" >
                                    <div class="box box-info">
                                        <div class="box-body">
                                            <div class="panel panel-info">
                                                <div class="panel-heading">
                                                    <div class="panel-title">&nbsp;</div>
                                                </div>
                                                <div class="panel-body">
                                                 
                            <div class="row">
                                <div class="col-md-3">
                                  Pickup Address:
                                </div>
                                <div class="col-md-6">
                                    <?php echo getbooking('pickup_address', $_REQUEST['id']); ?>
                                </div>
                            </div>
                            <br />
						 <div class="row">
                                <div class="col-md-3">
                                  Drop Address:
                                </div>
                                <div class="col-md-6">
                                    <?php echo getbooking('drop_address', $_REQUEST['id']); ?>
                                </div>
                            </div>
                            <br />
						
							 <div class="row">
                                <div class="col-md-3">
                                  Customer Name:
                                </div>
                                <div class="col-md-6">
                                    <?php echo getregisterform('name',getbooking('register_id', $_REQUEST['id'])); ?>
                                </div>
                            </div>
                            <br />
						    	 <div class="row">
                                <div class="col-md-3">
                                  Customer Mobileno:
                                </div>
                                <div class="col-md-6">
                                    <?php echo getregisterform('mobileno',getbooking('register_id', $_REQUEST['id'])); ?>
                                </div>
                            </div>
                            <br />
                             <div class="row">
                                <div class="col-md-3">
                                  Price Per km:
                                </div>
                                <div class="col-md-6">
                                    <?php echo getregisterform('mobileno',getbooking('register_id', $_REQUEST['id'])); ?>
                                </div>
                            </div>
                            <br />
                               <div class="row">
                                <div class="col-md-3">
                                  Rental Amount:
                                </div>
                                <div class="col-md-6">
                                    <?php echo getregisterform('mobileno',getbooking('register_id', $_REQUEST['id'])); ?>
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-md-3">
                                    Date:
                                </div>
                                <div class="col-md-3">
                                    <?php echo date('d-M-Y , g:i a', strtotime(getbooking('date', $_REQUEST['id']))); ?>
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

<script>
    function click1()
    {

        $('#demo').css("display", "block");

    }
</script>