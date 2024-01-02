<?php

function generateRandomString($length = 4) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



class Form{
 
    // database connection and table name
    private $conn;
    private $table_name = "register";
 
    // object properties
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
function createbooking(){

$query = "INSERT INTO `booking`
            SET
                booking_km='".$this->booking_km."',customer_booking_amount='".$this->booking_amount."',customer_paid_booking_amount='".$this->customer_paid_booking_amount."',quote_amount='".$this->quote_amount."',register_id='".$this->register_id."',pickup_address='".$this->pickup_address."',drop_address='".$this->drop_address."',triptype='".$this->triptype."',car_id='".$this->car_id."',trip_date='".$this->trip_date."',drop_date='".$this->drop_date."',trip_time='".$this->trip_time."'";

    // prepare query
$stmt = $this->conn->prepare($query);  

    if($stmt->execute()){
    $registerid = $this->conn->lastInsertId();
    return $registerid;
    }
 
    return false;
}

function tempcreate(){

     $ip=$_SERVER['REMOTE_ADDR'];
    // query to insert record
   $query = "INSERT INTO `verifyregister`
            SET
                name='".$this->name."', emailid='".$this->emailid."',mobileno='".$this->mobileno."' ";

    // prepare query
    $stmt = $this->conn->prepare($query);
 

    // execute query
    if($stmt->execute()){
        $registerid = $this->conn->lastInsertId();
        return $registerid;
    }
 
    return false;
     
}


}
