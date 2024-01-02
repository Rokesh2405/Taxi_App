<?php
$report = 1;
include 'config.inc.php';
global $db;

// error_reporting(1);

// ini_set('display_errors','1');
// error_reporting(E_ALL);

if (($_REQUEST['image'] != '') && ($_REQUEST['id'] != '') && ($_REQUEST['table'] != '') && ($_REQUEST['path'] != '') && ($_REQUEST['images'] != '') && ($_REQUEST['pid'] != '')) {

    // if ($_REQUEST['table'] == 'product') {
    //     $pimg = explode(",", getproduct('image', $_REQUEST['id']));

    //     if (($key = array_search($_REQUEST['image'], $pimg))) {
    //         unset($pimg[$key]);
    //     }
    //     unlink("../../images/product/thump/" . $_REQUEST['image']);
    //     unlink("../../images/product/image/" . $_REQUEST['image']);
    //     unlink("../../images/product/small/" . $_REQUEST['image']);
    //     unlink("../../images/product/big/" . $_REQUEST['image']);
    //     unlink($_REQUEST['path'] . $_REQUEST['image']);

    //     $uimg = '';
    //     foreach ($pimg as $gimage) {
    //         if ($gimage != $_REQUEST['image']) {
    //             $uimg .= $gimage . ',';
    //         } else {
    //             $uimg .= '';
    //         }
    //     }
    //     $uimg = substr($uimg, 0, -1);

    //     $updateimg = $db->prepare("UPDATE `" . $_REQUEST['table'] . "` SET `" . $_REQUEST['images'] . "`=? WHERE `" . $_REQUEST['pid'] . "`=?");
    //     $updateimg->execute(array($uimg, $_REQUEST['id']));
    // } 
    
    if ($_REQUEST['table'] == 'gallery') {
        unlink($_REQUEST['path'].'thump/'.$_REQUEST['image']);
        unlink($_REQUEST['path'] . $_REQUEST['image']);
        $updateimg = $db->prepare("UPDATE `" . $_REQUEST['table'] . "` SET `" . $_REQUEST['images'] . "`=? WHERE `" . $_REQUEST['pid'] . "`=?");
        $updateimg->execute(array('', $_REQUEST['id']));
    } elseif ($_REQUEST['table'] == 'services') {
        unlink($_REQUEST['path'].'thump/'.$_REQUEST['image']);
        unlink($_REQUEST['path'] . $_REQUEST['image']);
        $updateimg = $db->prepare("UPDATE `" . $_REQUEST['table'] . "` SET `" . $_REQUEST['images'] . "`=? WHERE `" . $_REQUEST['pid'] . "`=?");
        $updateimg->execute(array('', $_REQUEST['id']));
    } elseif ($_REQUEST['table'] == 'ourpartners') {
        unlink($_REQUEST['path'].'thump/'.$_REQUEST['image']);
        unlink($_REQUEST['path'] . $_REQUEST['image']);
        $updateimg = $db->prepare("UPDATE `" . $_REQUEST['table'] . "` SET `" . $_REQUEST['images'] . "`=? WHERE `" . $_REQUEST['pid'] . "`=?");
        $updateimg->execute(array('', $_REQUEST['id']));
    } elseif ($_REQUEST['table'] == 'blogcategory') {
        unlink($_REQUEST['path'].'thump/'.$_REQUEST['image']);
        unlink($_REQUEST['path'] . $_REQUEST['image']);
        $updateimg = $db->prepare("UPDATE `" . $_REQUEST['table'] . "` SET `" . $_REQUEST['images'] . "`=? WHERE `" . $_REQUEST['pid'] . "`=?");
        $updateimg->execute(array('', $_REQUEST['id']));
    } elseif ($_REQUEST['table'] == 'blog') {
        unlink($_REQUEST['path'].'thump/'.$_REQUEST['image']);
        unlink($_REQUEST['path'] . $_REQUEST['image']);
        $updateimg = $db->prepare("UPDATE `" . $_REQUEST['table'] . "` SET `" . $_REQUEST['images'] . "`=? WHERE `" . $_REQUEST['pid'] . "`=?");
        $updateimg->execute(array('', $_REQUEST['id']));
    } else {
        unlink($_REQUEST['path'] . $_REQUEST['image']);
        
        $updateimg = $db->prepare("UPDATE `" . $_REQUEST['table'] . "` SET `" . $_REQUEST['images'] . "`=? WHERE `" . $_REQUEST['pid'] . "`=?");
        $updateimg->execute(array('', $_REQUEST['id']));
    }
    // $htry = $db->prepare("INSERT INTO `history` (`page`,`pageid`,`action`,`userid`,`ip`,`actionid`,`info`) VALUES (?,?,?,?,?,?,?)");
    // $htry->execute(array($_REQUEST['table'], 9, 'Delete', $_SESSION['UID'], $_SERVER['REMOTE_ADDR'], $_REQUEST['id'], 'Image Deletion'));

    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>Successfully Deleted</div>';
}

