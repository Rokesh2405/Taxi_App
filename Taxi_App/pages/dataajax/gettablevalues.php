<?php
include ('../../config/config.inc.php');

// ini_set('display_errors','1');
// error_reporting(E_ALL);
function mres($value) {
    $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
    $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
    return str_replace($search, $replace, $value);
}
if ($_REQUEST['types'] == 'conftriptable') {
    $aColumns = array('id', 'trip_date','register_id','pickup_address','drop_address');
    $sIndexColumn = "id";
    //$editpage = ($_REQUEST['db_table_for'] == 'live') ? "edit" : "editstati";
    $sTable = "booking";
}
if ($_REQUEST['types'] == 'pendingtriptable') {
    $aColumns = array('id', 'trip_date','register_id','pickup_address','drop_address');
    $sIndexColumn = "id";
    //$editpage = ($_REQUEST['db_table_for'] == 'live') ? "edit" : "editstati";
    $sTable = "booking";
}
if ($_REQUEST['types'] == 'pricelisttable') {
    $aColumns = array('id', 'car_type','trip_type','per_km');
    $sIndexColumn = "id";
    //$editpage = ($_REQUEST['db_table_for'] == 'live') ? "edit" : "editstati";
    $sTable = "price_list";
}
if ($_REQUEST['types'] == 'adminnotificationtable') {
    $aColumns = array('id', 'date','title','image','message');
    $sIndexColumn = "id";
    //$editpage = ($_REQUEST['db_table_for'] == 'live') ? "edit" : "editstati";
    $sTable = "admin_notification";
}
if ($_REQUEST['types'] == 'ridingtable') {
    $aColumns = array('id', 'trip_date','register_id','driver_id','driver_charge');
    $sIndexColumn = "id";
    //$editpage = ($_REQUEST['db_table_for'] == 'live') ? "edit" : "editstati";
    $sTable = "booking";
}

if ($_REQUEST['types'] == 'drivertable') {
    $aColumns = array('id', 'driver_name','driver_mobileno','bitting_amout');
    $sIndexColumn = "id";
    //$editpage = ($_REQUEST['db_table_for'] == 'live') ? "edit" : "editstati";
    $sTable = "driver";
}

if ($_REQUEST['types'] == 'typetable') {
    $aColumns = array('id', 'name','status');
    $sIndexColumn = "id";
    //$editpage = ($_REQUEST['db_table_for'] == 'live') ? "edit" : "editstati";
    $sTable = "types";
}

if ($_REQUEST['types'] == 'packagetable') {
    $aColumns = array('id', 'location','status');
    $sIndexColumn = "id";
    //$editpage = ($_REQUEST['db_table_for'] == 'live') ? "edit" : "editstati";
    $sTable = "packages";
}
if ($_REQUEST['types'] == 'carstable') {
    $aColumns = array('id', 'type','name','status');
    $sIndexColumn = "id";
    //$editpage = ($_REQUEST['db_table_for'] == 'live') ? "edit" : "editstati";
    $sTable = "cars";
}
if ($_REQUEST['types'] == 'registertable') {
    $aColumns = array('id', 'name','emailid','mobileno','status');
    $sIndexColumn = "id";
    //$editpage = ($_REQUEST['db_table_for'] == 'live') ? "edit" : "editstati";
    $sTable = "register";
}


/* Declaration table name start here */



$aColumns1 = $aColumns;

function fatal_error($sErrorMessage = '') {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
    die($sErrorMessage);
}

$sLimit = "";

if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
    $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
}


    $sOrder = "ORDER BY `$sIndexColumn` DESC";


if (isset($_GET['iSortCol_0'])) {
    $sOrder = "ORDER BY  ";
    if (in_array("order", $aColumns)) {
        $sOrder .= "`order` asc, ";
    } else if (in_array("Order", $aColumns)) {
        $sOrder .= "`Order` asc, ";
    }
    for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
        if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
            $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
        }
        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY ") {
            $sOrder = " ";
        }
    }
}

$sWhere = "";


if ($sWhere != '') {
     $sWhere = "WHERE `userid`='".$_SESSION['ALOID']."' AND `$sIndexColumn`!='' $sWhere";    
}
if($_REQUEST['postid']!='') { 
    $sWhere = "WHERE `rescue_id`='".$_REQUEST['postid']."' AND `$sIndexColumn`!='' $sWhere";    
}
if ($_REQUEST['types'] == 'ridingtable') {
    $sWhere = "WHERE `completed_status`='1' $sWhere";    
}
if ($_REQUEST['types'] == 'pendingtriptable') {
    $sWhere = "WHERE `booking_status`='0' AND `driver_charge` IS NULL $sWhere";    
}
if ($_REQUEST['types'] == 'conftriptable') {
    $sWhere = "WHERE `booking_status`='1' $sWhere";    
}
$sWhere1=array();
if($_REQUEST['users']!='0') { 
    $sWhere1[] = "`registerid`='".$_REQUEST['users']."' ";    
}
if($_REQUEST['module']!='0') { 
    $sWhere1[] = "`type`='".$_REQUEST['module']."'";    
}
if($_REQUEST['animal_type']!='0') { 
    $sWhere1[] = "`animaltype`='".$_REQUEST['animal_type']."'";    
}

