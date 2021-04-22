<?php
session_start();
$server = "localhost" ;
$servername = "" ; // ชื่อผู้ใช้ในการติดต่อกับฐานข้อมูล
$serverpass = "" ; // password ในการเชื่อมต่อกับฐานข้อมูล
$dbbb = "" ; // 

$objCon = mysqli_connect($server,$servername,$serverpass,$dbbb);
mysqli_query($objCon, "SET NAMES UTF8");
?>