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
   
    $msg = delpackage($chk);
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
            if (confirm("Please confirm you want to Delete this Package(s)"))
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
    
      <div class="col-sm-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">Pending Trips List</h4>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $_SESSION['sitename']; ?></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Master </a></li>
                                        <li class="breadcrumb-item active">>Pending Trips</li>
                                    </ol>
            
                                  
                                     <div class="state-information d-none d-sm-block">
                                        <!--<div class="state-graph">-->
                                           
                                        <!--   <a href="<?php echo $sitename; ?>master/addpackage.htm"><button class="btn btn-success waves-effect waves-light" type="submit">Add New</button></a>-->
                                        <!--</div>-->
                                       
                                       
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
<!--                                        <h4 class="mt-0 header-title">Default Datatable</h4>-->
<!--                                        <p class="text-muted m-b-30">DataTables has most features enabled by-->
<!--                                            default, so all you need to do to use it with your own tables is to call-->
<!--                                            the construction function: <code>$().DataTable();</code>.-->
<!--                                        </p>-->
<?php echo $msg; ?>
<form name="listform" id="listform" method="post">
<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                      
                                            <thead>
                                <tr align="center">
                                    <th style="width:5%;">S.id</th>
                                    <th style="width:20%;">Date</th>
                                    <th style="width:20%;">Customer Name</th>
                                    <th style="width:20%;">Pickup Address</th>
                                     <th style="width:20%;">Drop Address</th>
                                    <!--<th data-sortable="false" align="center" style="text-align: center; padding-right:0; padding-left: 0; width: 10%;">View</th>-->
                                   
                                </tr>
                            </thead>                          
                            <tfoot>
                                <tr>
                                  <th colspan="5">&nbsp;</th>
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
        window.location.href = '<?php echo $sitename; ?>master/' + a + '/viewpending_trip.htm';
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
        "sAjaxSource": "<?php echo $sitename; ?>pages/dataajax/gettablevalues.php?types=pendingtriptable"
    });
</script>