if(!empty($sWhere1) && ($_REQUEST['users']!='' || $_REQUEST['module']!='' && $_REQUEST['animal_type']!='')) {
    $impwhere=implode(' AND ',$sWhere1);
     $sWhere = "WHERE ".$impwhere." AND `$sIndexColumn`!='' $sWhere";    
}
echo $sQuery = "SELECT SQL_CALC_FOUND_ROWS `" . str_replace(",", "`,`", implode(",", $aColumns)) . "` FROM $sTable $sWhere $sOrder $sLimit ";


$rResult = $db->prepare($sQuery);
$rResult->execute();


$sQuery = "SELECT FOUND_ROWS()";

$rResultFilterTotal = $db->prepare($sQuery);
$rResultFilterTotal->execute();

$aResultFilterTotal = $rResultFilterTotal->fetch();
$iFilteredTotal = $aResultFilterTotal[0];

$sQuery = "SELECT COUNT(" . $sIndexColumn . ") FROM $sTable";
$rResultTotal = $db->prepare($sQuery);
$rResultTotal->execute();

$aResultTotal = $rResultTotal->fetch();
$iTotal = $aResultTotal[0];

$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
);

$ij = 1;
$k = $_GET['iDisplayStart'];

while ($aRow = $rResult->fetch(PDO::FETCH_ASSOC)) {
    $k++;
    $row = array();
    $row1 = '';
    for ($i = 0; $i < count($aColumns1); $i++) {
        if ($_REQUEST['types'] == 'typetable') {
            if ($aColumns1[$i] == $sIndexColumn) {
                $row[] = $k;
            } 
            elseif ($aColumns1[$i] == 'status') {
                $row[] = $aRow[$aColumns1[$i]] ? "Active" : "Inactive";
            }else {
                $row[] = $aRow[$aColumns1[$i]];
            }
        }elseif ($_REQUEST['types'] == 'ridingtable' || $_REQUEST['types'] == 'pendingtriptable' || $_REQUEST['types'] == 'conftriptable') {
            if ($aColumns1[$i] == $sIndexColumn) {
                $row[] = $k;
            } 
             elseif ($aColumns1[$i] == 'trip_date') {
                $row[] = date('d-m-Y',strtotime($aRow[$aColumns1[$i]]));
            }
            elseif ($aColumns1[$i] == 'register_id') {
                $row[] = getregisterform('name',$aRow[$aColumns1[$i]]);
            }
            elseif ($aColumns1[$i] == 'driver_id') {
                $row[] = getdriver('driver_name',$aRow[$aColumns1[$i]]);
            }else {
                $row[] = $aRow[$aColumns1[$i]];
            }
        }elseif ($_REQUEST['types'] == 'drivertable') {
            if ($aColumns1[$i] == $sIndexColumn) {
                $row[] = $k;
            } 
            elseif ($aColumns1[$i] == 'car_id') {
                $row[] = getcar('name',$aRow[$aColumns1[$i]]);
            }
            elseif ($aColumns1[$i] == 'status') {
                $row[] = $aRow[$aColumns1[$i]] ? "Active" : "Inactive";
            }else {
                $row[] = $aRow[$aColumns1[$i]];
            }
        }
        elseif ($_REQUEST['types'] == 'adminnotificationtable') {
            if ($aColumns1[$i] == $sIndexColumn) {
                $row[] = $k;
            } 
             elseif ($aColumns1[$i] == 'image') {
                 $imgpath=$sitename.'images/notification/'.$aRow[$aColumns1[$i]];
                $row[] = '<img src="'.$imgpath.'" width="120">';
            }
            elseif ($aColumns1[$i] == 'status') {
                $row[] = $aRow[$aColumns1[$i]] ? "Active" : "Inactive";
            }else {
                $row[] = $aRow[$aColumns1[$i]];
            }
        }
        elseif ($_REQUEST['types'] == 'carstable') {
            if ($aColumns1[$i] == $sIndexColumn) {
                $row[] = $k;
            } 
             elseif ($aColumns1[$i] == 'type') {
                $row[] = gettypes('name',$aRow[$aColumns1[$i]]);
            }
            elseif ($aColumns1[$i] == 'status') {
                $row[] = $aRow[$aColumns1[$i]] ? "Active" : "Inactive";
            }else {
                $row[] = $aRow[$aColumns1[$i]];
            }
        }elseif ($_REQUEST['types'] == 'registertable') {
            if ($aColumns1[$i] == $sIndexColumn) {
                $row[] = $k;
            } 
            elseif ($aColumns1[$i] == 'status') {
                $row[] = $aRow[$aColumns1[$i]] ? "Active" : "Inactive";
            }else {
                $row[] = $aRow[$aColumns1[$i]];
            }
        }elseif ($_REQUEST['types'] == 'packagetable') {
            if ($aColumns1[$i] == $sIndexColumn) {
                $row[] = $k;
            } 
            elseif ($aColumns1[$i] == 'status') {
                $row[] = $aRow[$aColumns1[$i]] ? "Active" : "Inactive";
            }else {
                $row[] = $aRow[$aColumns1[$i]];
            }
        }else {
            if ($aColumns1[$i] == $sIndexColumn) {
                $row[] = $k;
            } elseif ($aColumns1[$i] == 'Status') {
                $row[] = $aRow[$aColumns1[$i]] ? "Active" : "Inactive";
            } elseif ($aColumns1[$i] == 'status') {
                $row[] = $aRow[$aColumns1[$i]] ? "Active" : "Inactive";
            } else {
                $row[] = $aRow[$aColumns1[$i]];
            }
        }
    }

    /* Edit page  change start here */
  
    if (($_REQUEST['types'] == 'registertable') || ($_REQUEST['types'] == 'posttable')) {
        $row[] = "<i class='fa fa-eye' onclick='javascript:viewthis(" . $aRow[$sIndexColumn] . ");' style='cursor:pointer;'> </i>";
    }
    
    
    elseif(($_REQUEST['types'] == 'chattable'))
    {
         $row[] = "<i class='fa fa-edit' onclick='javascript:editthis(" . $aRow[$sIndexColumn] . ");' style='cursor:pointer;'> Edit </i>";
      $row[] = "<i class='fa fa-eye' onclick='javascript:viewthis(" . $aRow[$sIndexColumn] . ");' style='cursor:pointer;'> </i>";
      
    }  elseif(($_REQUEST['types'] == 'drivertable'))
    {
         $row[] = "<button class='btn btn-success waves-effect waves-light' type='button' onclick='javascript:addbitting(" . $aRow[$sIndexColumn] . ");' style='cursor:pointer;'> Add Bitting </button>&nbsp;&nbsp;<button class='btn btn-success waves-effect waves-light' type='button' onclick='javascript:bittinghistory(" . $aRow[$sIndexColumn] . ");' style='cursor:pointer;'> History</button>";
         $row[] = "<i class='fa fa-edit' onclick='javascript:editthis(" . $aRow[$sIndexColumn] . ");' style='cursor:pointer;'> Edit </i>";
      
      
    }
    else {
        if($_REQUEST['types'] != 'ordertable' && $_REQUEST['types'] != 'ridingtable' && $_REQUEST['types'] != 'pendingtriptable' &&  $_REQUEST['types'] != 'filterordertable' && $_REQUEST['types'] != 'cancelordertable' && $_REQUEST['types'] != 'commenttable' && $_REQUEST['types'] != 'adminnotificationtable')
        {
        $row[] = "<i class='fa fa-edit' onclick='javascript:editthis(" . $aRow[$sIndexColumn] . ");' style='cursor:pointer;'> Edit </i>";
        }
        
    }

    if ($_REQUEST['types'] == 'ordertable' || $_REQUEST['types'] == 'filterordertable' || $_REQUEST['types'] == 'cancelordertable' || $_REQUEST['types'] == 'ridingtable' || $_REQUEST['types'] == 'pendingtriptable') {
        $row[] = "<i class='fa fa-eye' onclick='javascript:viewthis(" . $aRow[$sIndexColumn] . ");' style='cursor:pointer;'> </i>";
    } else if ($_REQUEST['types'] == 'customertable') {
        $row[] = "<i class='fa fa-eye' onclick='javascript:editthis(" . $aRow[$sIndexColumn] . ");' style='cursor:pointer;'></i>";
    }
    if($_REQUEST['types'] != 'adminnotificationtable') {
    $row[] = '<input type="checkbox"  name="chk[]" id="chk[]" value="' . $aRow[$sIndexColumn] . '" />';

}

    $output['aaData'][] = $row;
    $ij++;
}

echo json_encode($output);
?>
 
