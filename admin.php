<?php 
include('header.php');
if ($login==0 || $data['class']<2){
header("location:index.php");
exit(0);
}
?>
<div class="mx-2">
<h2>ผู้ดูแลระบบ - จัดการโรงหนัง</h2>
<hr>
<div class="row">
<div class="col-md-2">
<ul class="list-group">
<nav class="list-group">
  <?if ($data['class']==3){?><a class="list-group-item" href="admin.php?section=users">User</a><?}?>
  <a class="list-group-item" href="admin.php?section=cinema">Cinema</a>
  <a class="list-group-item" href="admin.php?section=movie">Movie</a>
  <a class="list-group-item" href="admin.php?section=schedule">Schedule</a>
</nav>
</ul>
</div>
<div class="col-md">
<?
if ($_GET['section']=="users"){
$sql = "SELECT * FROM users ORDER BY id ASC";
$list = $objCon->query($sql);

function clsText($cls){
	if ($cls==0) return "banned";
	if ($cls==1) return "member";
	if ($cls==2) return "staff";
	if ($cls==3) return "admin";
	return "guest";
}
?>
<?
if (isset($_GET['add'])){
if (isset($_POST['addUser'])){
	if ($_POST['nusn']==null){
		$msg = "<div class='alert alert-danger'>ERROR: ชื่อผู้ใช้เป็นค่าว่างไม่ได้</div>";
	}
	$secure = sha1(md5(stripslashes($_POST['addPass'])));
	$sql = "INSERT INTO users (username,password,fname,lname,class) 
	VALUES ('".$_POST['nusn']."','".$secure."','".$_POST['fname']."','".$_POST['lname']."',".$_POST['class'].")";
	if (mysqli_query($objCon,$sql))
		$msg = "<div class='alert alert-success'>SECCESS: เพิ่มผู้ใช้แล้ว</div>";
}	
?>
<?=$msg?>
<form method="post">
	<div class="form-group">
    <label>เพิ่มผู้ใช้</label>
    <input type="text" autocomplete="blahblahblah" class="form-control" name="nusn"  placeholder="">
	</div>
	<div class="form-group">
    <label>กำหนดรหัส</label>
    <input type="password" autocomplete="blahblahblah" class="form-control" name="npw"  placeholder="">

	<div class="form-group">
    <label>ชื่อ</label>
    <input type="text" class="form-control" name="fname"  placeholder="ชื่อจริง">
	</div>
	<div class="form-group">
    <label>นามสกุล</label>
    <input type="text" class="form-control" name="lname"  placeholder="นามสกุล">
	</div>
	<div class="form-group">
    <label>Role</label>
	  <select class="form-control" name="class">
		<option value="0">Baned</option>
		<option value="1" selected>Member</option>
		<option value="2">Staff</option>
		<option value="3">Admin</option>
	  </select>
	</div>
	<div class="form-group">
	<input type="submit" class="btn btn-success" name="addUser">
	<a href=".?section=users&main"><button class="btn btn-danger">กลับ</button></a>
		</div>
</form>
<?}else{?>
<h3>จัดการผู้ใช้</h3>
<hr>
<a href="admin.php?section=users&add"><button class="btn btn-success">เพิ่มผู้ใช้</button></a>
<table class="table">
  <thead>
    <tr>
      <th>#</th>
	  <th>User</th>
      <th>First Name</th>
      <th>Last Name</th>
	  <th>Role</th>
	  <th>Mannage</th>
    </tr>
  </thead>
  <tbody>
	<?
	while($user = $list->fetch_assoc()) {
	?>
	<tr>
      <th scope="row"><?=$user['id']?></th>
	  <td><?=$user['username']?></td>
      <td><?=$user['fname']?></td>
      <td><?=$user['lname']?></td>
      <td><?=clsText($user['class'])?></td>
	  <td>[action]</td>
    </tr>
	<?}?>
  </tbody>
<?} // end USER SECTION?>
<?}else if ($_GET['section']=="cinema"){?>

<?}else if ($_GET['section']=="movie"){?>

<?}else if ($_GET['section']=="schedule"){?>

<?}else if ($_GET['section']=="report"){?>

<?}else{?>
(กรุณาเลือกการดำเนินการ)
<?}?>
</div>
</div>