<?php 
include('header.php');
if ($login==0){
header("location:index.php");
exit(0);
}

if (isset($_GET['view_ticket'])){
	$_GET['view_ticket'] = substr($_GET['view_ticket'],-6);
	$sql = "SELECT t.*,s.*,m.mname,c.cid FROM transaction t, reserve r, schedule s,movie m, cinema c 
		WHERE tid='".$_GET['view_ticket']."' AND SUBSTRING_INDEX(t.rid, ',', 1)=r.rid 
		AND r.user_id=".$data['id']." AND r.sid = s.sid AND s.mid=m.mid AND s.cid=c.cid";
	$objQuery = mysqli_query($objCon,$sql);
	$t = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
	$t['tcode'] = 'T'.date('ym',strtotime($t['datetime'])).str_pad($t['tid'], 6, "0", STR_PAD_LEFT);
	$seats = "";
	$ridsArray = explode(",",$t['rid']);
	foreach ($ridsArray as $rid){
		$sql = "SELECT pos FROM reserve WHERE rid=$rid";
		$objQuery = mysqli_query($objCon,$sql);
		$r = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);
		$seats.= $r['pos'].",";
	}
?>
<style>
  #tracker{
	display: none;
  }

@media print {
  #tracker{
	display: block;
  }
  
  body * {
    visibility: hidden;
  }
  #ticket, #ticket * {
    visibility: visible;
  }
  #ticket {
    position: absolute;
    left: 0;
    top: 0;
	width:100%;
  }
}
</style>
<div class="container">
<h2>บัตรภาพยนต์หมายเลข <?=$t['tcode']?></h2>
<div class="row">
<div class="col-md-4">
<div class="card" align="center">
<?
$link = "javascript:void(0);";
$disabled = "disabled";
$dpn = "style='display:none;'";
if (date('Y-m-d H:i:s') < date('Y-m-d H:i:s', strtotime($t['date'].' '.$t['start'].' - 10 minute'))){
	$cls = 'btn btn-secondary';
	$txt = 'ยังไม่ถึงเวลาฉาย';
	$dpn = "style='display:block'";
}else if ($t['status']==1 && date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($t['date'].' '.$t['end']))){
	$cls = 'btn btn-danger';
	$txt = 'ภาพยนต์จบไปแล้ว';
}else if (!$t){
	$cls = 'btn btn-dark';
	$txt = 'ไม่พบข้อมูลบัตร';
}else if ($t['status']==0){
	$cls = 'btn btn-success';
	$txt = 'ใช้งานแล้วเมื่อ '.$t['watch_time'];
	$place = 'ขอบคุณที่ใช้บริการ';
}else{
	$link = "check.php?ticket_id=".$t['tid'] ;
	$cls = 'btn btn-primary';
	$txt = 'ยังไม่เข้าชมภาพยนต์';
	$place = "กรุณาให้พนักงาน Scan QR ก่อนเข้าชมภาพยนต์";
	$disabled = "";
	$dpn = "style='display:block'";
}
?>
<img class="card-img-top" src="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=<?=urlencode('https://sj-assist.com/dbasesys/check.php?ticket_id='.$t['tid'].'')?>"/>
<div class="card-body">
<h5 class="card-title"><?=$t["mname"]?></h3>
<p class="card-text">
<?=$place?>
</p>
<a href="<?=$link?>"><button class="<?=$cls?> mt-auto"><?=$txt?></button></a>
</div>
</div>
</div>
<div class="col-md-8">
<div class="card" id="ticket">
<div class="card-body">
<h5 class="card-title">Ticket <?=$t['tcode']?></h3>
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
  
  <dt class="col-sm-3" id="tracker">Track</dt>
  <dd class="col-sm-9" id="tracker"><img class="card-img-top" style="width:100px; heigth:auto;"src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?=urlencode('https://sj-assist.com/dbasesys/check.php?ticket_id='.$t['tcode'].'')?>"/></dd>
</dl>
</p>
</div>
</div>
<div class="mt-2">
<button class="btn btn-primary" onclick="window.print();" <?=$dpn?>>พิมพ์ตั๋วภาพยนต์</button>
</div>
</div>
</div>
<?
}else{

$sql = "SELECT t.*,r.*,s.*,m.mname,c.cname FROM transaction t, reserve r, schedule s,movie m, cinema c 
		WHERE SUBSTRING_INDEX(t.rid, ',', 1)=r.rid 
		AND r.user_id=".$data['id']."
		AND r.sid = s.sid AND s.mid=m.mid AND s.cid=c.cid
		ORDER BY t.datetime DESC
		";

// SELECT SUBSTRING_INDEX(rid, ',', 1) FROM `transaction`

/* $sql = "SELECT t.*,r.*,s.*,m.mname,c.cname FROM transaction t, reserve r,schedule s,movie m, cinema c 
		WHERE t.user_id=".$data['id']." AND r.rid IN (SELECT * FIND_IN_SET(r.rid,t.rid)) AND r.sid=s.sid AND s.mid=m.mid AND s.cid=c.cid
		ORDER BY r.rid DESC";*/ 
$list = $objCon->query($sql);
?>
<div class="container">
<h2>ประวัติการสั่งซื้อ</h2>
<hr>
<table class="table">
  <thead>
    <tr>
	  <th>Purchase</th>
	  <th>TID</th>
	  <th>Movie</th>
	  <th>Cinema</th>
	  <th>Price</th>
	  <th>Start</th>
	  <th>Ticket</th>
    </tr>
  </thead>
  <tbody>
	<?
	while($his = $list->fetch_assoc()) {
	$his['tcode'] = 'T'.date('ym',strtotime($his['datetime'])).str_pad($his['tid'], 6, "0", STR_PAD_LEFT);
	?>
	<tr>
	  <td><?=substr($his['datetime'],0,-3)?></td>
	  <td><?=$his['tcode']?></td>
	  <td><?=$his['mname']?></td>
	  <td><?=$his['cname']?></td>
	  <td><?=$his['price']?></td>
	  <td><?=$his['date']?> <?=substr($his['start'],0,-3)?></td>
	  <td><a href="history.php?view_ticket=<?=$his['tcode']?>">View</a></td>
    </tr>
	<?}?>
  </tbody>
</table>
</div>
<?}?>