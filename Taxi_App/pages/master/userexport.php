<?php include ('../../config/config.inc.php');
$filename="Users-".date('d-m-Y').'.csv';
$fp = fopen('php://output', 'w');

$header=array("Date","Name","Emailid","Contactno","Address","Account Status");

header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
fputcsv($fp, $header);

$query=pFETCH("SELECT * FROM `register` WHERE `id`!=? ORDER BY `id` DESC", 0);
while ($row = $query->fetch(PDO::FETCH_ASSOC))
{
    
    if($row['date']!='')
     {
       $registerdate=date("d-m-Y g:i a",strtotime($row['date']));  
     }
     else
     {
       $registerdate='-';  
     }   
     
     if($row['name']!='')
     {
       $name=$row['name'];  
     }
     else
     {
       $name='-';  
     }   
     
     if($row['emailid']!='')
     {
       $emailid=$row['emailid'];   
     }
     else
     {
       $emailid='-';  
     }   
     
      if($row['mobileno']!='')
     {
       $mobileno=$row['mobileno'];   
     }
     else
     {
       $mobileno='-';  
     }  
     
      if($row['address']!='')
     {
       $address=$row['address'];   
     }
     else
     {
       $address='-';  
     }  
      
       if($row['status']=='1')
     {
       $status='Active';   
     }
     else
     {
       $status='Inactive';  
     }  

$res=array($registerdate,$name,$emailid,$mobileno,$address,$status);  
    
fputcsv($fp, $res);
}
exit;
?>