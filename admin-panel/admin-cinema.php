<?
include('admin-header.php')
?>
<div class="col-md">
<h3>จัดการโรงภาพยนต์</h3>
<hr>
<?
if ($_GET['action']=='add'){
if (isset($_POST['add'])){
	$sql = "INSERT INTO cinema (cname) 
	VALUES ('".$_POST['cname']."')";
	if (mysqli_query($objCon,$sql))
		$msg = "<div class='alert alert-success'>SECCESS: สร้างโรงภาพยนต์แล้ว</div>";
	else
		$msg = "mysql error";
}	
?>
<?=$msg?>
<form method="post" autocomplete="chrome-off">
	<div class="form-group">
	<!--<label>หมายเลขโรง</label>
    <input type="number" class="form-control" name="cid"  placeholder="หมายเลขโรง">-->
    <label>ชื่อโรงภาพยนต์</label>
    <input type="text" autocomplete="chrome-off" class="form-control" name="cname" placeholder="ชื่อโรงภาพยนต์ (โรงภาพยนต์ i)">
	</div>
	<div class="form-group">
	<input type="submit" class="btn btn-success" name="add" value="เพิ่ม">
	</div>
</form>
</div>
<?}else if ($_GET['action']=="edit" && isset($_GET['id'])){
if (isset($_POST['edit'])){
	$sql = "UPDATE cinema SET cid='".$_POST['cid']."',cname='".$_POST['cname']."' WHERE cid=".$_GET['id']."";
	if (mysqli_query($objCon,$sql))
		$msg = "<div class='alert alert-success'>SECCESS: อัพเดตข้อมูลสำเร็จ</div>";
	else
		$msg = "mysql error";
}

$sql = "SELECT * FROM cinema WHERE cid=".$_GET['id']."";
$objQuery = mysqli_query($objCon,$sql);
$cin = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
if (!$cin){
	header("location:admin-cinema.php");
}
?>
<?=$msg?>
<form method="post" autocomplete="chrome-off">
	<div class="form-group">
	<label>หมายเลขโรง</label>
    <input type="number" class="form-control" name="cid" value="<?=$cin['cid']?>"  placeholder="หมายเลขโรง">
    <label>ชื่อโรงภาพยนต์</label>
    <input type="text" autocomplete="chrome-off" class="form-control" name="cname" value="<?=$cin['cname']?>" placeholder="ชื่อโรงภาพยนต์ (โรงภาพยนต์ i)">
	</div>
	<div class="form-group">
	<input type="submit" class="btn btn-success" name="edit" value="แก้ไข">
	</div>
</form>
<?}else{?>
<?
$sql = "SELECT * FROM cinema ORDER BY cid ASC";
$list = $objCon->query($sql);
?>
<a href="admin-cinema.php?action=add"><button class="btn btn-success">เพิ่มโรงภาพยนต์</button></a>
<table class="table">
  <thead>
    <tr>
      <th>#</th>
	  <th>Name</th>
	  <th>Edit</th>
	  <th>View</th>
    </tr>
  </thead>
  <tbody>
	<?
	while($cin = $list->fetch_assoc()) {
	?>
	<tr>
      <th scope="row"><?='T'.$cin['cid']?></th>
	  <td><?=$cin['cname']?></td>
	  <td><a href="admin-cinema.php?action=edit&id=<?=$cin['cid']?>">แก้ไขข้อมูล</td>
	  <td><a href="admin-schedule.php?fetch=<?='T'.$cin['cid']?>">ดูการฉาย</td>
    </tr>
	<?}?>
  </tbody>
<?}?>