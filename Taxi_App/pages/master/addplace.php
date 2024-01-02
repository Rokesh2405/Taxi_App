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

  $msg = addplace($place,$status, $getid);
}
?>
<script type="text/javascript" >
   function checkdelete(name)
    {
        if (confirm("Do you want to delete the Place"))
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
                                    <h4 class="page-title">Places</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Master</a></li>
                                        <li class="breadcrumb-item active"><?php
                            if (isset($_REQUEST['id'])) {
                                echo "View";
                            } else {
                                echo "Add";
                            }
                            ?> Place </li>
                                    </ol>
            
                                <div class="state-information d-none d-sm-block">
                                    <h4 class="page-title"><a href="<?php echo $sitename; ?>master/places.htm">Back to Listing</a></h4>
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
                                            <div class="col-md-12"><h5>Place Details</h5></div>    
                                            </div>
                                             <br>
                                            <div class="row">
                                            <div class="col-md-2"><label>Place Name&nbsp;&nbsp;<span style="color:red;">*</span></label> </div> 
                                             <div class="col-md-4"><input class="form-control" type="text" value="<?php echo getplace('place',$_REQUEST['banid']); ?>" required="required" name="place"></div> 
                                            
 <div class="col-md-2"><label>Status</label></div>   
                                        <div class="col-md-4"> <select name="status" class="form-control">
                                            <option value="1" <?php if(getplace('status',$_REQUEST['banid'])=='1') { ?> selected="selected" <?php } ?>>Active</option>
                                            <option value="0" <?php if(getplace('status',$_REQUEST['banid'])=='0') { ?> selected="selected" <?php } ?>>Inactive</option>
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