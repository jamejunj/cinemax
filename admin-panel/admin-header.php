<?php 
include('../system.php');
if ($login==0 || $data['class']<2){
header("location:../index.php");
exit(0);
}
?>
<head>
<title>Admin Panel</title>
<meta property="og:title" content="Cinemax Plus" />
<meta property="og:description" content="จองตั๋วหนังง่าย ๆ ดังใจนึก" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
<script src="../assets/js/jquery.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<div class="m-4">
<h2>ผู้ดูแลระบบ - จัดการโรงหนัง</h2>
<hr>
<div class="row">
<div class="col-md-2">
<ul class="list-group">
<nav class="list-group">
  <? if ($data['class']==3){?><a class="list-group-item" href="admin-users.php">User</a><?}?>
  <a class="list-group-item" href="admin-cinema.php">Cinema</a>
  <a class="list-group-item" href="admin-movie.php">Movie</a>
  <a class="list-group-item" href="admin-schedule.php">Schedule</a>
  <!--<a class="list-group-item" href="admin-reserve.php">Reserve</a>-->
  <a class="list-group-item" href="admin-report.php">Report</a>
  <a class="list-group-item btn btn-outline-danger" href="../index.php">กลับหน้าหลัก</a>
</nav>
</ul>
</div>