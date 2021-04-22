<?
include('admin-header.php')
?>
<div class="col-md">
<h3>จัดการรอบการฉาย</h3>
<hr>
<?
function title_short($t){
	if (strlen($t)<=50)
		echo $t;
	else
		echo mb_substr($t,0,50,'UTF-8');
}

if ($_GET['action']=='create'){
if (isset($_POST['add'])){
	$objQuery = mysqli_query($objCon,"SELECT mlength AS l FROM movie WHERE mid=".$_POST['mid']."");
	$movie = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
	$start = date('H:i:s', strtotime($_POST['start']));
	$end = date('H:i:s', strtotime($_POST['start'].' + '.$movie['l'].'minute'));
	
	$sql = "SELECT sid FROM schedule WHERE cid=".$_POST['cid']." AND date='".$_POST['date']."' AND ('".$start."' <= end) AND ('".$end."' >= start)";
	$query = mysqli_query($objCon,$sql);
	if (mysqli_num_rows($query)!=0){
		$msg = "<div class='alert alert-danger'>ERROR: เวลาการฉายซ้ำซ้อน</div>";
	}else{
	$sql = "INSERT INTO schedule (cid, mid, type, date, start, end, deluxe, premium, vip) 
	VALUES (".$_POST['cid'].",".$_POST['mid'].",'".$_POST['sound'].",".$_POST['sub']."','".$_POST['date']."','".$start."','".$end."',".$_POST['deluxe'].",".$_POST['premium'].",".$_POST['vip'].")";
	if (mysqli_query($objCon,$sql))
		$msg = "<div class='alert alert-success'>SECCESS: สร้างรอบการฉายแล้ว ($start - $end)</div>";
	else
		$msg = "mysql error $sql";
	
	}
}	
?>
<?=$msg.$val?>
<form method="post" autocomplete="chrome-off">
	<div class="form-group">
    <label>โรงภาพยนต์</label>
    <select class="form-control" name="cid">
	<? // =($user['class']==1 ? "selected" : "")?>
		<option value="" selected>เลือกโรงภาพยนต์</option>
	<?
	$sql = "SELECT * FROM cinema ORDER BY cid ASC";
	$list = $objCon->query($sql);
	while($cin = $list->fetch_assoc()) {
	?>
		<option value="<?=$cin['cid']?>" <?=($_POST['cid']==$cin['cid'] ? 'selected' : '')?>><?=$cin['cname']?> (<?=$cin['cid']?>)</option>
	<?}?>
	</select>
	</div>
	<div class="form-group">
    <label>เรื่อง</label>
    <select class="form-control" name="mid">
	<option value="" selected>เลือกภาพยนต์</option>
	<?
	$sql = "SELECT * FROM movie WHERE onair=1 ORDER BY mid ASC";
	$list = $objCon->query($sql);
	while($mov = $list->fetch_assoc()) {
	?>
		<option value="<?=$mov['mid']?>" <?=($_POST['mid']==$mov['mid'] ? 'selected' : '')?>><?=$mov['mname']?></option>
	<?}?>
	</select>
	</div>
	<div class="form-group">
    <label>เสียง / ซับ</label>
    <div class="row">
  <div class="col">
    <input type="text" maxlength="2" class="form-control" name="sound" placeholder="เสียง:TH,JP,EN" value="<?=$_POST['sound']?>">
  </div>
  <div class="col">
    <input type="text" maxlength="2" class="form-control" name="sub" placeholder="ซับ:TH,JP,EN,-" value="<?=$_POST['sub']?>">
  </div>
</div>
	</div>
	<div class="form-group">
    <label>วันที่ฉาย</label>
    <input type="date" name="date" class="form-control" value="<?=($_POST['date'] ? $_POST['date'] : date("Y-m-d")) ?>">
	</div>
	<div class="form-group">
    <label>เวลาเริ่มต้น</label>
    <input type="time" name="start" class="form-control" value="<?=($start ? $start : '')?>">
	</div>
	<div class="form-group">
	<label>ราคา</label>
	<div class="row">
  <div class="col">
    <input type="number" class="form-control" name="deluxe" placeholder="deluxe" value="<?=($_POST['deluxe'] ? $_POST['deluxe'] : 180) ?>">
  </div>
  <div class="col">
    <input type="number" class="form-control" name="premium" placeholder="premium" value="<?=($_POST['premium'] ? $_POST['premium'] : 340) ?>">
  </div>
  <div class="col">
    <input type="number" class="form-control" name="vip" placeholder="vip"  value="<?=($_POST['vip'] ? $_POST['vip'] : 680) ?>">
  </div>
</div>
    	</div>
	<hr>
	<div class="form-group">
	<input type="submit" class="btn btn-success" name="add" value="เพิ่ม">
	</div>
</form>
</div>
<?}else if ($_GET['action']=="edit" && isset($_GET['id'])){
if (isset($_POST['edit'])){
	$objQuery = mysqli_query($objCon,"SELECT mlength AS l FROM movie WHERE mid=".$_POST['mid']."");
	$movie = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
	$objQuery = mysqli_query($objCon,"SELECT mlength AS l FROM movie WHERE mid=".$_POST['mid']."");
	$movie = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
	$start = date('H:i:s', strtotime($_POST['start']));
	$end = date('H:i:s', strtotime($_POST['start'].' + '.$movie['l'].'minute'));
	$sql = "UPDATE schedule SET cid='".$_POST['cid']."',mid='".$_POST['mid']."',start='".$start."',end='".$end."',type='".$_POST['sound'].",".$_POST['sub']."',deluxe=".$_POST['deluxe'].",premium=".$_POST['premium'].",vip=".$_POST['vip']." WHERE sid=".$_GET['id']."";
	//$sql = "UPDATE schedule SET end=start + INTERVAL ".$movie['l']." MINUTE WHERE sid=".$_GET['id']."";
	if (mysqli_query($objCon,$sql))
		$msg = "<div class='alert alert-success'>SECCESS: อัพเดตข้อมูลสำเร็จ</div>";
	else
		$msg = "mysql error";
}

if (isset($_POST['delete'])){
	$sql = "DELETE FROM schedule WHERE sid=".$_GET['id']."";
	if (mysqli_query($objCon,$sql)){
		$display = "style='display:none;'";
		$msg = "<div class='alert alert-success'>SECCESS: ลบสำเร็จ</div>";
	}else{
		$msg = "mysql error";
	}
}

$sql = "SELECT * FROM schedule WHERE sid=".$_GET['id']."";
$objQuery = mysqli_query($objCon,$sql);
$r = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
if (!$r){
	header("location:admin-schedule.php");
}
?>
<?=$msg?>
<form method="post" autocomplete="chrome-off" <?=$display?>>
	<div class="form-group">
    <label>โรงภาพยนต์</label>
    <select class="form-control" name="cid">
		<option value="" selected>เลือกโรงภาพยนต์</option>
	<?
	$sql = "SELECT * FROM cinema ORDER BY cid ASC";
	$list = $objCon->query($sql);
	while($cin = $list->fetch_assoc()) {
	?>
		<option value="<?=$cin['cid']?>" <?=($r['cid']==$cin['cid'] ? "selected" : "")?>><?=$cin['cname']?> (<?=$cin['cid']?>)</option>
	<?}?>
	</select>
	</div>
	<div class="form-group">
    <label>เรื่อง</label>
    <select class="form-control" name="mid">
	<option value="" selected>เลือกภาพยนต์</option>
	<?
	$sql = "SELECT * FROM movie WHERE onair=1 ORDER BY mid ASC";
	$list = $objCon->query($sql);
	while($mov = $list->fetch_assoc()) {
	?>
		<option value="<?=$mov['mid']?>" <?=($r['mid']==$mov['mid'] ? "selected" : "")?>><?=$mov['mname']?></option>
	<?}?>
	</select>
	</div>
	<div class="form-group">
    <label>เสียง / ซับ</label>
	<? 
	$type = explode(",",$r['type']);
	?>
    <div class="row">
  <div class="col">
    <input type="text" maxlength="2" class="form-control" name="sound" value="<?=$type[0]?>" placeholder="เสียง:TH,JP,EN" >
  </div>
  <div class="col">
    <input type="text" maxlength="2" class="form-control" name="sub" value="<?=$type[1]?>" placeholder="ซับ:TH,JP,EN,-" >
  </div>
</div>
	</div>
	<div class="form-group">
    <label>วันที่ฉาย</label>
    <input type="date" name="date" class="form-control" value="<?=$r['date']?>">
	</div>
	<div class="form-group">
    <label>เวลาเริ่มต้น</label>
    <input type="time" name="start" class="form-control" value="<?=$r['start']?>">
	</div>
	<div class="form-group">
	<label>ราคา</label>
	<div class="row">
  <div class="col">
    <input type="number" class="form-control" name="deluxe" value="<?=$r['deluxe']?>" placeholder="deluxe">
  </div>
  <div class="col">
    <input type="number" class="form-control" name="premium" value="<?=$r['premium']?>" placeholder="premium">
  </div>
  <div class="col">
    <input type="number" class="form-control" name="vip" value="<?=$r['vip']?>" placeholder="vip">
  </div>
</div>
    	</div>
	<hr>
	<div class="form-group">
	<input type="submit" class="btn btn-success" name="edit" value="แก้ไข">
	<div class="modal fade" id="cf_delete" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Action: Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		คุณแน่ใจหรือไม่ที่จะลบข้อมูล
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" name="delete" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </div>
 </div>
	<input type="button" onclick="event.preventDefault();" class="btn btn-danger" value="ลบ" data-bs-toggle="modal" data-bs-target="#cf_delete">
	</div>
</form>
<?}else{?>
<?
if (isset($_GET['fix'])){
	$objQuery = mysqli_query($objCon,"SELECT mlength AS l FROM movie WHERE mid=(SELECT mid FROM schedule WHERE sid=".$_GET['fix'].")");
	$movie = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
	$sql = "UPDATE schedule SET end=start + INTERVAL ".$movie['l']." MINUTE WHERE sid=".$_GET['fix']."";
	mysqli_query($objCon,$sql);
}
?>
<a href="admin-schedule.php?action=create"><button class="btn btn-success">สร้างรอบการฉายใหม่</button></a>
<!-- ONAIR -->
<?
$sql = "SELECT s.*,m.*,c.cname FROM schedule s, movie m, cinema c WHERE date=CURRENT_DATE() AND s.start<CURRENT_TIME() AND s.end>CURRENT_TIME() AND s.mid = m.mid AND s.cid = c.cid ORDER BY s.date DESC, s.end DESC";
$list = $objCon->query($sql);
?>
<h5>กำลังฉาย</h5>
<hr>
<div class="form-group">
<input id="fetch" type="text" class="form-control" placeholder="Look up!">
</div>
<table class="table" id="pending">
  <thead>
    <tr>
      <!--<th>#</th>-->
	  <th>T</th>
	  <th>Movie</th>
	  <th>Date</th>
	  <th>Start</th>
	  <th>End</th>
	  <th>Price</th>
	  <th>Edit</th>
    </tr>
  </thead>
  <tbody>
	<?
	if (mysqli_num_rows(mysqli_query($objCon,$sql)) > 0){
	$list = $objCon->query($sql);
	while($r = $list->fetch_assoc()) {
	?>
	<tr>
      <!--<th scope="row"><?=$r['sid']?></th>-->
	  <td><?='T'.$r['cid']?></td>
	  <td><?=title_short($r['mname'])?></td>
	  <td><?=$r['date']?></td>
	  <td><?=$r['start']?></td>
	  <td><?=($r['end']==$r['start'] ? "<a href='admin-schedule.php?fix=".$r['sid']."'>fix</a>" : $r['end'])?></td>
	  <td><?=$r['deluxe']?>,<?=$r['premium']?>,<?=$r['vip']?></td>
	  <td><a href="admin-schedule.php?action=edit&id=<?=$r['sid']?>">แก้ไขข้อมูล</td>
	  <td style="display:none;"><?='M'.$r['mid']?></td>
    </tr>
	<?}
	}else{
?>
	<tr>
      <td colspan="6" align="center">ไม่มีรายการที่กำลังฉายอยู่ ณ เวลา <?=date('H:i:s')?></td>
    </tr>
<?}?>
  </tbody>
</table>
<?
$sql = "SELECT s.*,m.*,c.cname FROM schedule s, movie m, cinema c WHERE 
(date>CURRENT_DATE() OR (date=CURRENT_DATE() AND s.start>CURRENT_TIME())) AND s.mid = m.mid AND s.cid = c.cid ORDER BY s.date ASC, s.start ASC";
$list = $objCon->query($sql);
?>
<h5>กำลังจะมาถึง</h5>
<hr>
<div class="form-group">
<input id="fetch_up" type="text" class="form-control" placeholder="Look up!">
</div>
<table class="table" id="upcoming">
  <thead>
    <tr>
      <!--<th>#</th>-->
	  <th>T</th>
	  <th>Movie</th>
	  <th>Date</th>
	  <th>Start</th>
	  <th>End</th>
	  <th>Price</th>
	  <th>Edit</th>
    </tr>
  </thead>
  <tbody>
	<?
	while($r = $list->fetch_assoc()) {
	?>
	<tr>
      <!--<th scope="row"><?=$r['sid']?></th>-->
	  <td><?='T'.$r['cid']?></td>
	  <td><?=title_short($r['mname'])?></td>
	  <td><?=$r['date']?></td>
	  <td><?=$r['start']?></td>
	  <td><?=($r['end']==$r['start'] ? "<a href='admin-schedule.php?fix=".$r['sid']."'>fix</a>" : $r['end'])?></td>
	  <td><?=$r['deluxe']?>,<?=$r['premium']?>,<?=$r['vip']?></td>
	  <td><a href="admin-schedule.php?action=edit&id=<?=$r['sid']?>">แก้ไขข้อมูล</td>
	  <td style="display:none;"><?='M'.$r['mid']?></td>
    </tr>
	<?}?>
  </tbody>
</table>
<?
$sql = "SELECT s.*,m.*,c.cname FROM schedule s, movie m, cinema c WHERE (s.date<CURRENT_DATE() OR (s.date=CURRENT_DATE() AND s.end<CURRENT_TIME())) AND s.mid = m.mid AND s.cid = c.cid ORDER BY s.date DESC, s.end DESC";
$list = $objCon->query($sql);
?>
<h5>สิ้นสุดแล้ว</h5>
<hr>
<div class="form-group">
<input id="fetch_end" type="text" class="form-control" placeholder="Look up!">
</div>
<table class="table" id="end">
  <thead>
    <tr>
      <!--<th>#</th>-->
	  <th>T</th>
	  <th>Movie</th>
	  <th>Date</th>
	  <th>Start</th>
	  <th>End</th>
	  <th>Price</th>
	  <th>Edit</th>
    </tr>
  </thead>
  <tbody>
	<?
	while($r = $list->fetch_assoc()) {
	?>
	<tr>
      <!--<th scope="row"><?=$r['sid']?></th>-->
	  <td><?='T'.$r['cid']?></td>
	  <td><?=title_short($r['mname'])?></td>
	  <td><?=$r['date']?></td>
	  <td><?=$r['start']?></td>
	  <td><?=$r['end']?></td>
	  <td><?=$r['deluxe']?>,<?=$r['premium']?>,<?=$r['vip']?></td>
	  <td><a href="admin-schedule.php?action=edit&id=<?=$r['sid']?>">แก้ไขข้อมูล</td>
	  <td style="display:none;"><?='M'.$r['mid']?></td>
    </tr>
	<?}?>
  </tbody>
</table>
<input type="hidden" id="master_fetch" value="<?=$_GET['fetch']?>">
<script>
$(document).ready(function(){
    var value = $("#master_fetch").val().toLowerCase();
    $("#pending tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
	$("#upcoming tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
	$("#end tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });

  $("#fetch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
	if (value.toUpperCase() == "TODAY") value = "<?=date('Y-m-d')?>";
	if (value.toUpperCase() == "TOMORROW") value = "<?=date('Y-m-d', strtotime(date('Y-m-d').'-1 days'))?>";
    $("#pending tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
 
  $("#fetch_up").on("keyup", function() {
    var value = $(this).val().toLowerCase();
	if (value.toUpperCase() == "TODAY") value = "<?=date('Y-m-d')?>";
	if (value.toUpperCase() == "TOMORROW") value = "<?=date('Y-m-d', strtotime(date('Y-m-d').'-1 days'))?>";
    $("#upcoming tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
  
  $("#fetch_end").on("keyup", function() {
    var value = $(this).val().toLowerCase();
	if (value.toUpperCase() == "YESTERDAY") value = "<?=date('Y-m-d', strtotime(date('Y-m-d').'-1 days'))?>";
    $("#end tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
<?}?>