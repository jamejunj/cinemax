<?
include('header.php');
?>
<?
if (!isset($_SESSION["id"])){
?>
<div class="modal fade" id="disclaimer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="disclaimer" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Disclaimer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>เนื้อหาภายในเว็บไซต์นี้คัดลอกมาจากเว็บไซต์อื่นเพื่อเป็น placeholder เท่านั้น</p>
		<p>เป็นส่วนหนึ่งของการเรียนการสอนรายวิชา 2301375 D BASE SYS ภาคการศึกษาปลาย ปีการศึกษา 2563</p>
		<p>Dummy account</p>
		<p>0: [<code>test</code>, <code>test</code>]</p>
		<p>1: [<code>member</code>, <code>member</code>]</p>
		<p>2: [<code>staff</code>, <code>staff</code>]</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">รับทราบ</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="register" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="register" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">สมัครสมาชิก</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		<form>
			<div class="container">
			<div id="alert"></div>
			<div class="form-group">
		    <label>ชื่อผู้ใข้</label>
			<input type="text" autocomplete="chrome-off" class="form-control" name="setusername"  placeholder="">
			</div>
			<div class="form-group">
			<label>รหัสผ่าน</label>
			<input type="password" autocomplete="chrome-off" class="form-control" name="setpassword"  placeholder="">
			</div>
			<div class="form-group">
			<label>ยืนยันรหัสผ่าน</label>
			<input type="password" autocomplete="chrome-off" class="form-control" name="check_password"  placeholder="">
			</div>
			<hr>
			<div class="form-group">
			<label>ชื่อ</label>
			<input type="text" onkeyup="document.getElementById('fname').innerHTML = this.value;" class="form-control" name="fname"  placeholder="ชื่อจริง">
			</div>
			<div class="form-group">
			<label>นามสกุล</label>
			<input type="text" onkeyup="document.getElementById('lname').innerHTML = this.value;" class="form-control" name="lname"  placeholder="นามสกุล">
			</div>
			<div class="form-check">
			  <input class="form-check-input" type="checkbox" value="" name="agree" id="agree">
			  <label class="form-check-label">
				ข้าพเจ้า <b id="fname"></b>  <b id="lname"></b> ยืนยันและยอมรับเงื่อนไขการให้บริการ
			  </label>
			</div>
			</div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
		<button type="button" class="btn btn-success" id="reg">สมัครสมาชิก</button>
      </div>
    </div>
  </div>
</div>
<div class="container">
<h1>Cinemax</h1>
โรงหนังมีคุณภาพ คุ้มค่า ไม่มีหลอกลวง
<script type="text/javascript">
    $(window).on('load',function(){
        $('#disclaimer').modal('show');
    });
</script>
<div class="d-flex justify-content-center">
<div class="w-75">
<div class="card">
<article class="card-body text-center">
	<h4 class="card-title mb-4 mt-1">เข้าสู่ระบบ</h4>
	<hr>
	<form method="post" class="d-grid gap-2">
	<div id="login_msg" align="left"><?=$return?></div>
	<div class="form-group">
	<div class="input-group">
		<span class="input-group-text"> <i class="fa fa-user"></i> </span>
		<input name="username" class="form-control" placeholder="Username" type="text">
	</div> <!-- input-group.// -->
	</div> <!-- form-group// -->
	<div class="form-group">
	<div class="input-group">
		<span class="input-group-text"> <i class="fa fa-lock"></i> </span>
	    <input name="password" class="form-control" placeholder="******" type="password">
	</div> <!-- input-group.// -->
	</div> <!-- form-group// -->
	<div class="form-group">
	<button type="submit" name="login" class="btn btn-primary">เข้าสู่ระบบ</button>
	<button class="btn btn-secondary" onclick="event.preventDefault();" data-bs-toggle="modal" data-bs-target="#register"> สมัครสมาชิก  </button>
	</div> <!-- form-group// -->
	<p class="text-center"><a href="javascript:alert('สมน้ำหน้า!');" class="btn">ลืมรหัสผ่าน?</a></p>
	</form>
</article>
</div> <!-- card.// -->
</div>
</div>
<script>
	$(document).ready(function(){
		$("button[name='login']").click(function(event){
			event.preventDefault();
			var username = $("input[name='username']").val();
			var password = $("input[name='password']").val(); 
			if (username=='' || password==''){
				$("#login_msg").html("<div class='alert alert-danger'>กรุณาระบุชื่อผู้ใช้และรหัสผ่านให้ถูกต้อง</div>");
			}else{
				$.ajax({
						url:"module/login.php",
						method:"POST",
						data:{
							username:username, 
							password:password, 
							login:true
						},
						beforeSend: function() {
							$("button[name='login']").attr("disabled", true);
							$("button[name='login']").html("Loading...")
						},
						success:function(data){
							$('#login_msg').html(data);
							if ($('#success').length==0){
								$("button[name='login']").attr("disabled", false);
								$("button[name='login']").html("เข้าสู่ระบบ")
							}else{
								$("button[name='login']").html("เข้าสู่ระบบสำเร็จ")
								$("button[name='login']").removeClass("btn btn-primary").addClass("btn btn-success")
							}
						}
				});
			}
		});
		
		$("#reg").click(function(event){
				event.preventDefault();
				var username = $("input[name='setusername']").val();
				var password = $("input[name='setpassword']").val();
				var check_password = $("input[name='check_password']").val();
				var fname = $("input[name='fname']").val();
				var lname = $("input[name='lname']").val();
				if (!document.getElementById("agree").checked){
					$('#alert').html("<div class='alert alert-danger'>กรุณายอมรับเงื่อนไขการให้บริการ</div>");
				}else{
					$.ajax({
						url:"module/register.php",
						method:"POST",
						data:{
							username:username, 
							password:password, 
							check_password:check_password, 
							fname:fname, 
							lname:lname, 
							register:true
						},
						success:function(data){
							$('#alert').html(data);
						}
					});
				}
		});
	});
</script>
</div>
<?}else{?>
<div class="container">
<div id="highlight" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#highlight" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#highlight" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#highlight" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="movie_img/big1.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>First slide label</h5>
        <p>Some representative placeholder content for the first slide.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="movie_img/big2.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Second slide label</h5>
        <p>Some representative placeholder content for the second slide.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="movie_img/big3.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Third slide label</h5>
        <p>Some representative placeholder content for the third slide.</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#highlight" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#highlight" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
<h1>Cinemax</h1>
โรงหนังมีคุณภาพ คุ้มค่า ไม่มีหลอกลวง
<hr>
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
	<!-- $sql = "SELECT * FROM `cinema` WHERE mid='THIS ID' ORDER BY start ASC"; -->
	<!--<div class="time">(เพิ่มปุ่มจองของเวลาต่าง ๆ)</div>-->
  </div>
</div>
</div>
<?}?>
</div>
</div>
<?
}
?>
