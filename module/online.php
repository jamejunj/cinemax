<?
include_once('../system.php');

$sql = "UPDATE users SET active=NOW() WHERE id=".$data['id']."";
mysqli_query($objCon,$sql);
?>