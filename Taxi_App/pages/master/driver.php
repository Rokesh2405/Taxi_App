<?php
$menu = "4";
include ('../../config/config.inc.php');
$dynamic = '1';
//$datepicker = '1';
$datatable = '1';

include ('../../require/header.php');

if($_REQUEST['driverid']!='')
{
@extract($_REQUEST);
global $db;
echo $uquery = "UPDATE `driver` SET
                    device_key='' WHERE `id`='".$_REQUEST['driverid']."' ";

$ustmt = $db->prepare($uquery);
$ustmt->execute();	
echo '<script>alert("Reset Successfully")</script>';

}

if (isset($_REQUEST['delete']) || isset($_REQUEST['delete_x'])) {
    $chk = $_REQUEST['chk'];
    $chk = implode('.', $chk);
   
    $msg = deldriver($chk);
}
if(isset($_REQUEST['tripsubmit'])){
@extract($_REQUEST);
global $db;  
$iquery = "INSERT INTO `driver_bitting` SET
                    driver='".$driver_id."',`old_amount`='".$old_amount."',`trip_type`='".$trip_type."',`trip_details`='".$trip_details."',`booking_no`='".$booking_no."',`trip_amount`='".$trip_amount."',`less_amount`='".$less_amount."' ";
$istmt = $db->prepare($iquery);
$istmt->execute();

$toamount=$old_amount-$less_amount;
$uquery = "UPDATE `driver` SET
                    bitting_amount='".$toamount."' WHERE `id`='".$driver_id."' ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();


$msg = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Updated Successfully</div>';

}

if(isset($_REQUEST['bitsubmit'])){
@extract($_REQUEST);
global $db;  

$alramount=getdriver('wallet', $driver_id);
$toamount=$alramount+$biting_amount;

$uquery = "UPDATE `driver` SET
                    wallet='".$toamount."' WHERE `id`='".$driver_id."' ";
$ustmt = $db->prepare($uquery);
$ustmt->execute();

$iquery = "INSERT INTO `wallet` SET
                    driver_id='".$driver_id."',`amount`='".$biting_amount."' ";
$istmt = $db->prepare($iquery);
$istmt->execute();

$msg = '<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Updated Successfully</div>';
}
?>
<style>
.modal-content{
        width: 100%!important;
}