if ($_REQUEST['delimag'] != '') {
    $sel1 = $db->prepare("SELECT * FROM `imageup` WHERE `aiid`=?");
    $sel1->execute(array($_REQUEST['delimag']));
    $sel = $sel1->fetch();
    unlink("../../images/imageup/" . $sel['image']);

    $get = $db->prepare("DELETE FROM  `imageup` WHERE `aiid` =? ");
    $get->execute(array(trim($_REQUEST['delimag'])));

    //DB("DELETE FROM `addimages` WHERE `aid`='".$sel['aid']."'");

    echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-close"></i>Succesfully Deleted, Image will be deleted in few seconds</h4></div>';

    echo '<meta http-equiv="refresh" content="3;url=' . $sitename . 'others/viewimage.htm/' . '">';
    exit;
}


if ($_REQUEST['newssubmit'] != '') {
    @extract($_REQUEST);
    $ip = $_SERVER['REMOTE_ADDR'];

    $msgg = addsubscribe($_REQUEST['newssubmit'], $ip);
    echo $msgg;
}

if ($_REQUEST['changestate'] != '') {
    @extract($_REQUEST);
    ?>
    <select name="shipcity" id="shipcity" required="required" class="form-control" >
        <option value="">Select City</option>
        <?php
        $cst = pFETCH("SELECT * FROM `shipping_city` WHERE `status`=? AND `ship_state`=?", '1', $changestate);
        while ($fcst = $cst->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <option value="<?php echo $fcst['scid']; ?>"><?php echo $fcst['shipping_city']; ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}

if ($_POST['attribute']) {
    $nbays_attribute = $db->prepare("SELECT * FROM `subcategory` WHERE `scid`=?");
    $nbays_attribute->execute(array($_POST['attribute']));
    $checkcat = $nbays_attribute->fetch(PDO::FETCH_ASSOC);
    // $checkcat = DB_QUERY("SELECT * FROM `subcategory` WHERE `scid`='" . $_POST['attribute'] . "'");
    if ($checkcat['attributeset'] != '') {
        ?>
        <div class="panel" style="width: 100%; margin-bottom: 10px;  alignment-baseline: central">
            <div class="panel-heading" style="background-color:#f95483">
                <div class="panel-title" style="font-size:25px;color:white;font-weight:bold;">
                    Specification(s)
                </div></div>
        </div>
        <div class="panel panel-info" style="width: 100%;  alignment-baseline: central">
            <div class="panel-body color">
                <div class="row">
                    <?php
                    $serve = explode(',', $checkcat['attributeset']);
                    if (count($serve) > 0) {
                        $y = 0;
                        foreach ($serve as $i => $servicesname) {
                            $nbays_attribute_type = $db->prepare("SELECT * FROM `attributetype` where `attribute`=?");
                            $nbays_attribute_type->execute(array($servicesname));
                            //  $attrtype = DB("SELECT * FROM `attributetype` where `attribute`='" . $servicesname . "'");
                            while ($rescheck = $nbays_attribute_type->fetch(PDO::FETCH_ASSOC)) {
                                $attvalue = explode(',', $rescheck['value']);
                                $y++;
                                ?>
                                <div class="col-md-6">
                                    <select  id="attvalue" name="attvalue[]">
                                        <option value="">Select <?php echo $rescheck['attributtitle']; ?></option>
                                        <?php
                                        $attrval = explode(',', $rescheck['value']);
                                        foreach ($attrval as $i => $j) {
                                            ?>
                                            <option value="<?php echo $rescheck['attributtitle'] . '###' . $j; ?>"><?php echo $j; ?></option>  
                                        <?php } ?>     
                                    </select>
                                </div>
                                <?php
                                if ($y == '2') {
                                    echo '</div><br /><div class="row">';
                                    $y = '0';
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
}
if ($_POST['attribute1']) {
    $nbays_attribute1 = $db->prepare("SELECT * FROM `subcategory` WHERE `scid`=?");
    $nbays_attribute1->execute(array($_POST['attribute1']));
    $checkcat = $nbays_attribute1->fetch(PDO::FETCH_ASSOC);
    // $checkcat = DB_QUERY("SELECT * FROM `subcategory` WHERE `scid`='" . $_POST['attribute1'] . "'");
    if ($checkcat['attributeset'] != '') {
        ?> <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Specification(s)</div>
            </div>
            <div class="panel-body">

                <div class="row">
                    <?php
                    $serve = explode(',', $checkcat['attributeset']);
                    if (count($serve) > 0) {
                        $y = 0;
                        foreach ($serve as $i => $servicesname) {
                            $nbays_attribute1_type = $db->prepare("SELECT * FROM `attributetype` where `attribute`=?");
                            $nbays_attribute1_type->execute(array($servicesname));
                            //  $attrtype = DB("SELECT * FROM `attributetype` where `attribute`='" . $servicesname . "'");
                            while ($rescheck = $nbays_attribute1_type->fetch(PDO::FETCH_ASSOC)) {
                                $attvalue = explode(',', $rescheck['value']);
                                $y++;
                                ?>
                                <div class="col-md-6">
                                    <select class="form-control" id="attvalue" name="attvalue[]">
                                        <option value="">Select <?php echo $rescheck['attributtitle']; ?></option>
                                        <?php
                                        $attrval = explode(',', $rescheck['value']);
                                        foreach ($attrval as $i => $j) {
                                            ?>
                                            <option value="<?php echo $rescheck['attributtitle'] . '###' . $j; ?>"><?php echo $j; ?></option>  
                                        <?php } ?>     
                                    </select>
                                </div>
                                <?php
                                if ($y == '2') {
                                    echo '</div><br /><div class="row">';
                                    $y = '0';
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
}
if ($_POST['cur'] != '') {
    $_SESSION['FRONT_WEB_CURRENCY'] = $_POST['cur'];
    echo json_encode(array('suc'));
    exit;
}
if (($_REQUEST['orderstatus'] != '') && ($_REQUEST['orderid'] != '')) {
    $norder = FETCH_all("SELECT * FROM `norder` WHERE `oid`=?", $_REQUEST['orderid']);

$general = FETCH_all("SELECT * FROM `manageprofile` WHERE `pid` = ?", '1');

    $from = $general['recoveryemail'];
    
    pFETCH("UPDATE `norder` SET `order_status`=? WHERE `oid`=?",$_REQUEST['orderstatus'],$_REQUEST['orderid']);

    if ($_REQUEST['orderstatus'] == '0') {
        $ostatus = 'Awaiting for Payment';
    } elseif ($_REQUEST['orderstatus'] == '1') {
        $ostatus = 'Awaiting Fulfillment';
    } elseif ($_REQUEST['orderstatus'] == '2') {
        $ostatus = 'Completed';
    } elseif ($_REQUEST['orderstatus'] == '3') {
        $ostatus = 'Cancelled';
    } elseif ($_REQUEST['orderstatus'] == '4') {
        $ostatus = 'Declined';
    } elseif ($_REQUEST['orderstatus'] == '5') {
        $ostatus = 'Refunded';
    } elseif ($_REQUEST['orderstatus'] == '6') {
        $ostatus = 'Partially Refunded';
    }

    if ($norder['guest'] == '1') {
        $name = getguest1('email', $norder['CusID']) . ' ( Guest )';
        $cemail = getguest1('email', $norder['CusID']);
    } else {
        $name = getcustomer1('FirstName', $norder['CusID']);
        $cemail = getcustomer1('E-mail', $norder['CusID']);
    }
    
    $to = $cemail;
    
    $subject='Your Everest Gifts Order Has Been Updated '.$norder['order_id'];

    $mailcontent = '<table style="width:600px; font-family:arial; font-size:13px;" cellpadding="0" cellspacing="0">
                <tr>
                    <td><h2 style="color:#cc6600; border-bottom:1px solid #cc6600;">Order Status Changed</h2></td>
                </tr>
                <tr>
                    <td><p><b>Hi ' . $name . '</b>,</p><p>An order you recently placed on our website has had its status changed.</p><p>The status of order '.$norder['order_id'].' is now ' . $ostatus . '</p></td>
                </tr>
                <tr>
                    <td>
                        <table style="width:100%; border-bottom:1px solid #cc6600; padding:2%; font-size:13px;" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="60%;" align="left">
                                    <h4>Order Number : ' . $norder['order_id'] . '</h4>
                                    <p>Order Date : ' . date('d-M-Y H:i:s A', strtotime($norder['datetime'])) . '</p>
                                    <p>Payment Method : ' . $norder['payment_mode'] . '</p>
                                    <h4>Currency : ' . $norder['currency'] . '</h4>
                                </td>
                                <td width="40%;">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="width:100%; border-bottom:1px solid #cc6600; padding:2%; font-size:13px;" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="50%;">
                                    <a href="http://www.everestgifts.com.au/" target="_blank">Everest Gifts</a>
                                </td>
                                <td width="50%;" align="right"></td>
                        </table>
                    </td>
                </tr>
            </table>';

    if ($_REQUEST['sendmail'] == '1')
    {
         //Customer Email
        sendoldmail($subject, $mailcontent, $from, $to);
    }
    echo '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><i class="icon fa fa-check"></i> Order Updated Successfully!!! </div>';
}
?>
