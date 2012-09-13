<?php
	// Do Cron Job //
	
	include_once('func.inc.php');
	
	$sql = "SELECT * FROM  `tblusers`;";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc($result)) {
		$username 		= $row['Username'];
		$name 			= $row['Name'];
		$email 		   = $row['Email'];
		$emailFreq 	   = $row['EmailFreq'];
		$subjectIds 	  = explode(",",$row['SubjectIds']);
		$session 		 = $row['Session'];
		$password 		= decrypt($row['Password']);
		
		$attResults = checkAttendance($username, $password, $subjectIds);
		
		/*
		$attResults = Array (
						Array (
						'id' => '4',
						'absent' => '0',
						'total' => '7'
						)
					);
		*/
		foreach ($attResults as $re) {
			$id = $re['id'];				// ID môn học.
			$absent = (int)$re['absent'];	// Số slot absent hiện tại.
			$total = $re['total'];			// Tổng số slot.
			$oldAbsent = getOldAbsent($username, $id); //Số slot absent lần trước.	
			if ($absent != $oldAbsent) {
				// Giá trị absent bị thay đổi.
				$percent = (100 * (int)$absent / (int)$total);
				if (addToDraft($email, $name, $id, $absent, $percent)) {
					setNewAbsent($username, $id, $absent);
				}
			}
		}
	}
	
?>