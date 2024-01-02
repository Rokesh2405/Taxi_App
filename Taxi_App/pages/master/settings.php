<?php
$menu = "8";
$thispageid = 17;
$franchisee = 'yes';
include ('../../config/config.inc.php');
include ('../../require/header.php');
// error_reporting(1);
// ini_set('display_errors','1');
// error_reporting(E_ALL);
$settingval = FETCH_all("SELECT * FROM `users` WHERE `id`=? ", $_SESSION['ALOID']);

if (isset($_REQUEST['update'])) {
    @extract($_REQUEST);
    global $db;
    $ip = $_SERVER['REMOTE_ADDR'];
    
 
            $resa = $db->prepare("UPDATE `users` SET `min_wallet`=?,`wallet_percentage`=?,`loyalty_amount`=?,`terms`=?,`upi_id`=?,`waiting_charge`=?,`min_driver_charge`=? WHERE `id`=?");
            $resa->execute(array(trim($min_wallet,$wallet_percentage),trim($loyalty_amount),trim($terms),trim($upiid),trim($waiting_charge),trim($min_driver_charge),$_SESSION['ALOID']));
            echo '<script>alert("Updated Successfully");window.location.href = "https://droptaximadurai.in/appadmin/master/settings.htm";</script>'; 
            
            //header("location:https://webtoall.in/droptaxi/master/settings.htm");   
        
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
                                    <h4 class="page-title">General Settings</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Settings</a></li>
                                   </ol>
            
                              </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card m-b-20">
                                    <div class="card-body">
        
                                        <?php echo $msg; ?>
                                           
                                        <form name="form1" method="post" autocomplete="off">
                                            <br>
                     <div class="panel panel-info">
                        <!--<div class="panel-heading">-->
                        <!--    <div class="panel-title">Change UPI ID</div>-->
                        <!--</div>-->
                        <div class="panel-body">
                           
                            <div class="row">
                           <div class="col-md-3"><strong>UPI ID <span style="color:#FF0000;">*</span></strong></div>
                            <div class="col-md-3"><input type="text" name="upiid" id="upiid" required="required" class="form-control" value="<?php echo $settingval['upi_id']; ?>"></div>
                            <div class="col-md-3"><strong>Waiting Charge<span style="color:#FF0000;">*</span></strong></div>
                            <div class="col-md-3"><input type="text" name="waiting_charge" id="waiting_charge" required="required" class="form-control" value="<?php echo $settingval['waiting_charge']; ?>"></div>
                            </div>
                            <br>
                          <div class="row">
                        <div class="col-md-3" align="left">
                        <label><strong>Minimum Amount of Driver Charge</strong></strong> <span style="color:#FF0000;">*</span></label>    
                        </div> 
                        <div class="col-md-3"  align="left">
                       <input type="text" name="min_driver_charge" id="min_driver_charge" required="required" class="form-control" value="<?php echo $settingval['min_driver_charge']; ?>">
                        </div> 
							 <div class="col-md-3" align="left">
                        <label><strong>Per Loyalty Point Amount</strong> <span style="color:#FF0000;">*</span></label>    
                        </div> 
                        <div class="col-md-3"  align="left">
                       <input type="text" name="loyalty_amount" id="min_driver_charge" required="required" class="form-control" value="<?php echo $settingval['loyalty_amount']; ?>">
                        </div> 
						 
                        </div>
                             <br>   
											<div class="row">
												 <div class="col-md-3" align="left">
                        <label><strong>Wallet Commission Percentage</strong> <span style="color:#FF0000;">*</span></label>    
                        </div> 
												<div class="col-md-3"  align="left">
                       <input type="text" required="required" name="wallet_percentage" id="wallet_percentage" required="required" class="form-control" value="<?php echo $settingval['wallet_percentage']; ?>">
                        </div> 
						  <div class="col-md-3" align="left">
                        <label><strong>Minimum Wallet Amount</strong> <span style="color:#FF0000;">*</span></label>    
                        </div> 
												<div class="col-md-3"  align="left">
                       <input type="text" required="required" name="min_wallet" id="min_wallet" required="required" class="form-control" value="<?php echo $settingval['min_wallet']; ?>">
                        </div> 
                        </div>
                             <br>  

                            <div class="row">
                             <div class="col-md-6"><strong>Terms & Conditions<span style="color:#FF0000;">*</span></strong></div>    
                            </div>
                            <br>
                            <div class="row">
                            <div class="col-md-12">
                            <textarea name="terms" required="required" class="form-control" rows="10"><?php echo $settingval['terms']; ?></textarea>    
                            </div>    
                            </div>
                              <br>
                            <div class="row">
                            <div class="col-md-3"><button type="submit"  id="update" name="update" class="btn btn-success"> Update</button></div>
                            </div>
                            <br>
                           
                            </div>
                          
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