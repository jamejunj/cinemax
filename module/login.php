<?php 
session_start();

include_once('connect.php');

if (isset($_POST['login'])){
	$return = "<div class='alert alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> <b>ERROR</b> Pressed login</div>";
	$secure = sha1(md5(stripslashes($_POST['password'])));
	$strSQL = "SELECT * FROM users WHERE username='".$_POST['username']."' AND password ='$secure'";
	$objQuery = mysqli_query($objCon,$strSQL);
	$data = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
	if(!$data){
			$return = "<div class='alert alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> <b>ERROR</b> ชื่อผู้ใช้หรือรหัสผ่านผิด</div>";
	}else{
			$_SESSION["id"] = $data["id"];
			$_SESSION["class"] = $data["class"];
			session_write_close();
			$return = "<div id='success' class='alert alert-success'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> <b>SUCCESS</b> เข้าสู่ระบบสำเร็จ</div>";
			$success = 1;
	}
}
?>
<?=$return?>
<?
if ($success==1){
?>
<script>
window.setTimeout(function() {
	location.href = 'index.php';
}, 2000);
</script>
<?}?>