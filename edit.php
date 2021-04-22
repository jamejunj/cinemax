<?php 
include('header.php');
if ($login==0){
	header("location:index.php");
	exit(0);
}else{
	if ($data['username']=='test' || $data['username']=='member' || $data['username']=='staff'){
		echo "<div class='alert alert-info'>What are you trying to do sir ?</div>";
		exit(0);
	}
}

$msg = "";

function utf8_strlen($s) {
	$c = strlen($s); $l = 0;
	for ($i = 0; $i < $c; ++$i)
	if ((ord($s[$i]) & 0xC0) != 0x80) ++$l;
	return $l;
}

if (isset($_POST['update'])){
	$sql = "SELECT * FROM `users` WHERE `username`='".$_POST['new_username']."' AND id!=".$data['id']."";
	$query = mysqli_query($objCon, $sql);
	if (mysqli_num_rows($query) > 0) $exist = 1;
	
	$msg = "<div class='alert alert-danger'><b>Error :</b> มีข้อผิดพลาด<br>";
	if ($_POST['new_username']!=$data['username'] && (strlen($_POST['new_username'])<4 || strlen($_POST['new_username'])>10)){
		$msg = $msg."<br> - ชื่อผู้ใช้จะต้องมีความยาวระหว่าง 4-10 ตัวอักษรและจะต้องเป็นภาษาอังกฤษเท่านั้น";
		$error = 1;
	} else if ($exist){
		$msg = $msg."<br> - มีชื่อผู้ใช้ในระบบแล้ว";
		$error = 1;
	}
	if ($_POST['fname']==null){
		$msg = $msg."<br> - กรุณาระบุชื่อจริง";
		$error = 1;
	}
	if ($_POST['lname']==null){
		$msg = $msg."<br> - กรุณาระบุนามสกุล";
		$error = 1;
	}
	if ($_POST['new_password']!=null && utf8_strlen($_POST['new_password'])<4 || utf8_strlen($_POST['new_password'])>12){
		$msg = $msg."<br> - รหัสผ่านจะต้องมีความยาวระหว่าง 4-12 ตัวอักษรและจะต้องเป็นภาษาอังกฤษเท่านั้น";
		$error = 1;
	}
	if ($_POST['new_password']!=null && $_POST['new_password']!=$_POST['check_password']){
		$msg = $msg."<br> - รหัสผ่านไม่ตรงกัน";
		$error = 1;
	}
	if (!$error){
		if ($_POST['new_password']!=null){
			$secure = sha1(md5(stripslashes($_POST['new_password'])));
			$usql = "UPDATE `users` SET `username`='".$_POST['new_username']."',`password`='$secure',`fname`='".$_POST['fname']."',`lname`='".$_POST['lname']."' WHERE `id`=".$data['id']."";
		}else{
			$usql = "UPDATE `users` SET `username`='".$_POST['new_username']."',`fname`='".$_POST['fname']."',`lname`='".$_POST['lname']."' WHERE `id`=".$data['id']."";
		}
		if (mysqli_query($objCon,$usql)){
			$msg = "<div class='alert alert-success'>SECCESS: อัพเดตข้อมูลสำเร็จ";
		}
	}
	$msg = $msg."</div>";
}

if (isset($_POST['upgrade'])){
	$newcls = ($data['class'] ? 0 : 1);
	$sql = "UPDATE `users` SET `class`=$newcls";
	if (mysqli_query($objCon,$sql)){
		$msg =  ($data['class'] ? "<div class='alert alert-success'>SECCESS: ยกเลิกการสมัครสมาชิกแล้ว เราเสียใจมากและหวังว่าคุณจะกลับมาใหม่</div>" : "<div class='alert alert-success'>SECCESS: สมัครสมาชิกสำเร็จ!  คุณสามารถดูหนังได้ฟรีตลอดชีวิต</div>");
		$data['class'] = $newcls;
	}
}
?>
<div class="container">
<h2>แก้ไขข้อมูลส่วนตัว</h2>
<?=$msg?>
<form method="post" autocomplete="chrome-off">
	<div class="form-group">
    <label>ชื่อผู้ใช้</label>
    <input type="text" onchange="this.value = (this.value!='' ? this.value : '<?=$data['username']?>');" autocomplete="new-username" value="<?=($_POST['new_username'] ? $_POST['new_username'] : $data['username'])?>" class="form-control" name="new_username" placeholder="ชื่อผู้ใช้ใหม่">
	</div>
	<div class="form-group">
    <label>รหัสผ่าน</label>
    <input type="password" onkeyup="document.getElementById('cpw').style.display = (this.value!='' ? 'block' : 'none');" autocomplete="new-password" value="" class="form-control" name="new_password"  placeholder="เว้นว่างไว้หากไม่ต้องการเปลี่ยน">
	</div>
	<div class="form-group" id="cpw" style="display:none;">
    <label>ยืนยันรหัสผ่าน</label>
    <input type="password" autocomplete="chrome-off" value="" class="form-control" name="check_password"  placeholder="ยืนยันรหัสผ่าน">
	</div>
	<hr>
	<div class="row">
	<div class="col">
	<div class="form-group">
    <label>ชื่อ</label>
    <input type="text" class="form-control" name="fname" value="<?=($_POST['fname'] ? $_POST['fname'] : $data['fname'])?>" placeholder="ชื่อจริง">
	</div>
	</div>
	
	<div class="col">
	<div class="form-group">
    <label>นามสกุล</label>
    <input type="text" class="form-control" name="lname" value="<?=($_POST['lname'] ? $_POST['lname'] : $data['lname'])?>" placeholder="นามสกุล">
	</div>
	</div>
	
	</div>
	<hr>
	<?
	$cls = "";
	if ($data['class']==0){
		$cls = "ผู้ใช้ทั่วไป";
	} if ($data['class']==1){
		$cls = "<span class='text-success'>สมาชิก - Member</span>";
	} if ($data['class']==2){
		$cls = "<span class='text-primary'>พนักงาน - Staff</span>";
	} if ($data['class']==3){
		$cls = "<span class='text-danger'>ผู้ดูแลระบบ - Administrator</span>";
	}
	?>
	<p>ประเภทบัญชี : <b><?=$cls?></b></p>
	<?
	if ($data['class']<2){
	?>
	<input type="button" class="btn btn-outline-<?=($data['class'] ? 'danger' : 'primary')?>" name="upgrade" value="<?=($data['class'] ? 'ยกเลิกการสมัครสมาชิก' : 'สมัครสมาชิก')?>">
	<?}?>
	<div class="form-group mt-2">
		<input type="submit" class="btn btn-success" name="update" value="แก้ไขข้อมูลส่วนตัว">
	</div>
</form>
</div>