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
    
  if ($password == $cpassword) {
            $resa = $db->prepare("UPDATE `users` SET `val2`=?,`orgpassword`=? WHERE `id`=?");
            $resa->execute(array(md5(trim($password)),$password,'1'));
            echo '<script>alert("Updated Successfully");window.location.href = "https://droptaximadurai.in/appadmin/master/change_password.htm";</script>'; 
        } else {
          echo '<script>alert("Password Does not Match");window.location.href = "https://droptaximadurai.in/appadmin/master/change_password.htm";</script>'; 
        }
	
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
                                    <h4 class="page-title">Change Password</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Change Password</a></li>
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
                           <div class="col-md-3"><strong>New Password <span style="color:#FF0000;">*</span></strong></div>
                            <div class="col-md-3"> <input type="password" required="required" class="form-control" placeholder="New Password" name="password" id="password" min="6" Max="255" value="" /></div>
							</div>
							<br>
							<div class="row">
                            <div class="col-md-3"><strong>Confirm Password<span style="color:#FF0000;">*</span></strong></div>
                            <div class="col-md-3"><input type="password" required="required" class="form-control" placeholder="Confirm Password" name="cpassword" id="cpassword" min="6" Max="255" value="" /></div>
                            </div>
                            <br>
                         
							
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