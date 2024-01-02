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
                                    <h4 class="page-title">Users List</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Forms </a></li>
                                        <li class="breadcrumb-item active">Register Users</li>
                                    </ol>
            
                                  
                                    
                                </div>
                            </div>
                              <div class="col-md-6">
                                  <br>
                                    <a href="<?php echo $sitename.'master/userexport.htm'; ?>" style="color:blue;font-weight:bold;"> <button type="button" name="export" id="export" class="btn btn-success" style="float:left; float:right;">Export as Excel</button>    
                                               </a>    
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
                                    <th style="width:10%;">Date</th>
                                    <th style="width:10%;">Name</th>
                                    <th style="width:10%;">Email</th>
									 <th style="width:10%;">Contact No</th>
                                    <th style="width:10%;">Additional No</th>
                                    <th data-sortable="false" align="center" style="text-align: center; padding-right:0; padding-left: 0; width: 10%;">View</th>
                                    <!--<th data-sortable="false" align="center" style="text-align: center; padding-right:0; padding-left: 0; width: 10%;"><input name="check_all" id="check_all" value="1" onclick="javascript:checkall(this.form)" type="checkbox" /></th>-->
                                </tr>
                            </thead>  
                            <tbody>
                             <?php
                            $o = '1';
$ord = $db->prepare("SELECT * FROM `register` ORDER BY `id` DESC ");	
$ord->execute();
$ordnum = $ord->rowCount(); 
if($ordnum>0) { 
while ($ford = $ord->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                             <tr>
                            <td><?php echo $o; ?></td>   
                            <td><?php echo date('d-m-Y',strtotime($ford['date'])); ?></td>
                            <td><?php echo $ford['name']; ?></td>
                            <td><?php echo $ford['emailid']; ?></td>
                            <td><?php echo $ford['mobileno']; ?></td>
								 <td><?php echo $ford['additional_no']; ?></td>
                            <td><i class='fa fa-eye' onclick='javascript:viewthis(<?php echo $ford['id']; ?>);' style='cursor:pointer;'> View </i></td>
                            <!--<td><input type="checkbox"  name="chk[]" id="chk[]" value="<?php echo $ford['id']; ?>" /></td>-->
                            </tr>
                            
                            <?php $o++; } } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7">&nbsp;</th>
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
<script type="text/javascript">
  $('#datatable').dataTable({
          rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true ,
        "bProcessing": true,
        "bServerSide": false,
        //"scrollX": true,
        "searching": true,
    });
</script>