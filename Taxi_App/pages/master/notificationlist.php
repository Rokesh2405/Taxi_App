<?php
$menu = "4";
include ('../../config/config.inc.php');
$dynamic = '1';
//$datepicker = '1';
$datatable = '1';

include ('../../require/header.php');

if (isset($_REQUEST['delete']) || isset($_REQUEST['delete_x'])) {
    $chk = $_REQUEST['chk'];
    $chk = implode('.', $chk);
   
    $msg = delregisterform($chk);
}
?>
<script type="text/javascript" >
    function validcheck(name)
    {
        var chObj = document.getElementsByName(name);
        var result = false;
        for (var i = 0; i < chObj.length; i++) {
            if (chObj[i].checked) {
                result = true;
                break;
            }
        }
        if (!result) {
            return false;
        } else {
            return true;
        }
    }

    function checkdelete(name)
    {
        if (validcheck(name) == true)
        {
            if (confirm("Please confirm you want to Delete this User(s)"))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else if (validcheck(name) == false)
        {
            alert("Select the check box whom you want to delete.");
            return false;
        }
    }

</script>
<script type="text/javascript">
    function checkall(objForm) {
        len = objForm.elements.length;
        var i = 0;
        for (i = 0; i < len; i++) {
            if (objForm.elements[i].type == 'checkbox') {
                objForm.elements[i].checked = objForm.check_all.checked;
            }
        }
    }
</script>

<style type="text/css">
    .row { margin:0;}
    #normalexamples tbody tr td:nth-child(1),tbody tr td:nth-child(3), tbody tr td:nth-child(4),tbody tr td:nth-child(5),tbody tr td:nth-child(6),tbody tr td:nth-child(7) {
        text-align:center;
    }
</style>
       
        <div class="content-page">
        
  
  <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="page-title-box">
                                    <h4 class="page-title">Notifications List</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Forms </a></li>
                                        <li class="breadcrumb-item active">Notifications List</li>
                                    </ol>
            
                                  
                                    
                                </div>
                            </div>
                              <div class="col-md-6">
                                  <!--<br>-->
                                  <!--  <a href="<?php echo $sitename.'master/userexport.htm'; ?>" style="color:blue;font-weight:bold;"> <button type="button" name="export" id="export" class="btn btn-success" style="float:left; float:right;">Export as Excel</button>    -->
                                  <!--             </a>    -->
                                    </div>    
                        </div>
                        
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card m-b-20">
                                    <div class="card-body">
                                   
<!--
                                        <h4 class="mt-0 header-title">Default Datatable</h4>
                                        <p class="text-muted m-b-30">DataTables has most features enabled by
                                            default, so all you need to do to use it with your own tables is to call
                                            the construction function: <code>$().DataTable();</code>.
                                        </p>-->
<?php echo $msg; ?>
<form name="listform" id="listform" method="post">
<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                      
                                            <thead>
                                <tr align="center">
                                    <th style="width:5%;">S.id</th>
                                    <th style="width:15%;">Date</th>
                                    <th style="width:15%;">From</th>
                                    <th style="width:20%;">Title</th>
                                    <th style="width:20%;">Message</th>
                                     <th style="width:20%;">Pickup Address</th>
                                      <th style="width:20%;">Drop Address</th>
                                    <th style="width:20%;">Trip Status</th>
                                 </tr>
                            </thead>   
                            <tbody>
                             <?php
                            $o = '1';
$ord = $db->prepare("SELECT * FROM `notification` WHERE (`to`=? OR `title`=? OR `title`=? OR (`message`=? AND `driver_name` IS NULL)) ORDER BY `id` DESC ");	
$ord->execute(array('admin','DROPTAXI - Your Trip is Started','DROPTAXI - Your Trip is End','Confirm to get this booking'));
$ordnum = $ord->rowCount(); 
if($ordnum>0) { 
while ($ford = $ord->fetch(PDO::FETCH_ASSOC)) {
    $tripfrom=explode('-',$ford['type']);
$bkss = $db->prepare("SELECT * FROM `booking` WHERE `id`='".$ford['booking_id']."' AND `amount_from_customer`!='' ORDER BY `id` DESC ");	
$bkss->execute();
$bkssnum = $bkss->rowCount(); 
if($bkssnum>0){
    $trpstatus="Closed";
}
else
{
   $trpstatus="Open";  
}
         if($ford['type']=='User-Admin') { 
                    $linkk=$sitename.'master/booking.htm';    
                    }
                    elseif($ford['title']=='DROPTAXI - Quote Price for Booking')
                    {
                     $linkk=$sitename.'master/driver_bittings.htm';        
                    }
                    elseif($ford['title']=='DROPTAXI - Your Trip is Started')
                    {
                     $linkk=$sitename.'master/closedtrips.htm';        
                    }
                    else 
                    {
                     $linkk=$sitename.'master/confirmedtrip.htm';        
                    }
                    
                                        ?>
                             <tr>
                            <td <?php if($ford['read_status']==0) { ?> style="font-weight:bold;" <?php } ?>><?php echo $o; ?></td>   
                             <td <?php if($ford['read_status']==0) { ?> style="font-weight:bold;" <?php } ?>><?php echo date('d-m-Y',strtotime($ford['date'])); ?></td>
                             <td <?php if($ford['read_status']==0) { ?> style="font-weight:bold;" <?php } ?>><?php echo $tripfrom['0']; ?></td>
                            <td <?php if($ford['read_status']==0) { ?> style="font-weight:bold;" <?php } ?>><?php echo $ford['title']; ?></td>
                            <td <?php if($ford['read_status']==0) { ?> style="font-weight:bold;" <?php } ?>><?php echo $ford['message']; ?></td>
                            <td <?php if($ford['read_status']==0) { ?> style="font-weight:bold;" <?php } ?>>
								

								
								<?php if(getplace('place',getbooking('pickup_address',$ford['booking_id']))!='')
                                                  {
                                                  	echo getplace('place',getbooking('pickup_address',$ford['booking_id']));
                                                  }
                                                  else
                                                  {
                                                  	echo getbooking('pickup_address',$ford['booking_id']);
                                                  }
                                                   ?>
								
								</td>
                            <td <?php if($ford['read_status']==0) { ?> style="font-weight:bold;" <?php } ?>><?php if(getplace('place',getbooking('drop_address',$ford['booking_id']))!='')
                                                  {
                                                  	echo getplace('place',getbooking('drop_address',$ford['booking_id']));
                                                  }
                                                  else
                                                  {
                                                  	echo getbooking('drop_address',$ford['booking_id']);
                                                  }
                                                   ?></td>
                            <td <?php if($ford['read_status']==0) { ?> style="font-weight:bold;" <?php } ?>><?php echo $trpstatus; ?></td>
                            </tr>
                            
                            <?php $o++; } } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="8">&nbsp;</th>
                                   
                                </tr>
                            </tfoot>
                                    </table>
</form>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                        

                    </div> <!-- container-fluid -->

                </div> <!-- content -->

                
        </div>


<!-- Content Wrapper. Contains page content -->

<script type="text/javascript">
    function viewthis(a)
    {
        var did = a;
        window.location.href = '<?php echo $sitename; ?>master/' + a + '/viewuser.htm';
    }     
</script>
<?php
include ('../../require/footer.php');
?>
<script type="text/javascript">
    $('#datatable').dataTable({
          rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true ,
        "bProcessing": true,
        "bServerSide": false,
        //"scrollX": true,
        "searching": true
    });
</script>