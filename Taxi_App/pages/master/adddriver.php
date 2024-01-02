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

if (isset($_REQUEST['submit'])) {
    @extract($_REQUEST);
    $getid = $_REQUEST['banid'];
    $ip = $_SERVER['REMOTE_ADDR'];
//Profile Image
$imagec = time();

    $imag = strtolower($_FILES["image"]["name"]);
$imag1 = strtolower($_FILES["image1"]["name"]);
$imag2 = strtolower($_FILES["image2"]["name"]);
    if ($getid != '') {
        $linkimge = $db->prepare("SELECT * FROM `driver` WHERE `id` = ? ");
        $linkimge->execute(array($getid));
        $linkimge1 = $linkimge->fetch();
        $pimage = $linkimge1['profile'];
        $pimage1 = $linkimge1['license'];
        $pimage2 = $linkimge1['rc_book'];
    }
    if ($imag != '') {
        if ($pimage != '') {
            unlink("../../images/dprofile/" . $pimage);
        }
       
        $main = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        $size = $_FILES['image']['size'];
        $width = 1000;
        $height = 1000;
        $extension = getExtension($main);
        $extension = strtolower($extension);
        if (($extension == 'jpg') || ($extension == 'png') || ($extension == 'gif') || ($extension == 'jpeg')) {
            $m = time();
            $imagev = $m . "." . $extension;
            $thumppath = "../../images/dprofile/";
//            move_uploaded_file($tmp, $thumppath . $imagev);

            $compressedImage = compressImage($tmp, $thumppath . $imagev, 75);
        } else {
            $ext = '1';
        }
        $image = $imagev;
    } else {
        if ($_REQUEST['banid']) {
            $image = $pimage;
        } else {
            $image = '';
        }
    }
    
    
// Licence Image
 if ($imag1 != '') {
        if ($pimage1 != '') {
            unlink("../../images/license/" . $pimage1);
        }
       
        $main = $_FILES['image1']['name'];
        $tmp = $_FILES['image1']['tmp_name'];
        $size = $_FILES['image1']['size'];
        $width = 1000;
        $height = 1000;
        $extension = getExtension($main);
        $extension = strtolower($extension);
        if (($extension == 'jpg') || ($extension == 'png') || ($extension == 'gif') || ($extension == 'jpeg')) {
            $m = time();
            $imagev = $m . "." . $extension;
            $thumppath = "../../images/license/";
//            move_uploaded_file($tmp, $thumppath . $imagev);

            $compressedImage = compressImage($tmp, $thumppath . $imagev, 75);
        } else {
            $ext = '1';
        }
        $image1 = $imagev;
    } else {
        if ($_REQUEST['banid']) {
            $image1 = $pimage1;
        } else {
            $image1 = '';
        }
    }
    
        
// Rc Book Image
 if ($imag2 != '') {
        if ($pimage2 != '') {
            unlink("../../images/rc_book/" . $pimage2);
        }
       
        $main = $_FILES['image2']['name'];
        $tmp = $_FILES['image2']['tmp_name'];
        $size = $_FILES['image2']['size'];
        $width = 1000;
        $height = 1000;
        $extension = getExtension($main);
        $extension = strtolower($extension);
        if (($extension == 'jpg') || ($extension == 'png') || ($extension == 'gif') || ($extension == 'jpeg')) {
            $m = time();
            $imagev = $m . "." . $extension;
            $thumppath = "../../images/rc_book/";
//            move_uploaded_file($tmp, $thumppath . $imagev);

            $compressedImage = compressImage($tmp, $thumppath . $imagev, 75);
        } else {
            $ext = '1';
        }
        $image2 = $imagev;
    } else {
        if ($_REQUEST['banid']) {
            $image2 = $pimage2;
        } else {
            $image2 = '';
        }
    }

    $msg = adddriver($wallet,$image,$image1,$image2,$driver_name,$driver_mobileno,$licence_no,$car_no,$login_username,$login_password,$driver_address,$car_id,$status, $ip, $getid);
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
                                    <h4 class="page-title">Drivers</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Drivers</a></li>
                                        <li class="breadcrumb-item active"><?php
                            if (isset($_REQUEST['id'])) {
                                echo "View";
                            } else {
                                echo "Add";
                            }
                            ?> Driver</li>
                                    </ol>
            
                                <div class="state-information d-none d-sm-block">
                                    <h4 class="page-title"><a href="<?php echo $sitename; ?>master/driver.htm">Back to Listing</a></h4>
                                    </div>

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
                                            <div class="col-md-12"><h5>Driver Details</h5></div>    
                                            </div>
                                            <br>
                                            <div class="row">
                                            <div class="col-md-2"><label>Driver Name&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getdriver('driver_name',$_REQUEST['banid']); ?>" required="required" name="driver_name"></div> 
                                             <div class="col-md-2"><label>Car Name</label></div>
                                             <div class="col-md-4">
                                                 <select name="car_id" id="type" class="form-control" required="required">
                                                        <option value="">Select</option>   
                                                        <?php
                                                        $sno = 1;
                                                        $type = pFETCH("SELECT * FROM `cars` WHERE `status`=? ", 1);
                                                        while ($typefetch = $type->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                            <option value="<?php echo $typefetch['id']; ?>" <?php if (getdriver('car_id', $_REQUEST['banid']) == $typefetch['id']) { ?> selected="selected" <?php } ?>><?php echo $typefetch['name']; ?></option>   
                                                        <?php } ?>    
                                                    </select>   
                                             </div>
                                           
                                             </div>
                                            <br>
                                             <div class="row">
                                                   <div class="col-md-2"><label>Driver Mobileno&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getdriver('driver_mobileno',$_REQUEST['banid']); ?>" required="required" name="driver_mobileno"></div> 
                                            <div class="col-md-2"><label>Licence No&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getdriver('licence_no',$_REQUEST['banid']); ?>" required="required" name="licence_no"></div> 
                                             
                                             </div>
                                            <br>
                                            <div class="row">
                                            <div class="col-md-2"><label>Login Username&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getdriver('login_username',$_REQUEST['banid']); ?>" required="required" name="login_username"></div> 
                                             <div class="col-md-2"><label>Login Password&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getdriver('login_password',$_REQUEST['banid']); ?>" required="required" name="login_password"></div> 
                                             </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-2"><label>Car No&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getdriver('car_no',$_REQUEST['banid']); ?>" required="required" name="car_no"></div> 
                                            <div class="col-md-2"><label>Address&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4">
                                                 <textarea name="driver_address" class="form-control" required="required"><?php echo getdriver('driver_address',$_REQUEST['banid']); ?></textarea>
                                               </div>
                                             </div>
                                             <br>
                                          
                                        <div class="row">
                                        <div class="col-md-2">
                                        <label>Profile Image</label>    
                                        </div>    
                                        <div class="col-md-4">
                                            <input class="form-control spinner" name="image" type="file"> 
                                        </div>
                                         <?php if (getdriver('profile', $_REQUEST['banid']) != '') { ?>
                                        <div class="col-md-6" id="delimage">
                                            <img src="<?php echo $fsitename; ?>images/dprofile/<?php echo getdriver('profile', $_REQUEST['banid']); ?>" style="padding-bottom:10px;" height="100" />
                                                        <button type="button" style="cursor:pointer;" class="btn btn-danger" name="del" id="del" onclick="javascript:deleteimage('<?php echo getdriver('profile', $_REQUEST['banid']); ?>', '<?php echo $_REQUEST['banid']; ?>', 'driver', '../images/dprofile/', 'profile', 'id');"><i class="fa fa-close">&nbsp;Delete Image</i></button>
                                            
                                        </div>
                                        <?php } ?>
                                        </div>
                                         
                                     <br>
                                     
                                     <div class="row">
                                        <div class="col-md-2">
                                        <label>License Image</label>    
                                        </div>    
                                        <div class="col-md-4">
                                            <input class="form-control spinner" name="image1" type="file"> 
                                        </div>
                                         <?php if (getdriver('license', $_REQUEST['banid']) != '') { ?>
                                        <div class="col-md-6" id="delimage1">
                                            <img src="<?php echo $fsitename; ?>images/license/<?php echo getdriver('license', $_REQUEST['banid']); ?>" style="padding-bottom:10px;" height="100" />
                                                        <button type="button" style="cursor:pointer;" class="btn btn-danger" name="del1" id="del1" onclick="javascript:deleteimage1('<?php echo getdriver('license', $_REQUEST['banid']); ?>', '<?php echo $_REQUEST['banid']; ?>', 'driver', '../images/license/', 'license', 'id');"><i class="fa fa-close">&nbsp;Delete Image</i></button>
                                            
                                        </div>
                                        <?php } ?>
                                        </div>
                                         
                                     <br>
                                     <div class="row">
                                        <div class="col-md-2">
                                        <label>RC Book Image</label>    
                                        </div>    
                                        <div class="col-md-4">
                                            <input class="form-control spinner" name="image2" type="file"> 
                                        </div>
                                         <?php if (getdriver('rc_book', $_REQUEST['banid']) != '') { ?>
                                        <div class="col-md-6" id="delimage2">
                                            <img src="<?php echo $fsitename; ?>images/rc_book/<?php echo getdriver('rc_book', $_REQUEST['banid']); ?>" style="padding-bottom:10px;" height="100" />
                                                        <button type="button" style="cursor:pointer;" class="btn btn-danger" name="del2" id="del2" onclick="javascript:deleteimage2('<?php echo getdriver('rc_book', $_REQUEST['banid']); ?>', '<?php echo $_REQUEST['banid']; ?>', 'driver', '../images/rc_book/', 'rc_book', 'id');"><i class="fa fa-close">&nbsp;Delete Image</i></button>
                                            
                                        </div>
                                        <?php } ?>
                                        </div>
                                         
                                     <br>
                                        <div class="row">
                                            <div class="col-md-2"><label>Wallet Amount</label></div>   
                                        <div class="col-md-4">
											<input type="number" readonly="readonly" name="wallet" class="form-control" value="<?php echo getdriver('wallet',$_REQUEST['banid']); ?>">
                                            </div>  
                                        <div class="col-md-2"><label>Status</label></div>   
                                        <div class="col-md-4"> <select name="status" class="form-control">
                                            <option value="1" <?php if(getdriver('status',$_REQUEST['banid'])=='1') { ?> selected="selected" <?php } ?>>Active</option>
                                            <option value="0" <?php if(getdriver('status',$_REQUEST['banid'])=='0') { ?> selected="selected" <?php } ?>>Inactive</option>
                                            </select>
                                            </div>
                                        </div>
                                        <br>
                                        <div>
                                             <button type="submit" name="submit" id="submit" class="btn btn-primary waves-effect waves-light"><?php
                                if ($_REQUEST['banid'] != '') {
                                    echo 'UPDATE';
                                } else {
                                    echo 'SUBMIT';
                                }
                                ?></button>
                                
                                
                                        <button type="reset" class="btn btn-secondary waves-effect m-l-5">CANCEL</button>
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