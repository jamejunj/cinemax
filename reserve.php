<?php 
include('header.php');
if ($login==0){
header("location:index.php");
exit(0);
}

	function typeToText($t){
		$s = explode(",",$t);
		$lang = array(
			"th"=>"ภาษาไทย",
			"en"=>"ภาษาอังกฤษ",
			"jp"=>"ภาษาญี่ปุ่น",
			"TH"=>"ภาษาไทย",
			"EN"=>"ภาษาอังกฤษ",
			"JP"=>"ภาษาญี่ปุ่น",
		);
		$text = "เสียง".$lang[$s[0]];
		if ($s[1]!='-')
			$text.= " | "."คำบรรยาย".$lang[$s[1]];
		return $text;
	}
	
	$strSQL = "SELECT * FROM movie WHERE mid=".$_GET['movie']."";
	$objQuery = mysqli_query($objCon,$strSQL);
	$movie = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);

	if (!$movie){
		header("location:index.php");
	}
?>
<div class="container">
<h2>การสั่งจองตั๋วภาพยนต์</h2>
<div class="alert alert-info">ท่านกำลังจองภาพยนต์ เรื่อง <b><?=$movie['mname']?></b></div>
<h3>กรุณาเลือกรอบฉาย</h3>
<br>
<?
$days   = [];
$period = new DatePeriod(
    new DateTime(), // Start date of the period
    new DateInterval('P1D'), // Define the intervals as Periods of 1 Day
    6 // Apply the interval 6 times on top of the starting date
);

foreach ($period as $day)
{
    $days[] = $day->format('l, (d/m)');
}
?>
<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
<?
$i=0;
foreach ($days as $day){
	$i+=1;
	$d = explode(',',$day);
?>
<li class="nav-item" role="presentation">
    <button class="nav-link <?=($i==1 ? 'active' : '')?>" id="pills-<?=$d[0]?>-tab" data-bs-toggle="pill" data-bs-target="#pills-<?=$d[0]?>" type="button" role="tab" aria-controls="pills-<?=$d[0]?>" aria-selected="<?=($i==1 ? 'true' : 'false')?>"><?=$d[0].$d[1]?></button>
</li>
<?}?>
</ul>
<div class="tab-content" id="pills-tabContent">
<?
$i=0;
foreach ($days as $day){
	$i+=1;
	$d = explode(',',$day);
?>
<div class="tab-pane fade show <?=($i==1 ? 'active' : '')?>" id="pills-<?=$d[0]?>" role="tabpanel" aria-labelledby="pills-<?=$d[0]?>-tab">
<form method="post" action="position.php">
<div class="form-group">
<input type="radio" style="display:none;" required>
<?
$sql = "SELECT c.*,s.type FROM schedule s,cinema c WHERE s.cid=c.cid AND s.mid=".$_GET['movie']." AND s.date=CURRENT_DATE() + INTERVAL ".($i-1)." DAY";
if ($i-1 == 0) $sql.= " AND s.start>=CURRENT_TIME()";
$sql.=" GROUP BY s.cid";
$list = $objCon->query($sql);
$shd = mysqli_num_rows(mysqli_query($objCon,$sql));
while($c = $list->fetch_assoc()) {
?>
<div class="mt-2">
<h4><?=$c['cname']?></h4>
<?=typeToText($c['type'])?>
<hr>
	<?
	$tsql = "SELECT * FROM schedule WHERE mid=".$_GET['movie']." AND cid=".$c['cid']." AND date=CURRENT_DATE() + INTERVAL ".($i-1)." DAY";
	if ($i-1 == 0) $sql.= " AND start>=CURRENT_TIME()";
	$btn = $objCon->query($tsql);
	while($s = $btn->fetch_assoc()) {
	?>
	<input type="radio" class="btn-check" name="sid" value="<?=$s['sid']?>" id="<?=$s['sid']?>" autocomplete="off" />
	<label class="btn btn-outline-primary btn-lg" for="<?=$s['sid']?>"><?=substr($s['start'],0,5)?></label>
	<?}?>
</div>
<?}?>
<?
if ($shd==0){
?>
(ไม่มีรอบการฉายในวันดังกล่าว)
<?}?>
</div>
<hr>
<div class="form-group">
<?
if ($shd!=0){
?>
<input type="submit" class="btn btn-success" name="choose" value="เลือก" />
<?}?>
<a href="movie.php"><button class="btn btn-danger">กลับ</button></a>
</div>
</form>
</div>
<?}?>
</div>
</div>
