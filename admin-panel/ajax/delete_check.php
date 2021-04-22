<?php
include('../../module/connect.php');

if (isset($_POST['delete'])){
	if (isset($_POST['table'], $_POST['pk'], $_POST['pk_id'])){
		$del = "DELETE FROM ".$_POST['table']." WHERE ".$_POST['pk']."='".$_POST['pk_id']."'";
		
		if ($_POST['table']=='schedule'){
			$sql = "SELECT * FROM transaction t,reserve r,users u 
			WHERE t.user_id=u.id AND SUBSTRING_INDEX(t.rid, ',', 1)=r.rid AND r.sid='".$_POST['pk_id']."'";
			if (mysqli_num_rows(mysqli_query($objCon,$sql)) > 0){
				$list = $objCon->query($sql);
				$affact = array();
				$affact['user'] = array();
				while($a = $list->fetch_assoc()) {
					echo "<code>REFUND</code> การสั่งซื้อ ".$a['tid'];
				}
			}
		}
	}
}
?>