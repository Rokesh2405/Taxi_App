<?php
$menu = "44,44,45";
$thispageeditid = 45;
include ('../../config/config.inc.php');
$dynamic = '1';

include ('../../require/header.php');

if (isset($_REQUEST['submit'])) {
    global $db;
    @extract($_REQUEST);
    $getid = $_REQUEST['stid'];
    $ip = $_SERVER['REMOTE_ADDR'];
    
      $resa = $db->prepare("UPDATE `static_pages` SET `pagecontent`=? WHERE `id`=? ");
      $resa->execute(array($pagecontent,$_REQUEST['stid']));
    $msg = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button><h5><i class="icon fa fa-check"></i>Updated Successfully</h5></div>';
               
            
}


if (isset($_REQUEST['stid'])) {

    $gettid = $_REQUEST['stid'];

    $editresult = $db->prepare("SELECT * FROM `static_pages` where `id` = ? ");
    $editresult->execute(array($gettid));
    $editresult1 = $editresult->fetch();
    //$editresult = DB_QUERY("SELECT * FROM `banner` where `bid` =$gettid");
}
?>
 <div class="content-page">
        
<div class="content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="page-title-box">
                                    <h4 class="page-title"><?php
                            if ($_REQUEST['stid'] == 1) {
                echo $title = "Terms & Conditions";
            } 
                            ?> </h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Static Pages</a></li>
                                        <li class="breadcrumb-item active"><?php
                            if ($_REQUEST['stid'] == 1) {
                echo $title = "Terms & Conditions";
            } 
                            ?> </li>
                                    </ol>
            
                             
                                    
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
<?php echo $msg; ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card m-b-20">
                                    <div class="card-body">
      
                                        <h4 class="mt-0 header-title">Content</h4>
                                       
                                        
                                      <form method="post" name="pageform">
									      <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title"></div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea name="pagecontent" class="form-control" placeholder="Enter the Full Content" style="width:100%;" rows="10" ><?php echo $editresult1['pagecontent']; ?></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">

<button type="submit" name="submit" id="submit" class="btn btn-success" style="float:right;"><?php
                                if ($_REQUEST['stid'] != '') {
                                    echo 'UPDATE';
                                } else {
                                    echo 'INSERT';
                                }
                                ?></button>
                                </div>
                            </div>
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