<?php
	// Do Cron Job //

	include_once('func.inc.php');
	
	$sql = "SELECT * FROM  `tblemail`";
	$result = mysql_query($sql);
	
	while ($row = mysql_fetch_assoc($result)) {
		$mailId = $row['ID'];
		$sendTo = $row['SendTo'];
		$name = $row['Name'];
		$subject = $row['Subject'];
		$absent = $row['Absent'];
		$percent = $row['Percent'];
		
		$htmlContent = "
		<html>
		<head>
		<title>Attendance Notifier</title>
		<style type= 'text/css '>
		.das {
			color: #F00;
		}
		</style>
		</head>
		<body>
		<h2>Chào bạn <strong>$name</strong>!</h2>
		<p>Bạn vừa vắng mặt một slot môn <strong class= 'das '>$subject</strong> vào ngày hôm nay.</p>
		<h3>Số slot Absent: <span class= 'das '>$absent</span></h3>
		<h3>Số phần trăm nghỉ: <span class= 'das '>$percent%</span> <em class= 'das '>(trên 20% sẽ bị cấm thi)</em></h3>
		<br />
		<p><em>Email này được gửi từ dịch vụ Attendance Notifier, cảm ơn bạn đã sử dụng dịch vụ.</em><br/>
		<a href= 'http://full.name.vn/att'>http://full.name.vn/att</a></p>
		</body>
		</html>
		";
		
		if (sendMail($sendTo, "Bạn vừa Absent môn $subject ($absent).", $htmlContent)) {
			$sql = "DELETE FROM `tblemail` WHERE `tblemail`.`ID` = ".$mailId.";";
			mysql_query($sql);
		}
	}
?>