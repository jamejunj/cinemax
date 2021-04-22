<?
include('admin-header.php');

if ($data['class']!=3){
header("location:index.php");
exit(0);
}
?>
<div class="col-md">
<?
function clsText($cls){
	if ($cls==0) return "banned";
	if ($cls==1) return "member";
	if ($cls==2) return "staff";
	if ($cls==3) return "admin";
	return "guest";
}
?>
<h3>จัดการผู้ใช้</h3>
<hr>
<?
if ($_GET['action']=='add'){
if (isset($_POST['addUser'])){
	if ($_POST['nusn']==null){
		$msg = "<div class='alert alert-danger'>ERROR: ชื่อผู้ใช้เป็นค่าว่างไม่ได้</div>";
	}
	$secure = sha1(md5(stripslashes($_POST['npw'])));
	$sql = "INSERT INTO users (username,password,fname,lname,class) 
	VALUES ('".$_POST['nusn']."','".$secure."','".$_POST['fname']."','".$_POST['lname']."',".$_POST['class'].")";
	if (mysqli_query($objCon,$sql))
		$msg = "<div class='alert alert-success'>SECCESS: เพิ่มผู้ใช้แล้ว</div>";
}	
?>
<?=$msg?>
<form method="post" autocomplete="chrome-off">
	<div class="form-group">
    <label>ชื่อผู้ใข้</label>
    <input type="text" autocomplete="chrome-off" class="form-control" name="nusn"  placeholder="">
	</div>
	<div class="form-group">
    <label>รหัสผ่าน</label>
    <input type="password" autocomplete="chrome-off" class="form-control" name="npw"  placeholder="">
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
		<option value="0">Banned</option>
		<option value="1" selected>Member</option>
		<option value="2">Staff</option>
		<option value="3">Admin</option>
	  </select>
	</div>
	<div class="form-group">
	<input type="submit" class="btn btn-success" name="addUser">
		</div>
</form>
<?}else if ($_GET['action']=="edit" && isset($_GET['id'])){
	
if (isset($_POST['editUser'])){
	if ($_POST['password']!=null){
		$secure = sha1(md5(stripslashes($_POST['password'])));
		$usql = "UPDATE `users` SET `username`='".$_POST['username']."',`password`='$secure',`class`='".$_POST['class']."',`fname`='".$_POST['fname']."',`lname`='".$_POST['lname']."' WHERE `id`=".$_GET['id']."";
	}else{
		$usql = "UPDATE `users` SET `username`='".$_POST['username']."',`class`='".$_POST['class']."',`fname`='".$_POST['fname']."',`lname`='".$_POST['lname']."' WHERE `id`=".$_GET['id']."";
	}
	if (mysqli_query($objCon,$usql))
		$msg = "<div class='alert alert-success'>SECCESS: อัพเดตข้อมูลสำเร็จ</div>";
	else
		$msg = "mysql error";
}

$sql = "SELECT * FROM users WHERE id=".$_GET['id']."";
$objQuery = mysqli_query($objCon,$sql);
$user = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
if (!$user){
	header("location:admin-users.php");
}
?>
<?=$msg?>
<form method="post" autocomplete="chrome-off">
	<div class="form-group">
    <label>ชื่อผู้ใข้</label>
    <input type="text" autocomplete="chrome-off" value="<?=$user['username']?>" class="form-control" name="username" placeholder="">
	</div>
	<div class="form-group">
    <label>รหัสผ่าน</label>
    <input type="password" autocomplete="chrome-off" value="" class="form-control" name="password"  placeholder="เว้นว่างไว้หากไม่ต้องการเปลี่ยน">

	<div class="form-group">
    <label>ชื่อ</label>
    <input type="text" class="form-control" name="fname" value="<?=$user['fname']?>" placeholder="ชื่อจริง">
	</div>
	<div class="form-group">
    <label>นามสกุล</label>
    <input type="text" class="form-control" name="lname" value="<?=$user['lname']?>" placeholder="นามสกุล">
	</div>
	<div class="form-group">
    <label>Role</label>
	  <select class="form-control" name="class">
		<option value="0" <?=($user['class']==0 ? "selected" : "")?>>Banned</option>
		<option value="1" <?=($user['class']==1 ? "selected" : "")?>>Member</option>
		<option value="2" <?=($user['class']==2 ? "selected" : "")?>>Staff</option>
		<option value="3" <?=($user['class']==3 ? "selected" : "")?>>Admin</option>
	  </select>
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-success" name="editUser">
	</div>
</form>
<?}else{
$sql = "SELECT * FROM users ORDER BY id ASC";
$online = "SELECT * FROM users WHERE active >= (NOW() - INTERVAL 5 MINUTE)";
$list = $objCon->query($sql);	
?>
<a href="admin-users.php?action=add"><button class="btn btn-success">เพิ่มผู้ใช้</button></a>
<h4>รายชื่อผู้ใช้ทั้งหมด</h4>
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
	  <td><a href="admin-users.php?action=edit&id=<?=$user['id']?>">แก้ไขข้อมูล</a></td>
    </tr>
	<?}?>
  </tbody>
</table>
<h4>ผู้ใช้ล่าสุด</h4>
<table class="table">
  <thead>
    <tr>
      <th>#</th>
	  <th>User</th>
      <th>First Name</th>
      <th>Last Name</th>
	  <th>Role</th>
	  <th>Last active</th>
    </tr>
  </thead>
  <tbody>
	<?
	$list = $objCon->query($online);	
	while($user = $list->fetch_assoc()) {
	?>
	<tr>
      <th scope="row"><?=$user['id']?></th>
	  <td><?=$user['username']?></td>
      <td><?=$user['fname']?></td>
      <td><?=$user['lname']?></td>
      <td><?=clsText($user['class'])?></td>
	  <td><?=$user['active']?></td>
    </tr>
	<?}?>
  </tbody>
</table>
<?}?>