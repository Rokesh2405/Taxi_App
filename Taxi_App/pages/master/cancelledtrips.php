<?php
$menu = "4";
include ('../../config/config.inc.php');
$dynamic = '1';
//$datepicker = '1';
$datatable = '1';

include ('../../require/header.php');
include_once 'notification.php';
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
    .modal-content{
    width: 1004px;margin-left: -255px;    
    }
    
</style>
       
        <div class="content-page">
        
  
  <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="page-title-box">
                                    <h4 class="page-title">Cancelled Booking List</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Master </a></li>
                                        <li class="breadcrumb-item active">Cancelled Booking List</li>
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
                                    <th style="width:15%;">Cancelled Date</th>
                                     <th style="width:15%;">Trip Date</th>
                                    <th style="width:10%;">Trip Time</th>
                                    <th style="width:10%;">Pickup</th>
                                      <th style="width:10%;">Drop</th>
                                       <th style="width:10%;">Cab Name</th>
                                    <th data-sortable="false" align="center" style="text-align: center; padding-right:0; padding-left: 0; width: 10%;">View Details</th>
                              
                                </tr>
                            </thead> 
                            <tbody>
                             <?php
                                $o = '1';
$ord = $db->prepare("SELECT * FROM `cancelled_trips` ORDER BY `id` DESC ");	
$ord->execute();
$ordnum = $ord->rowCount(); 
if($ordnum>0) { 
                                while ($ford = $ord->fetch(PDO::FETCH_ASSOC)) {
                                   
                                    ?>
                                    <tr>
                                    <td><?php echo $o; ?></td> 
                                    <td><?php echo date('d-M-Y',strtotime($ford['date'])); ?></td>   
                                      <td><?php echo date('d-M-Y',strtotime($ford['trip_date'])); ?></td>   
                                       <td><?php echo date('h:i a',strtotime($ford['trip_time'])); ?></td> 
                                          <td><?php if(getplace('place',$ford['pickup_address'])!='')
                                                  {
                                                    echo getplace('place',$ford['pickup_address']);
                                                  }
                                                  else
                                                  {
                                                    echo $ford['pickup_address'];
                                                  }
                                                   ?></td> 
<td><?php if(getplace('place',$ford['drop_address'])!='')
                                                  {
                                                    echo getplace('place',$ford['drop_address']);
                                                  }
                                                  else
                                                  {
                                                    echo $ford['drop_address'];
                                                  }
                                                   ?></td> 
<td><?php echo getcar('name',$ford['car_id']); ?></td> 


                                           <td><a data-toggle="modal" data-target="#book<?php echo $ford['id']; ?>" style="color:#62A3FF;cursor:pointer;">Booking Details</a>
                                            <div id="book<?php echo $ford['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <form name="modalform" id="modalform" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Booking Details</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                                </div>
                                                <div class="modal-body" style="line-height:10px;">
                                                   <div class="row">
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                    <label>Username</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                        <input type="hidden" name="booking_id" class="form-control" value="<?php echo $ford['id']; ?>">
                                                  <?php echo getregisterform('name',$ford['register_id']); ?>   
                                                    </div> 
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                    <label>Contact Number</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                  <?php echo getregisterform('mobileno',$ford['register_id']); ?>   
                                                    </div> 
                                                   </div>
                                                 
                                                   <div class="row">
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                    <label>Car Name</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                  <?php echo getcar('name',$ford['car_id']); ?>   
                                                    </div> 
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                    <label>Trip Type</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                  <?php echo $ford['triptype']; ?>   
                                                    </div> 
                                                   </div>
                                                   
                                                  
                                                   <div class="row">
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                    <label>Pickup Address</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left" style="max-width: 23%;">
                                                  <p><?php if(getplace('place',$ford['pickup_address'])!='')
                                                  {
                                                    echo getplace('place',$ford['pickup_address']);
                                                  }
                                                  else
                                                  {
                                                    echo $ford['pickup_address'];
                                                  }
                                                   ?>   </p>
                                                    </div> 
                                                   
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                    <label>Drop Address</label>    
                                                    </div> 
                                                    <div class="col-md-3"  align="left" style="max-width: 23%;">
                                                   <p><?php if(getplace('place',$ford['drop_address'])!='')
                                                  {
                                                    echo getplace('place',$ford['drop_address']);
                                                  }
                                                  else
                                                  {
                                                    echo $ford['drop_address'];
                                                  }
                                                   ?></p>
                                                    </div> 
                                                   </div>
                                                  
                                                   <div class="row">
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                    <label>Trip Date</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                  <?php echo date('d-M-Y',strtotime($ford['trip_date'])); ?>   
                                                    </div> 
                                                      <div class="col-md-3" align="left" style="max-width: 23%;">
                                                    <label>Cancel Reason</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left" style="max-width: 23%;">
                                                  <?php echo $ford['cancel_reason']; ?>   
                                                    </div> 
                                                   </div>
                                                   <br>
                                                  <div class="row">
                                                      <div class="col-md-3" align="left" align="left" style="max-width: 23%;">
                                                    <label>Customer Paid <br><br><br> Amount Rs.</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left" align="left" style="max-width: 23%;">
                                                <?php echo $ford['customer_paid_booking_amount']; ?>   
                                                    </div> 
                                                     <div class="col-md-3" align="left" align="left" style="max-width: 23%;">
                                                    <label>Customer Booking <br><br><br>Amount Rs.</label>    
                                                    </div> 
                                                    <div class="col-md-3" align="left" align="left" style="max-width: 23%;">
                                                <?php echo $ford['customer_booking_amount']; ?>   
                                                    </div> 
                                                  </div>
                                                </div>
                                            
                                            </div>
                                        </form>
                                        </div>
                                    </div><!-- /.modal -->
                                       </td>
                                        
                                    </tr>
                                    <?php $o++; } } else { ?>
                                     <tr>
                                    <td colspan="8" align="center">No Records Found</td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="8">&nbsp;</th>
                                    <!--<th align="center"><button type="submit" class="btn btn-danger" name="delete" id="delete" style="width:100%;" value="Delete" onclick="return checkdelete('chk[]');"> DELETE </button></th>-->
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