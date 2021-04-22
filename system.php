<?php
session_start();
include_once('module/connect.php');

$return = "";

$login = 0;

if (isset($_SESSION["id"])){
	$strSQL = "SELECT * FROM users WHERE id='".$_SESSION['id']."'";
	$objQuery = mysqli_query($objCon,$strSQL);
	$data = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
	$login = 1;
	if ($data['class']==3){
		$admin = 1;
	}
	$sql = "UPDATE users SET active=NOW() WHERE id=".$data['id']."";
	mysqli_query($objCon,$sql);
}

if(isset($_GET['logout'])){
	session_destroy();
	header("location:index.php");
}
?>