 <footer class="footer">
      <!-- partial -->
      <?php if(getnotificationcount()>0) { ?>
      <div class="notifications js-notifications">
         <h3>Notifications</h3>
         <ul class="notifications-list">
             
            <li class="item no-data">You don't have notifications
			 <input type="hidden" id="val2" value="<?php echo getnotificationcount(); ?>"><br>
                                    <input type="hidden" id="val3" value="<?php echo getnotificationcount(); ?>">
			 </li>
			 
              <?php
                $sno = 1;
                $notify = pFETCH("SELECT * FROM `notification` WHERE `read_status`=? AND (`to`=? OR `title`=? OR `title`=? OR (`message`=? AND `driver_name`!='') )", 0,'admin','DROPTAXI - Your Trip is Started','DROPTAXI - Your Trip is End','Confirm to get this booking');
                while ($notifygfetch = $notify->fetch(PDO::FETCH_ASSOC)) {
                    if($notifygfetch['type']=='User-Admin') { 
                    $linkk=$sitename.'master/booking.htm?read='.$notifygfetch['id'];    
                    }
                    elseif($notifygfetch['title']=='DROPTAXI - Quote Price for Booking')
                    {
                     $linkk=$sitename.'master/driver_bittings.htm?read='.$notifygfetch['id'];          
                    }
                    elseif($notifygfetch['title']=='DROPTAXI - Your Trip is Started')
                    {
                     $linkk=$sitename.'master/closedtrips.htm?read='.$notifygfetch['id'];         
                    }
                    else 
                    {
                     $linkk=$sitename.'master/confirmedtrip.htm?read='.$notifygfetch['id'];         
                    }
                    
                    
                ?>
            <li class="item js-item expired" data-id="1">
               
                <i class="fas fa-arrow-alt-circle-right"></i>
                 <a href="<?php echo $linkk; ?>" style="color:#fff;">
               <div class="details">
                   <span class="title"><?php echo $notifygfetch['message']; ?></span>
                   <span class="date"><?php echo date('d-m-Y',strtotime($notifygfetch['date'])); ?></span></div>
                   </a>
               <button type="button" class="button-default button-dismiss js-dismiss">×</button>
               
            </li>
            <?php } ?>
         </ul>
         <a href="<?php echo $sitename; ?>master/notificationlist.htm" class="show-all">Show all notifications</a>
      </div>
       <?php } ?>
     
                        © <?php echo date('Y'); ?> Droptaxi - <span class="d-none d-sm-inline-block"> Crafted with <i class="mdi mdi-heart text-danger"></i> by Webtoall</span>
                </footer>

    
      </div>
<!--data tables-->
     <script src="<?php echo $sitename; ?>public/assets/js/jquery.min.js"></script>

<script src="<?php echo $sitename; ?>public/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $sitename; ?>public/assets/js/metisMenu.min.js"></script>
<script src="<?php echo $sitename; ?>public/assets/js/jquery.slimscroll.js"></script>
<script src="<?php echo $sitename; ?>public/assets/js/waves.min.js"></script>
<script src="<?php echo $sitename; ?>public/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>



     

                 <!-- Required datatable js -->
        <script src="<?php echo $sitename; ?>public/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo $sitename; ?>public/plugins/datatables/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="<?php echo $sitename; ?>public/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="<?php echo $sitename; ?>public/plugins/datatables/buttons.bootstrap4.min.js"></script>
        <script src="<?php echo $sitename; ?>public/plugins/datatables/jszip.min.js"></script>
        <script src="<?php echo $sitename; ?>public/plugins/datatables/pdfmake.min.js"></script>
        <script src="<?php echo $sitename; ?>public/plugins/datatables/vfs_fonts.js"></script>
        <script src="<?php echo $sitename; ?>public/plugins/datatables/buttons.html5.min.js"></script>
        <script src="<?php echo $sitename; ?>public/plugins/datatables/buttons.print.min.js"></script>
        <script src="<?php echo $sitename; ?>public/plugins/datatables/buttons.colVis.min.js"></script>
        <!-- Responsive examples -->
        <script src="<?php echo $sitename; ?>public/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="<?php echo $sitename; ?>public/plugins/datatables/responsive.bootstrap4.min.js"></script>

        <!-- Datatable init js -->
        <script src="<?php echo $sitename; ?>public/assets/pages/datatables.init.js"></script>

      <!-- App js -->
     <script src="<?php echo $sitename; ?>public/assets/js/app.js"></script>
	 

<script>
 
 $(document).ready(function(){
     // alert(a)
   
   function sendRequest()
   {
     var successCount = 0;
       $.ajax({
         cache: true,
         url: "<?php echo $fsitename; ?>notification.php",
         success: 
           function(data)
           {
               if(data!='' && data!='0')
               {
            $("#condisplay").css("display", "");
           $("#notblock").css("background-color", "red");
            $('#noti').html(data); 
             
            var a = data;
            //alert(a)
            $('#a_order').val(a);
            var t = $('#val2').val();
            if(a!=t)
            {
                 var audioElement = document.createElement('audio');
                 audioElement.setAttribute('src', '<?php echo $fsitename; ?>Siren_Noise-KevanGC-1337458893.mp3');
                 audioElement.play();
                 $('#val2').val(a);
                 $('#val3').val(a)
            }
            else
            {
             var c=0;
            // alert("can not play");
            var a = $("#val1").val();
              c++;
             //alert(c);
 
            }
               }
               else{
                var a = data;
                $('#val2').val(a);
                //$('#val3').val(a);
                $("#condisplay").css("display", "none");
               
               }
         },
         complete: function() 
         {
           
          // setInterval(sendRequest, 30000); 
         }
     });
   };

   setInterval(function(){sendRequest();}, 30000);
  ///////////////////////////////////////////////////////////////////
 
 });
 
 </script>

	 <script type="text/javascript">
    function deleteimage(a, b, c, d, e, f) {
  
        $.ajax({
            type: "POST",
            url: "<?php echo $sitename; ?>config/functions_ajax.php",
            data: {image: a, id: b, table: c, path: d, images: e, pid: f},
            success: function (data) {
               // alert(data);   
                $('#delimage').html(data);
            }

        });
    }

    function deleteimage1(a, b, c, d, e, f) {

        $.ajax({
            type: "POST",
            url: "<?php echo $sitename; ?>config/functions_ajax.php",
            data: {image: a, id: b, table: c, path: d, images: e, pid: f},
            success: function (data) {
               // alert(data);   
                $('#delimage1').html(data);
            }

        });
    }
    function deleteimage2(a, b, c, d, e, f) {

        $.ajax({
            type: "POST",
            url: "<?php echo $sitename; ?>config/functions_ajax.php",
            data: {image: a, id: b, table: c, path: d, images: e, pid: f},
            success: function (data) {
               // alert(data);   
                $('#delimage2').html(data);
            }

        });
    }
</script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<script>
$(document).ready(function(){
  $(".js-show-notifications").click(function(){
    $(".js-notifications").toggle();
  });
  
//   $(".button-dismiss").click(function(){
//       alert('remove');
//     $(".expired").removeClass("expired").addClass( "removenoti" );
//   });
  $(".button-dismiss").on("click", function(e) {
    $(this).closest('.expired').remove();
});

});



</script>
    </body>

</html>
