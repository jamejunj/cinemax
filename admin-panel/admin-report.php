<?
include('admin-header.php');
?>
<div class="col-md">
<h3>ข้อมูลสรุปผล</h3>
<hr>
<div>
<div class="row">
<div class="col-md-2">
วันที่
<input type="date" id="date_from" class="form-control" value="<?=date("Y-m-d")?>"/>
</div>
<div class="col-md-2" id='to' style="display:none;">
ถึง
<input type="date" id="date_to" class="form-control" value="<?=date("Y-m-d")?>"/>
</div>
<div class="col-md-2">
<div class="from-group">
<label>ช่วงเวลา</label>
<input type="checkbox" id="range" onclick="javascript: var e = document.getElementById('to'); e.style.display = (e.style.display=='none' ? 'block' : 'none');" class="from-control"/>
</div>
<input type="button" id="filter" class="btn btn-primary" value="Filter"/>
</div>
</div>
</div>
<div id="report"></div>
<script>
	$(document).ready(function(){
		$(function(){
			filter();
			$("#filter").click(filter);
		});
		
		function filter(){
				var from = $("#date_from").val();
				if (document.getElementById("range").checked){
					var to = $("#date_to").val();
				}else{
					var to = from;
				}
				if (from!=='' && to!=='' && from<=to){
					$.ajax({
						url:"ajax/filter_report.php",
						method:"POST",
						data:{from:from,to:to},
						success:function(data){
							$('#report').html(data);
						}
					});
			}
		}
	});
</script>