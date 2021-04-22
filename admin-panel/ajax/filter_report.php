<?
include('../../module/connect.php');

if (!isset($_POST['from'], $_POST['to'])){
	exit(0);
}

if (date('Y-m-d') == $_POST['from'] && date('Y-m-d') == $_POST['to'])
	$datetext = "วันนี้";
else if ($_POST['from']==$_POST['to'])
	$datetext = "วันที่ ".$_POST['from'];
else 
	$datetext = "ช่วงวันที่ ".$_POST['from']." ถึง ".$_POST['to'];


$sql = "SELECT SUM(price) AS revenue FROM transaction WHERE datetime BETWEEN '".$_POST['from']." 00:00:00' AND '".$_POST['to']." 23:59:59'";
$objQuery = mysqli_query($objCon,$sql);
$range = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);

$sql = "SELECT `movie`.*,schedule.*, rid,COUNT(`reserve`.`rid`) AS c FROM `movie`, `reserve`,`schedule` WHERE schedule.mid=movie.mid AND reserve.sid=schedule.sid AND schedule.date BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' GROUP BY movie.mid ORDER BY c DESC LIMIT 5";
?>
<div>
<h4>ภาพยนต์ที่มีผู้ชมมากสุดใน<?=$datetext?></h4>
<hr>
<table id="list" class="table">
  <thead>
    <tr>
      <th>#</th>
	  <th>Img</th>
	  <th>Movie</th>
      <th>Seats</th>
    </tr>
  </thead>
  <tbody>
	<?
	$i = 0;
if (mysqli_num_rows(mysqli_query($objCon,$sql)) > 0){
	$list = $objCon->query($sql);
	while($top = $list->fetch_assoc()) {
	$i++;
	?>
	<tr>
      <th scope="row"><?=$i?></th>
	  <td><img src="<?=$top['img']?>" class="rounded" style="width:50px; height:auto;"/></td>
	  <td><?=$top['mname']?></td>
      <td><?=$top['c']?> คน</td>
    </tr>
<?
	}
}else{
?>
	<tr>
      <td colspan="5" align="center">ไม่มีผู้ชมภาพยนต์เลยใน<?=$datetext?></td>
    </tr>
<?}?>
  </tbody>
</table>
</div>
<br>
<h4>การสั่งซื้อตั๋ว<?=$datetext?></h4>
<hr>
รายได้รวมจากการขายบัตรภาพยนต์ : <?=($range['revenue'] ? $range['revenue'] : 0)?> บาท<br>
<?
$sql = "SELECT t.*,u.* FROM transaction t, users u 
WHERE t.user_id = u.id AND t.datetime BETWEEN '".$_POST['from']." 00:00:00' AND '".$_POST['to']." 23:59:59'  ORDER BY tid DESC";

?>
<table class="table">
  <thead>
    <tr>
      <th>#</th>
	  <th>Name</th>
	  <th>Price</th>
	  <th>Purchase</th>
	  <th>Status</th>
	  <th>Check</th>
    </tr>
  </thead>
  <tbody>
	<?
if (mysqli_num_rows(mysqli_query($objCon,$sql)) > 0){
	$list = $objCon->query($sql);
	while($t = $list->fetch_assoc()) {
	$t['tcode'] = 'T'.date('ym',strtotime($t['datetime'])).str_pad($t['tid'], 6, "0", STR_PAD_LEFT);
	?>
	<tr>
      <td scope="row"><?=$t['tcode']?></td>
	  <td><?=$t['fname'].' '.$t['lname']?></td>
	  <td><?=$t['price']?></td>
	  <td><?=date('H:i:s',strtotime($t['datetime']))?></td>
	  <td><?=($t['status']==0 ? $t['watch_time'] : 'ยังไม่รับชม')?></td>
	  <td><a href="../check.php?ticket_id=<?=$t['tcode']?>">ตรวจสอบตั๋ว</td>
    </tr>
<?
	}
}else{
?>
	<tr>
      <td colspan="5" align="center">ไม่มีการทำรายการใน<?=$datetext?></td>
    </tr>
<?}?>
  </tbody>
</table>
<h4>การสั่งจองล่วงหน้าที่มีกำหนดชม<?=$datetext?></h4>
<hr>
<?
$sql = "SELECT t.*,u.*,s.* FROM transaction t, users u, schedule s, reserve r
WHERE t.user_id = u.id AND t.datetime<'".$_POST['from']."' AND SUBSTRING_INDEX(t.rid, ',', 1)= r.rid AND r.sid=s.sid AND (s.date >= '".$_POST['from']."') AND (s.date <= '".$_POST['to']."') ORDER BY t.tid DESC";
?>
<table class="table">
  <thead>
    <tr>
      <th>#</th>
	  <th>Name</th>
	  <th>Price</th>
	  <th>Purchase</th>
	  <th>Status</th>
	  <th>Check</th>
    </tr>
  </thead>
  <tbody>
	<?
if (mysqli_num_rows(mysqli_query($objCon,$sql)) > 0){
	$list = $objCon->query($sql);
	while($t = $list->fetch_assoc()) {
	$t['tcode'] = 'T'.date('ym',strtotime($t['datetime'])).str_pad($t['tid'], 6, "0", STR_PAD_LEFT);
	?>
	<tr>
      <td scope="row"><?=$t['tcode']?></td>
	  <td><?=$t['fname'].' '.$t['lname']?></td>
	  <td><?=$t['price']?></td>
	  <td><?=$t['datetime']?></td>
	  <td><?=($t['status']==0 ? $t['watch_time'] : 'ยังไม่รับชม')?></td>
	  <td><a href="../check.php?ticket_id=<?=$t['tcode']?>">ตรวจสอบตั๋ว</td>
    </tr>
<?
	}
}else{
?>
	<tr>
      <td colspan="5" align="center">ไม่มีการจองล่วงหน้าซึ่งมีกำหนดรับชมใน<?=$datetext?></td>
    </tr>
<?}?>
  </tbody>
</table>

