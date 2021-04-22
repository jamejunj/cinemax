<?php 
include('header.php');
if ($login==0 || $data['class']<2){
	header("location:history.php?view_ticket=".$_GET['ticket_id']);
	exit(0);
}

if (isset($_GET['ticket_id'], $_POST['confirm_ticket'])){
	$sql = "UPDATE transaction SET status=0, watch_time='".date('Y-m-d H:i:s')."' WHERE tid=".$_GET['ticket_id']."";
	mysqli_query($objCon,$sql);
}

if (isset($_GET['ticket_id'])){
	$_GET['ticket_id'] = substr($_GET['ticket_id'],-6);
	$sql = "SELECT t.*,s.*,m.mname,c.cid FROM transaction t, reserve r, schedule s,movie m, cinema c 
		WHERE tid='".$_GET['ticket_id']."' AND SUBSTRING_INDEX(t.rid, ',', 1)=r.rid 
		AND r.sid = s.sid AND s.mid=m.mid AND s.cid=c.cid";
	$objQuery = mysqli_query($objCon,$sql);
	$t = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
	
	$seats = "";
	$ridsArray = explode(",",$t['rid']);
	foreach ($ridsArray as $rid){
		$sql = "SELECT pos FROM reserve WHERE rid=$rid";
		$objQuery = mysqli_query($objCon,$sql);
		$r = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
		$seats.= $r['pos'].",";
	}

?>
<div class="container">
<h2>ตรวจสอบบัตรภาพยนต์</h2>
<div class="row">
<div class="col-md-4">
<div class="card" align="center">
<div class="card-body">
<h5 class="card-title"><?=$t["mname"]?></h3>
<p class="card-text">
กดตรงนี้เพื่อยืนยันการใช้งาน
</p>
<?
$disabled = "disabled";
if (date('Y-m-d H:i:s') < date('Y-m-d H:i:s', strtotime($t['date'].' '.$t['start'].' - 10 minute'))){
	$cls = 'btn btn-secondary';
	$txt = 'ยังไม่ถึงเวลาฉาย';
}else if ($t['status']==1 && $date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($t['date'].' '.$t['end']))){
	$cls = 'btn btn-danger';
	$txt = 'ภาพยนต์จบไปแล้ว';
}else if (!$t){
	$cls = 'btn btn-dark';
	$txt = 'ไม่พบข้อมูลบัตร';
}else if ($t['status']==0){
	$cls = 'btn btn-success';
	$txt = 'ใช้งานแล้วเมื่อ '.$t['watch_time'];
}else{
	$cls = 'btn btn-primary';
	$txt = 'ยืนยันการใช้งาน';
	$disabled = "";
}
?>
<form method="post">
<input type="submit" name="confirm_ticket" <?=$disabled?> class="<?=$cls?>" value="<?=$txt?>" />
</form>
</div>
</div>
</div>
<div class="col-md-8">
<div class="card">
<div class="card-body">
<h5 class="card-title">Ticket ID <?='T'.date('ym',strtotime($t['date'])).str_pad($_GET['ticket_id'], 6, "0", STR_PAD_LEFT);?></h3>
<p class="card-text">
<dl class="row">
  <dt class="col-sm-3">Movie</dt>
  <dd class="col-sm-9"><?=$t['mname']?></dd>
  
  <dt class="col-sm-3">Theatre</dt>
  <dd class="col-sm-9 fs-2"><?=$t['cid']?></dd>

  <dt class="col-sm-3">Seat No.</dt>
  <dd class="col-sm-9 fs-1"><b><?=rtrim($seats,",")?></b></dd>

  <dt class="col-sm-3">Date</dt>
  <dd class="col-sm-9"><?=$t['date']?></dd>

  <dt class="col-sm-3">Showtime</dt>
  <dd class="col-sm-9"><?=substr($t['start'],0,-3)?></dd>
</dl>
</p>
</div>
</div>
</div>
</div>
</div>
<?}?>