<?php 
include('header.php');
if ($login==0){
header("location:index.php");
exit(0);
}
if (!isset($_POST['choose'])){
	header("location:movie.php");
}

	$reserved = array();
	$sql = "SELECT pos FROM reserve WHERE sid=".$_POST['sid']."";
	$query = mysqli_query($objCon,$sql);
	while($row = mysqli_fetch_array($query)){
		array_push($reserved, $row['pos']);
	}
	
	$sql = "SELECT s.*,m.mname,c.cname FROM schedule s,movie m, cinema c WHERE s.sid=".$_POST['sid']." AND m.mid=s.mid AND c.cid=s.cid";
	$q = mysqli_query($objCon,$sql);
	$s = mysqli_fetch_array($q,MYSQLI_ASSOC);
	
function poscls($l){
	if ($l>="D")
		return 'secondary';
	else
		return 'primary';
}

?>
<div class="container-fluid">
<div class="card bg-light mb-2">
<div class="card-body">
ท่านกำลังจองภาพยนต์ <span class="badge bg-secondary"><?=$s['mname']?></span><br>
โรง: <span class="badge bg-secondary"><?=$s['cname']?></span><br>
วัน <span class="badge bg-secondary"><?=date('l d-m-Y',strtotime($s['date']))?></span> เวลา: <span class="badge bg-secondary"><?=$s['start']?></span> - <span class="badge bg-secondary"><?=$s['end']?></span><br>
ราคา:  <span class="badge bg-secondary"><?=$s['deluxe']?></span> / <span class="badge bg-primary"><?=$s['premium']?></span> / <span class="badge bg-success"><?=$s['vip']?></span> บาท
</div>
</div>
<? // debig reserved mysqli_num_rows($query)?>
<form method="post" action="confirm.php">
<input type="hidden" name="sid" value="<?=$s['sid']?>">
<style>
.seats-group {
	flex: 1;
}
</style>
<div class="d-flex justify-content-center">
<div class="seats-group me-2">
<?
foreach (range('J','A',-1) as $row){
	echo '<div class="btn-group w-100 d-flex mb-2" role="group">';
	for ($i=1; $i<=10; $i++){
		if ($i < 10) $i = '0'.$i;
?>
<!--<button type="button" class="btn btn-sm btn-outline-<?=poscls($row)?> btn-block w-100" <?=(in_array($row.$i, $reserved) ? "disabled" : "")?>><?=$row.$i?></button>-->
<input type="checkbox" class="btn-check" id="<?=$row.$i?>" name="seats[]" value="<?=$row.$i?>" <?=(in_array($row.$i, $reserved) ? "disabled" : "")?>>
<label class="btn btn-sm btn-outline-<?=poscls($row)?> btn-block w-100" for="<?=$row.$i?>"><?=$row.$i?></label>
<?
	}
	echo '</div>';
}
?>
</div>
<div class="seats-group ms-2">
<?
foreach (range('J','A',-1) as $row){
	echo '<div class="btn-group  d-flex mb-2" role="group">';
	for ($i=11; $i<=20; $i++){
?>
<input type="checkbox" class="btn-check" id="<?=$row.$i?>" name="seats[]" value="<?=$row.$i?>" <?=(in_array($row.$i, $reserved) ? "disabled" : "")?>>
<label class="btn btn-sm btn-outline-<?=poscls($row)?> btn-block w-100" for="<?=$row.$i?>"><?=$row.$i?></label>
<?
	}
	echo '</div>';
}
?>
</div>
</div>
<div class="d-flex">
<?
for ($i=1; $i<=10; $i++){
?>
<div class="seats-group m-2">
<input type="checkbox" class="btn-check" id="<?="VIP".$i?>" name="seats[]" value="<?="VIP".$i?>" <?=(in_array("VIP".$i, $reserved) ? "disabled" : "")?>>
<label class="btn btn-sm btn-outline-success btn-block w-100" for="<?="VIP".$i?>"><?="VIP".$i?></label>
</div>
<?}?>
</div>
<input type="submit" class="btn btn-success" name="choose" value="เลือก" />
</div>
</form>
</div>