<?
include('system.php');
?>
<head>
<title>Cinemax Plus</title>
<meta property="og:title" content="Cinemax Plus" />
<meta property="og:description" content="จองตั๋วหนังง่าย ๆ ดังใจนึก" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
<script src="assets/js/jquery.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
</head>
<div class="mb-3">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Cinemax</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
			<a class="nav-link" href="index.php"> หน้าแรก</a>
		</li>
        <?
		if (isset($_SESSION["id"])){
		?>
      <li class="nav-item">
        <a class="nav-link" href="movie.php"> จองตั๋วภาพยนต์</a>
      </li>
	  <?}?>
      </ul>
	  <?
		if (isset($_SESSION["id"])){
		?>
	   <ul class="navbar-nav">
		<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            ยินดีต้อนรับ <?=$data['username']?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="edit.php">แก้ไขข้อมูลส่วนตัว</a></li>
			<li><a class="dropdown-item" href="history.php">ประวัติการสั่งซื้อ</a></li>
            <?if ($data['class']>=2){?><li><a class="dropdown-item" href="admin-panel/">ผู้ดูแลระบบ</a></li><?}?>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
          </ul>
        </li>
	</ul>
	<?}?>
    </div>
  </div>
</nav>
</div>
<script>
var online = setInterval(function () {
    $.get("module/online.php");
}, 5*60000); // 5 min
</script>