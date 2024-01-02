<?php
$timestamp = strtotime('12:09 PM') + 60*60;

$time = date('h:i a', $timestamp);

echo $time;//11:09
?>