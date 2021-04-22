<?php 
include('header.php');
if ($login==0){
header("location:index.php");
}
?>
<div class="container">
<?if (!isset($_GET['movie'])){?>
<h2>กำลังฉาย</h2>
<div class="row">
<?php

function desc_short($desc,$mid){
	if (strlen($desc)<=300)
		echo $desc;
	else
		echo mb_substr($desc,0,300,'UTF-8')."...<a href='movie.php?id=$mid'>อ่านต่อ</a>";
}

$sql = "SELECT * FROM `movie` WHERE onair=1 ORDER BY mid ASC";
$list = $objCon->query($sql);
while($movie = $list->fetch_assoc()) {
?>
<div class="col-md-3 d-flex">
<div class="card mt-2 flex-fill">
	<img class="card-img-top" src="<?=$movie["img"]?>" alt="<?=$movie["mname"]?>">
  <div class="card-body  d-flex flex-column">
    <h5 class="card-title"><?=$movie["mname"]?></h3>
    <a href="movie.php?movie=<?=$movie['mid']?>" class="btn btn-primary mt-auto">รายละเอียด</a>
  </div>
</div>
</div>
<?}?>
</div>
<?}else{
$sql = "SELECT * FROM movie WHERE mid=".$_GET['movie']."";
$objQuery = mysqli_query($objCon,$sql);
$movie = mysqli_fetch_array($objQuery,MYSQLI_ASSOC);	
if ($movie['onair']==0){
	header('location:movie.php');
}
?>
<h1><?=$movie["mname"]?></h1>
<div class="row">
<div class="col-md-3">
<div class="card mt-2 flex-fill">
	<img class="card-img-top" src="<?=$movie["img"]?>" alt="<?=$movie["mname"]?>">
  <div class="card-body  d-flex flex-column">
    <a href="reserve.php?movie=<?=$movie['mid']?>" class="btn btn-primary mt-auto">ดูรอบภาพยนต์</a>
  </div>
</div>
</div>
<div class="col-md">
<div class="card mt-2 flex-fill">
  <div class="card-body  d-flex flex-column">
    <h5 class="card-title"><?=$movie["mname"]?></h5>
	<p class="card-text">
	<?=$movie['mdescription']?>
	</p>
  </div>
</div>
</div>
</div>
<?}?>
</div>