.modal-dialog {
    max-width: 66%;
    margin: 1.75rem auto;
    position: relative;
    
}    
table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable td:last-child, table.table-bordered.dataTable td:last-child {
    border-right-width: 0;
    white-space: break-spaces;
}
</style>
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
            if (confirm("Please confirm you want to Delete this Driver(s)"))
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
	
	function freset(a)
	{
		 if (confirm("Please confirm you want to Reset this Driver(s)"))
            {
				 window.location.href = '<?php echo $sitename; ?>master/driver.htm?driverid='+ a;
				 //return true;
            }
            else
            {
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
    
      <div class="col-sm-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Drivers List</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Master </a></li>
                                        <li class="breadcrumb-item active">Drivers List</li>
                                    </ol>
            
                                  
                                     <div class="state-information d-none d-sm-block">
                                        <div class="state-graph">
                                           
                                           <a href="<?php echo $sitename; ?>master/adddriver.htm"><button class="btn btn-success waves-effect waves-light" type="submit">Add New</button></a>
                                        </div>
                                       
                                       
                                    </div>
                                </div>
                            </div>
                            
                           
                           
                        </div>
                        
                        
                        <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card m-b-20">
                                    <div class="card-body">
<!--
                                       <h4 class="mt-0 header-title">Default Datatable</h4>-->
<!--                                        <p class="text-muted m-b-30">DataTables has most features enabled by-->
<!--                                            default, so all you need to do to use it with your own tables is to call-->
<!--                                            the construction function: <code>$().DataTable();</code>.-->
<!--                                        </p>-->
<?php echo $msg; ?>
<form name="listform" id="listform" method="post">
<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                      
                                            <thead>
                                <tr align="center">
                                    <th>S.id</th>
                                   
                                   <th>Driver Name</th>
                                   <th>Contact Number</th>
                                     <th>Available Wallet Amount</th>
                                     <th>Wallet</th>
                                      <th>Trips</th>
                                    <th>Action</th>
                                    <th><input name="check_all" id="check_all" value="1" onclick="javascript:checkall(this.form)" type="checkbox" /></th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php
                                $order = '1';
                               $ord = pFETCH("SELECT * FROM `driver` WHERE `id`!=? ORDER BY `id` DESC ", 0);
                                while ($ford = $ord->fetch(PDO::FETCH_ASSOC)) {
                                   
                                    ?>
                            <tr>
                            <td><?php echo $order; ?></td> 
                            <td><?php echo $ford['driver_name']; ?></td>
                            <td><?php echo $ford['driver_mobileno']; ?></td>
                            <td>Rs. <?php echo $ford['wallet']; ?></td>
                            <td><a data-toggle="modal" data-target="#addbitting<?php echo $ford['id']; ?>" style='cursor:pointer;'> <button  class="btn btn-success waves-effect waves-light">Add Wallet</button> </a><br><br><a data-toggle="modal" data-target="#history<?php echo $ford['id']; ?>" style='cursor:pointer;'><button  class="btn btn-success waves-effect waves-light"> History</button></a>
                            
                              <div id="addbitting<?php echo $ford['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Add Driver Wallet Amount</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                </div>
                                                <div class="modal-body">
                                                 <form name="modalform" id="modalform" method="post">
                                                     <div class="row">
                                                     <div class="col-md-3"><strong>Amount</strong></div>  
                                                     <div class="col-md-6">
                                                    <input type="hidden" name="driver_id" value="<?php echo $ford['id']; ?>">
                                                    <input type="text" class="form-control" name="biting_amount" required="required">     
                                                     </div>
                                                     </div>
                                                     <br>
                                                     <div class="row">
                                                         <div class="col-md-3">&nbsp;</div>
                                                     <div class="col-md-3">
                                                    <input type="submit" class="btn btn-success waves-effect waves-light" name="bitsubmit" value="submit">     </div>
                                                     </div>
                                            </form>
                                                </div>
                                                                                             </div>
                                           
                                        </div>
                            </div>
                             <div id="history<?php echo $ford['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Wallet History</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                </div>
                                                <div class="modal-body">
                                                 <form name="modalform" id="modalform" method="post">
                                               <table width="100%">
                                                 <tr>
                                                 <td><strong>Date</strong></td>
                                                 <td><strong>Wallet Amount</strong></td>
                                                 </tr>  
                                                  <?php
                                $o = '1';
                               $ord1 = pFETCH("SELECT * FROM `wallet` WHERE `driver_id`=? ORDER BY `id` DESC ", $ford['id']);
                                while ($ford1 = $ord1->fetch(PDO::FETCH_ASSOC)) {
                                   
                                    ?>
                              <tr>
                                                 <td><?php echo date('d-m-Y h:i a',strtotime($ford1['date'])); ?></td>
                                                 <td>Rs.<?php echo $ford1['amount']; ?></td>
                                                 </tr>  
                                <?php } ?>
                                               </table>
                                            </form>
                                                </div>
                                                                                             </div>
                                        </div>
                            </div> 
                            </td>
                              <td><a data-toggle="modal" data-target="#addtrip<?php echo $ford['id']; ?>" style='cursor:pointer;'> <button  class="btn btn-success waves-effect waves-light">Add Trip</button> </a><br><br><a data-toggle="modal" data-target="#triphistory<?php echo $ford['id']; ?>" style='cursor:pointer;'><button  class="btn btn-success waves-effect waves-light"> History</button></a>
                            
                              <div id="addtrip<?php echo $ford['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Add Trip Details</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                </div>
                                                <div class="modal-body">
                                                 <form name="modalform" id="modalform" method="post">
                                                     <div class="row">
                                                     <div class="col-md-3"><strong>Trip type</strong></div>  
                                                     <div class="col-md-6">
                                                         <select name="trip_type" class="form-control" required="required">
                                                             <option value="">Select</option>
                                                      <option value="Single Trip">Single Trip</option>
                                                       <option value="Round Trip">Round Trip</option>
                                                         </select>
                                                        <input type="hidden" name="driver_id" value="<?php echo $ford['id']; ?>">
                                                    
                                                     </div>
                                                     </div>
                                                     <br>
                                                      <div class="row">
                                                     <div class="col-md-3"><strong>Booking No</strong></div>  
                                                     <div class="col-md-6">
                                                       <input type="text" name="booking_no" class="form-control" required="required">
                                                     </div>
                                                     </div>
                                                     <br>
                                                       <div class="row">
                                                     <div class="col-md-3"><strong>Trip Details</strong></div>  
                                                     <div class="col-md-6">
                                                      <textarea name="trip_details" class="form-control"></textarea>
                                                     </div>
                                                     </div>
                                                     <br>
                                                     <div class="row">
                                                     <div class="col-md-3"><strong>Trip Amount</strong></div>  
                                                     <div class="col-md-6">
                                                         <input type="hidden" name="old_amount" value="<?php echo $ford['bitting_amount']; ?>" class="form-control">
                                                        <input type="text" name="trip_amount" class="form-control" required="required">
                                                     </div>
                                                     </div>
                                                     <br>
                                                       <div class="row">
                                                     <div class="col-md-3"><strong>Less Amount</strong></div>  
                                                     <div class="col-md-6">
                                                        <input type="text" name="less_amount" class="form-control" required="required">
                                                     </div>
                                                     </div>
                                                     <br>
                                                     
                                                     <div class="row">
                                                         <div class="col-md-3">&nbsp;</div>
                                                     <div class="col-md-3">
                                                    <input type="submit" class="btn btn-success waves-effect waves-light" name="tripsubmit" value="submit">     </div>
                                                     </div>
                                            </form>
                                                </div>
                                                                                             </div>
                                           
                                        </div>
                            </div>
                             <div id="triphistory<?php echo $ford['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                    <h4 class="modal-title">Trip History</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                </div>
                                                <div class="modal-body">
                                               <form name="modalform" id="modalform" method="post">
                                               <table width="100%">
                                                 <tr>
                                                 <td><strong>Date</strong></td>
                                                 <td><strong>Booking No</strong></td>
                                                 <td><strong>Trip Type</strong></td><td><strong>Trip Amount</strong></td><td><strong>Less Amount</strong></td>
                                                 <td style="witdh:120px;"><strong>Trip Details</strong></td>
                                                 </tr>  
                                                  <?php
                                $o = '1';
                               $ord2 = pFETCH("SELECT * FROM `driver_bitting` WHERE `driver`=? ORDER BY `id` DESC ", $ford['id']);
                                while ($ford2 = $ord2->fetch(PDO::FETCH_ASSOC)) {
                                   
                                    ?>
                              <tr>
                                                 <td><?php echo date('d-m-Y',strtotime($ford2['date'])); ?></td>
                                                 <td><?php echo $ford2['booking_no']; ?></td>
                                                 <td><?php echo $ford2['trip_type']; ?></td>
                                                   <td><?php echo $ford2['trip_amount']; ?></td>
                                                     <td><?php echo $ford2['less_amount']; ?></td>
                                                     <td><?php echo $ford2['trip_details']; ?></td>
                                                 </tr>  
                                <?php } ?>
                                               </table>
                                            </form>
                                    </div>
                                    </div>
                                </div>
                            </div> 
                            </td>
                            <td>
								<button class="btn btn-success waves-effect waves-light" onclick="freset('<?php echo $ford['id']; ?>');" name="reset" id="reset" type="button">RESET</button>
								<i class='fa fa-edit' onclick='javascript:editthis(<?php echo $ford['id']; ?>);' style='cursor:pointer;'> Edit </i></td>
                            <td><input type="checkbox"  name="chk[]" id="chk[]" value="<?php echo $ford['id']; ?>" /></td>

                            </tr>
                            <?php $order++; } ?>

                            </tbody>
                            <tfoot>
                               <!--  -->
                            </tfoot>
                            <tr>
                                    <th colspan="6">&nbsp;</th>
                                    <th align="center"><button type="submit" class="btn btn-danger" name="delete" id="delete" style="width:100%; position: relative;left: 67px;" value="Delete" onclick="return checkdelete('chk[]');"> DELETE </button></th>
                                </tr> 
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
    function editthis(a)
    {
        var did = a;
        window.location.href = '<?php echo $sitename; ?>master/' + a + '/editdriver.htm';
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
         "oPaginate":true,
        "scrollX": true,
        "searching": true,
    });
</script>