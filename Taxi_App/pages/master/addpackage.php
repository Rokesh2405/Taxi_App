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
        $linkimge = $db->prepare("SELECT * FROM `packages` WHERE `id` = ? ");
        $linkimge->execute(array($getid));
        $linkimge1 = $linkimge->fetch();
        $pimage = $linkimge1['image'];
    }
    if ($imag != '') {
        if ($pimage != '') {
            unlink("../../images/packages/" . $pimage);
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
            $thumppath = "../../images/packages/";
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


    $msg = addpackage($location,$distance,$sedan_car_price,$suv_car_price,$image,$status, $ip, $getid);
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
                                    <h4 class="page-title">Packages</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Packages</a></li>
                                        <li class="breadcrumb-item active"><?php
                            if (isset($_REQUEST['id'])) {
                                echo "View";
                            } else {
                                echo "Add";
                            }
                            ?> Package </li>
                                    </ol>
            
                                <div class="state-information d-none d-sm-block">
                                    <h4 class="page-title"><a href="<?php echo $sitename; ?>master/packages.htm">Back to Listing</a></h4>
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
                                            <div class="col-md-2"><label>Location&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getpackage('location',$_REQUEST['banid']); ?>" required="required" name="location"></div> 
                                              <div class="col-md-2"><label>Distance&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getpackage('distance',$_REQUEST['banid']); ?>" required="required" name="distance"></div> 
                                            </div>
                                            
                                          
                                        <br>
                                         <div class="row">
                                            <div class="col-md-2"><label>Sedan Car Price&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getpackage('sedan_car_price',$_REQUEST['banid']); ?>" required="required" name="sedan_car_price"></div> 
                                              <div class="col-md-2"><label>SUV Car Price&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getpackage('suv_car_price',$_REQUEST['banid']); ?>" required="required" name="suv_car_price"></div> 
                                            </div>
                                            
                                          
                                        <br>
                                        
                                        <div class="row">
                                        <div class="col-md-2">
                                        <label>Image</label>    
                                        </div>    
                                        <div class="col-md-4">
                                            <input <?php if (getpackage('image', $_REQUEST['banid']) == '') { ?>required="required"<?php } ?> class="form-control spinner" name="image" type="file"> 
                                        </div>
                                         <?php if (getpackage('image', $_REQUEST['banid']) != '') { ?>
                                        <div class="col-md-6" id="delimage">
                                            <img src="<?php echo $fsitename; ?>images/packages/<?php echo getpackage('image', $_REQUEST['banid']); ?>" style="padding-bottom:10px;" height="100" />
                                                        <button type="button" style="cursor:pointer;" class="btn btn-danger" name="del" id="del" onclick="javascript:deleteimage('<?php echo getpackage('image', $_REQUEST['banid']); ?>', '<?php echo $_REQUEST['banid']; ?>', 'packages', '../images/packages/', 'image', 'id');"><i class="fa fa-close">&nbsp;Delete Image</i></button>
                                            
                                        </div>
                                        <?php } ?>
                                        </div>
                                         
                                     <br>
                                        
                                        <div class="row">
                                        <div class="col-md-2"><label>Status</label></div>   
                                        <div class="col-md-4"> <select name="status" class="form-control">
                                            <option value="1" <?php if(getpackage('status',$_REQUEST['banid'])=='1') { ?> selected="selected" <?php } ?>>Active</option>
                                            <option value="0" <?php if(getpackage('status',$_REQUEST['banid'])=='0') { ?> selected="selected" <?php } ?>>Inactive</option>
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