<?php 
include('header.php');
if ($login==0){
header("location:index.php");
exit(0);
}
if (!isset($_POST['choose']) && !isset($_POST['confirm'])){
	header("location:movie.php");
}

$msg = '<div class="alert alert-info">กรุณาตรวจสอบข้อมูลให้ถูกต้อง</div>';

$sql = "SELECT s.*,m.mname,c.cname FROM schedule s,movie m, cinema c WHERE s.sid=".$_POST['sid']." AND m.mid=s.mid AND c.cid=s.cid";
$q = mysqli_query($objCon,$sql);
$r = mysqli_fetch_array($q,MYSQLI_ASSOC);

$sql = "SELECT pos FROM reserve WHERE sid=".$_POST['sid']."";
$query = mysqli_query($objCon,$sql);
while($row = mysqli_fetch_array($query)){
	array_push($reserved, $row['pos']);
}

$seats = "";
$price = 0;
function getprice($seat,$d,$p,$v){
	if ($seat[0]=="A" || $seat[0]=="B" || $seat[0]=="C")
		return $p;
	else if ($seat[0]=="V")
		return $v;
	else
		return $d;
}

if (isset ($_POST['choose'])){
	foreach ($_POST['seats'] as $seat){
		$seats.= $seat.",";
		$price += getprice($seat,$r['deluxe'],$r['premium'],$r['vip']);
	}
	$_SESSION['seats'] = $seats;
	$_SESSION['price'] = $price;
}

if (isset($_POST['confirm'])){
	$seatsArray = explode(",",$_SESSION['seats']);
	foreach ($seatsArray as $seat){
		$sql = "SELECT pos FROM reserve WHERE sid=".$_POST['sid']." AND pos='".$seat."'";
		$query = mysqli_query($objCon,$sql);
		if (mysqli_num_rows($query)!=0){
			$exist = 1;
			$msg = "<div class='alert alert-danger'>ERROR: ขณะนี้ที่นั่งถูกจองไปแล้ว, กรุณาดำเนินการใหม่อีกครั้ง</div>";
			$msg .= "<a href='reserve.php?movie=".$r["mid"]."'><button class='btn btn-danger'>กลับ</button>";
			$success = 1;
			break;
		}
	}

	if (!$exist){
		$msg = $sql;
		$sql = "INSERT INTO reserve (sid,user_id, pos) VALUES ";
		$sA = explode(",",$_POST['show_seats']);
		$k = 0;
		foreach ($sA as $seat){
			$k++;
			$sql .= "(".$_POST['sid'].",".$data['id'].",'".$seat."'),";
		}
		$query = mysqli_query($objCon,rtrim($sql,","));
		
		$ridsArray = array();
		$sql = "SELECT rid FROM reserve WHERE user_id=".$data['id']." ORDER BY rid DESC LIMIT $k";
		$sqlRIDS = mysqli_query($objCon,$sql);
		while($r = mysqli_fetch_array($sqlRIDS)){
			array_push($ridsArray, $r['rid']);
		}
		$rids = "";
		foreach ($ridsArray as $rid)
			$rids .= $rid.",";
		$sql = "INSERT INTO transaction (rid, user_id ,price, datetime) VALUE ('".rtrim($rids,',')."','".$data['id']."',".$_SESSION['price'].",'".date('Y-m-d H:i:s')."');";
		$queryT = mysqli_query($objCon,$sql);
		if ($query){
			$msg = "<div class='alert alert-success'>SUCCESS: การสั่งของสำเร็จ ท่านสามารถดูข้อมูลได้ที่ ข้อมูลส่วนตัว > ประวัติการสั่งซื้อ</div>";
			$msg .= "<a href='index.php'><button class='btn btn-danger'>กลับ</button>";
			$success = 1;
		}
	}
}
?>
<div class="container">
<h2>ยืนยันการสั่งจอง</h2>
<hr>
<?=$msg?>
<?
if (!$success){
?>
<form method="post">
<div class="form-group">
    <label>ผู้จอง</label>
    <input type="text" class="form-control" name="show_customer" value="<?=$data['fname']?> <?=$data['lname']?>" readonly>
</div>
<div class="form-group">
    <label>ภาพยนต์</label>
    <input type="text" class="form-control" name="show_mname" value="<?=$r['mname']?>" readonly>
</div>
<div class="form-group">
    <label>โรงภาพยนต์</label>
    <input type="text" class="form-control" name="show_cname" value="<?=$r['cname']?>" readonly>
</div>
<div class="form-group">
    <label>ที่นั่ง</label>
    <input type="text" class="form-control" name="show_seats" value="<?=rtrim($_SESSION['seats'], ',')?>" readonly>
</div>
<div class="form-group">
    <label>ราคา</label>
    <input type="text" class="form-control" name="show_price" value="<?=$_SESSION['price']?>" readonly>
</div>
<div class="form-group">
	<div class="row">
  <div class="col">
  <label>เริ่มต้น</label>
    <input type="text" class="form-control" name="show_start" value="<?=substr($r['start'],0,-3)?>" readonly>
  </div>
  <div class="col">
  <label>สิ้นสุด</label>
    <input type="text" class="form-control" name="show_end" value="<?=substr($r['end'],0,-3)?>" readonly>
  </div>
</div>
</div>
<div class="form-group">
<input type="hidden" class="form-control" name="sid" value="<?=$_POST['sid']?>">
<input type="submit" class="btn btn-success" name="confirm" value="ยืนยัน">
</div>
<?
}
?>
</form>