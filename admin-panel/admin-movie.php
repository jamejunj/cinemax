<?
include('admin-header.php')
?>
<div class="col-md">
<h3>จัดการภาพยนต์</h3>
<hr>
<?
if ($_GET['action']=='add'){
if (isset($_POST['add'])){
	$target_dir = "../movie_img/cover/";
	$fname = md5(date("Y-m-d-H-i-s")).'.jpg';
	$target_file = $target_dir . basename($fname);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if (!empty($_FILES["upload"]) && move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)){
		$img = "https://sj-assist.com/dbasesys/movie_img/cover/$fname";
	}
	if ($_POST['mname']==null){
		$msg = "<div class='alert alert-danger'>ERROR: ชื่อหนังไม่ควรว่างนะจ๊ะ</div>";
	}
	$desc =nl2br($_POST['mdescription']);
	$sql = "INSERT INTO movie (mname, mdescription, mlength, img ,onair) 
	VALUES ('".$_POST['mname']."','".$desc."','".$_POST['mlength']."','".$img."','".$_POST['onair']."')";
	if (mysqli_query($objCon,$sql))
		$msg = "<div class='alert alert-success'>SECCESS: เพิ่มภาพยนต์แล้ว</div>";
	else
		$msg = "mysql error";
}	
?>
<?=$msg?>
<form method="post" action="" autocomplete="chrome-off" enctype="multipart/form-data">
	<div class="form-group">
	  <label>รูปภาพ</label>
	  <input class="form-control" type="file" accept="image/jpg" name="upload">
	</div>
	<div class="form-group">
    <label>ชื่อภาพยนต์</label>
    <input type="text" autocomplete="chrome-off" class="form-control" name="mname" placeholder="ชื่อภาพยนต์">
	</div>
	<div class="form-group">
    <label>คำอธิบาย</label>
    <textarea class="form-control" name="mdescription" rows="3" style="white-space: pre-wrap;"></textarea>
	</div>
	<div class="form-group">
    <label>ความยาว</label>
    <input type="number" class="form-control" name="mlength"  placeholder="ความยาว (นาที)">
	</div>
	<div class="form-group"><label>สถานะ</label></div>
	<div class="btn-group" role="group">
	  <input type="radio" class="btn-check" name="onair" value="1" id="onair" checked>
	  <label class="btn btn-outline-success" for="onair">On Air</label>
	  <input type="radio" class="btn-check" name="onair" value="0" id="non">
	  <label class="btn btn-outline-danger" for="non">None</label>
	</div>
	<div class="form-group mt-2">
	<input type="submit" class="btn btn-success" name="add">
	</div>
</form>
</div>
<?}else if ($_GET['action']=="edit" && isset($_GET['id'])){
if (isset($_POST['edit'])){
	$target_dir = "../movie_img/cover/";
	$fname = md5($_GET['id']).'.jpg';
	$target_file = $target_dir . basename($fname);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if (!empty($_FILES["upload"]) && move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)){
		$img = "https://sj-assist.com/dbasesys/movie_img/cover/$fname";
	}else{
		$img = $_POST['img'];
	}
	$sql = "UPDATE movie SET mname='".$_POST['mname']."',mdescription='".$_POST['mdescription']."',mlength=".$_POST['mlength'].",onair=".$_POST['onair'].",img='".$img."' WHERE mid=".$_GET['id']."";
	if (mysqli_query($objCon,$sql))
		$msg = "<div class='alert alert-success'>SECCESS: อัพเดตข้อมูลสำเร็จ</div>";
	else
		$msg = "mysql error";
}

$sql = "SELECT * FROM movie WHERE mid=".$_GET['id']."";
$objQuery = mysqli_query($objCon,$sql);
$mov = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
if (!$mov){
	header("location:admin-movie.php");
}
?>
<?=$msg?>
<form method="post" autocomplete="chrome-off">
	<div class="form-group">
	  <label>รูปภาพ</label>
	  <input class="form-control" type="file" accept="image/jpg" name="upload">
	</div>
	<div class="form-group">
	  <label>URL:</label>
	  <input class="form-control" type="text" value="<?=$mov['img']?>" name="img">
	</div>
	<div class="form-group">
    <label>ชื่อภาพยนต์</label>
    <input type="text" value="<?=$mov['mname']?>" class="form-control" name="mname" placeholder="ชื่อภาพยนต์">
	</div>
	<div class="form-group">
    <label>คำอธิบาย</label>
    <textarea class="form-control" name="mdescription" rows="5" style="white-space: pre-wrap;"><?=$mov['mdescription']?></textarea>
	</div>
	<div class="form-group">
    <label>ความยาว</label>
    <input type="number" value="<?=$mov['mlength']?>" class="form-control" name="mlength"  placeholder="ความยาว (นาที)">
	</div>
	<div class="form-group"><label>สถานะ</label></div>
	<div class="btn-group" role="group">
	  <input type="radio" class="btn-check" name="onair" value="1" id="onair" <?=($mov['onair']==1 ? "checked" : "")?>>
	  <label class="btn btn-outline-success" for="onair">On Air</label>
	  <input type="radio" class="btn-check" name="onair" value="0" id="non" <?=($mov['onair']==0 ? "checked" : "")?>>
	  <label class="btn btn-outline-danger" for="non">None</label>
	</div>
	<div class="form-group mt-2">
	<input type="submit" class="btn btn-success" name="edit">
	</div>
</form>
<?}else{?>
<?
$sql = "SELECT * FROM movie ORDER BY mid DESC";
$list = $objCon->query($sql);
?>
<div class="form-group">
<input id="fetch" type="text" class="form-control" placeholder="Look up!">
</div>
<div class="form-group mt-2">
<a href="admin-movie.php?action=add"><button class="btn btn-success">เพิ่มภาพยนต์</button></a>
</div>
<table id="list" class="table">
  <thead>
    <tr>
      <th>#</th>
	  <th>Img</th>
	  <th>Movie</th>
      <th>Length</th>
	  <th>Status</th>
	  <th>Edit</th>
	  <th>View</th>
    </tr>
  </thead>
  <tbody>
	<?
	function statecolor($st){
		return ($st==1 ? "success" : "danger");
	}
	while($mov = $list->fetch_assoc()) {
	?>
	<tr>
      <th scope="row"><?=$mov['mid']?></th>
	  <td><img src="<?=$mov['img']?>" class="rounded" style="width:50px; height:auto;"/></td>
	  <td><?=$mov['mname']?></td>
      <td><?=$mov['mlength']?> min</td>
	  <td class="text-<?=statecolor($mov['onair'])?>"><?=($mov['onair']==1 ? "กำลังฉาย" : "ไม่แสดง")?></td>
	  <td><a href="admin-movie.php?action=edit&id=<?=$mov['mid']?>">แก้ไขข้อมูล</td>
	  <td><a href="admin-schedule.php?fetch=<?='M'.$mov['mid']?>">รอบฉาย</td>
    </tr>
	<?}?>
  </tbody>
</table>
<?}?>
<script>
$(document).ready(function(){
  $("#fetch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#list tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>