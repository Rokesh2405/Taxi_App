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

    $imagec = time();

    $imag = strtolower($_FILES["image"]["name"]);

    if ($getid != '') {
        $linkimge = $db->prepare("SELECT * FROM `cars` WHERE `id` = ? ");
        $linkimge->execute(array($getid));
        $linkimge1 = $linkimge->fetch();
        $pimage = $linkimge1['image'];
    }
    if ($imag != '') {
        if ($pimage != '') {
            unlink("../../images/cars/" . $pimage);
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
            $thumppath = "../../images/cars/";
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

$amenitiesl=implode(',',$amenities);
    $msg = addcar($round_base_fare_km,$round_per_km,$round_beta_fee,$round_base_fare,$round_other_beta_fee,$base_fare_km,$per_km,$beta_fee,$base_fare,$other_beta_fee,$amenitiesl,$type,$name,$sit_count,$ac_status,$rental_amount,$image,$status, $ip, $getid);
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
                                    <h4 class="page-title">Cars</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Cars</a></li>
                                        <li class="breadcrumb-item active"><?php
                            if (isset($_REQUEST['id'])) {
                                echo "View";
                            } else {
                                echo "Add";
                            }
                            ?> Car </li>
                                    </ol>
            
                                <div class="state-information d-none d-sm-block">
                                    <h4 class="page-title"><a href="<?php echo $sitename; ?>master/cars.htm">Back to Listing</a></h4>
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
                                            <div class="col-md-12"><h5>Car Details</h5></div>    
                                            </div>
                                             <br>
                                            <div class="row">
                                            <div class="col-md-2"><label>Car Name&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('name',$_REQUEST['banid']); ?>" required="required" name="name"></div> 
                                              <div class="col-md-2"><label>Type&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                              <div class="col-md-4">
                                              <select name="type" id="type" class="form-control" required="required">
                                                        <option value="">Select</option>   
                                                        <?php
                                                        $sno = 1;
                                                        $type = pFETCH("SELECT * FROM `types` WHERE `status`=? ", 1);
                                                        while ($typefetch = $type->fetch(PDO::FETCH_ASSOC)) {
                                                            ?>
                                                            <option value="<?php echo $typefetch['id']; ?>" <?php if (getcar('type', $_REQUEST['banid']) == $typefetch['id']) { ?> selected="selected" <?php } ?>><?php echo $typefetch['name']; ?></option>   
                                                        <?php } ?>    
                                                    </select>  
                                            
                                          </div>
                                          </div>
                                        <br>
                                         <div class="row">
                                                 <div class="col-md-2"><label>Rental Amount&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('rental_amount',$_REQUEST['banid']); ?>" required="required" name="rental_amount"></div> 
                                           
                                              <div class="col-md-2"><label>Count of Seats&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('sit_count',$_REQUEST['banid']); ?>" required="required" name="sit_count"></div> 
                                            </div>
                                             <br>
                                        <!-- <div class="row">-->
                                        <!--    <div class="col-md-2"><label>Having AC ?&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> -->
                                        <!--     <div class="col-md-4">-->
                                        <!--     <select name="ac_status" class="form-control">-->
                                        <!--         <option value="">Select</option>-->
                                        <!--    <option value="Yes">Yes</option>        <option value="No">No</option>-->
                                        <!--     </select>-->
                                        <!--     </div> -->
                                          
                                        <!--    </div>-->
                                          
                                        <!--<br>-->
                                        
                                        <div class="row">
                                        <div class="col-md-2">
                                        <label>Image&nbsp;&nbsp;<span style="color:red;">*</span></label>    
                                        </div>    
                                        <div class="col-md-4">
                                            <input <?php if (getcar('image', $_REQUEST['banid']) == '') { ?>required="required"<?php } ?> class="form-control spinner" name="image" type="file"> 
                                        </div>
                                         <?php if (getcar('image', $_REQUEST['banid']) != '') { ?>
                                        <div class="col-md-6" id="delimage">
                                            <img src="<?php echo $fsitename; ?>images/cars/<?php echo getcar('image', $_REQUEST['banid']); ?>" style="padding-bottom:10px;" height="100" />
                                                        <button type="button" style="cursor:pointer;" class="btn btn-danger" name="del" id="del" onclick="javascript:deleteimage('<?php echo getcar('image', $_REQUEST['banid']); ?>', '<?php echo $_REQUEST['banid']; ?>', 'cars', '../images/cars/', 'image', 'id');"><i class="fa fa-close">&nbsp;Delete Image</i></button>
                                            
                                        </div>
                                        <?php } ?>
                                        </div>
                                         
                                     <br>
                                        <div class="row">
                                        <div class="col-md-12">
                                        <label>Car Amenities </label>   
                                        </div>    
                                        </div>
                                        <br>
                                        <?php
                                        $amenitiesl=explode(',',getcar('amenities', $_REQUEST['banid']));
                                        ?>
                                        <div class="row">
                                        <div class="col-md-3">
                                        <input type="checkbox" name="amenities[]" value="Televisions" <?php if(in_array('Televisions',$amenitiesl)) { ?> checked="checked" <?php } ?>>&nbsp;&nbsp;Televisions  
                                        </div>  
                                        <div class="col-md-3">
                                        <input type="checkbox" name="amenities[]" value="Audio System" <?php if(in_array('Audio System',$amenitiesl)) { ?> checked="checked" <?php } ?>>&nbsp;&nbsp;Audio System  
                                        </div>  
                                        <div class="col-md-3">
                                        <input type="checkbox" name="amenities[]" <?php if(in_array('AC',$amenitiesl)) { ?> checked="checked" <?php } ?> value="AC">&nbsp;&nbsp;AC  
                                        </div>  
                                        
                                        </div>
                                       <br><br>
                                         <div class="row">
                                        <!--      <div class="col-md-2"><label>Driver Charge&nbsp;&nbsp;<span style="color:red;">*</span></label></div>   -->
                                        <!--<div class="col-md-4">-->
                                        <!--    <input type="text" name="driver_charge" class="form-control" value="<?php echo getcar('driver_charge',$_REQUEST['banid']); ?>">-->
                                        <!--    </div>-->
                                        <div class="col-md-2"><label>Status</label></div>   
                                        <div class="col-md-4"> <select name="status" class="form-control">
                                            <option value="1" <?php if(getcar('status',$_REQUEST['banid'])=='1') { ?> selected="selected" <?php } ?>>Active</option>
                                            <option value="0" <?php if(getcar('status',$_REQUEST['banid'])=='0') { ?> selected="selected" <?php } ?>>Inactive</option>
                                            </select>
                                            </div>
                                        </div>
                                        <br>
                                        <hr>
                                        <div class="row">
                                        <div class="col-md-12"><h5>Oneway Price Details</h5></div>        
                                        </div>
                                        <br>
                                       <div class="row">
                                            <div class="col-md-2"><label>Price Per KM&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('per_km',$_REQUEST['banid']); ?>" required="required" name="per_km"></div> 
                                             <div class="col-md-2"><label>Km for Base Fare&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('base_fare_km',$_REQUEST['banid']); ?>" required="required" name="base_fare_km"></div>
                                            
                                       </div>
                                       <br>
                                         <div class="row">
                                              <div class="col-md-2"><label>Base Fare&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('base_fare',$_REQUEST['banid']); ?>" required="required" name="base_fare"></div>
                                            <div class="col-md-2"><label>Beta Fee (Driver Charge)&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('beta_fee',$_REQUEST['banid']); ?>" required="required" name="beta_fee"></div> 
                                            
                                       </div>
                                       <br>
                                       <!--<div class="row">-->
                                       <!--     <div class="col-md-2"><label>Other Beta Fee&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> -->
                                       <!--      <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('other_beta_fee',$_REQUEST['banid']); ?>" required="required" name="other_beta_fee"></div>-->
                                       <!--</div>-->
                                       <!--<br>-->
                                       
                                        <hr>
                                        <div class="row">
                                        <div class="col-md-12"><h5>Round Trip Price Details</h5></div>        
                                        </div>
                                        <br>
                                       <div class="row">
                                            <div class="col-md-2"><label>Price Per KM&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('round_per_km',$_REQUEST['banid']); ?>" required="required" name="round_per_km"></div> 
                                             <div class="col-md-2"><label>Km for Base Fare&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('round_base_fare_km',$_REQUEST['banid']); ?>" required="required" name="round_base_fare_km"></div>
                                            
                                       </div>
                                       <br>
                                         <div class="row">
                                              <div class="col-md-2"><label>Base Fare&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('round_base_fare',$_REQUEST['banid']); ?>" required="required" name="round_base_fare"></div>
                                            <div class="col-md-2"><label>Beta Fee (Driver Charge)&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('round_beta_fee',$_REQUEST['banid']); ?>" required="required" name="round_beta_fee"></div> 
                                            
                                       </div>
                                       <br>
                                       <!--<div class="row">-->
                                       <!--     <div class="col-md-2"><label>Other Beta Fee&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> -->
                                       <!--      <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getcar('round_other_beta_fee',$_REQUEST['banid']); ?>" required="required" name="round_other_beta_fee"></div>-->
                                       <!--</div>-->
                                       <!--<br>-->
                                      
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