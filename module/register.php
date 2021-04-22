<?php
include('connect.php');

$msg = "";

function utf8_strlen($s) {
	$c = strlen($s); $l = 0;
	for ($i = 0; $i < $c; ++$i)
	if ((ord($s[$i]) & 0xC0) != 0x80) ++$l;
	return $l;
}

if (isset($_POST['register'])){
	$sql = "SELECT * FROM `users` WHERE `username`='".$_POST['username']."'";
	$query = mysqli_query($objCon, $sql);
	if (mysqli_num_rows($query) > 0) $exist = 1;
	
	$msg = "<div class='alert alert-danger'><b>Error :</b> มีข้อผิดพลาด";
	if (strlen($_POST['username'])<4 || strlen($_POST['username'])>10){
		$msg = $msg."<br> - ชื่อผู้ใช้จะต้องมีความยาวระหว่าง 4-10 ตัวอักษรและจะต้องเป็นภาษาอังกฤษเท่านั้น";
		$error = 1;
	} else if ($exist){
		$msg = $msg."<br> - มีชื่อผู้ใช้ในระบบแล้ว";
		$error = 1;
	}
	if ($_POST['fname']==null){
		$msg = $msg."<br> - กรุณาระบุชื่อจริง</li>";
		$error = 1;
	}
	if ($_POST['lname']==null){
		$msg = $msg."<br> - กรุณาระบุนามสกุล</li>";
		$error = 1;
	}
	if ($_POST['password']==null){
		$msg = $msg."<br> - กรุณาระบุรหัสผ่าน</li>";
		$error = 1;
	}else if ($_POST['password']!='' && utf8_strlen($_POST['password'])<4 || utf8_strlen($_POST['password'])>12){
		$msg = $msg."<br> - รหัสผ่านจะต้องมีความยาวระหว่าง 4-12 ตัวอักษรและจะต้องเป็นภาษาอังกฤษเท่านั้น</li>";
		$error = 1;
	}
	if ($_POST['password']!=$_POST['check_password']){
		$msg = $msg."<br> - รหัสผ่านไม่ตรงกัน</li>";
		$error = 1;
	}
	if (!$error){
		$secure = sha1(md5(stripslashes($_POST['password'])));
		$sql = "INSERT INTO users (username,password,fname,lname,class) 
		VALUES ('".$_POST['username']."','".$secure."','".$_POST['fname']."','".$_POST['lname']."',0)";
		if (mysqli_query($objCon,$sql))
			$msg = "<div class='alert alert-success'>SECCESS: สมัครสมาชิกสำเร็จ</div>";
	}else{
		$msg.= "</div>";
	}
}
?>
<?=$msg?>