<?php
include_once('db.inc.php');

function mr($string) {
	return mysql_real_escape_string($string);	
}

function encrypt($decrypted) {
	$password = 'n*3h2HsaP1%hdXCa';
	$salt = '!kQm*fF3pXe1Kbm%9';
	$key = hash('SHA256', $salt . $password, true);
	srand(); $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
	if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) return false;
	$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $decrypted . md5($decrypted), MCRYPT_MODE_CBC, $iv));
	return $iv_base64 . $encrypted;
 } 

function decrypt($encrypted) {
	$password = 'n*3h2HsaP1%hdXCa';
	$salt = '!kQm*fF3pXe1Kbm%9';
	$key = hash('SHA256', $salt . $password, true);
	$iv = base64_decode(substr($encrypted, 0, 22) . '==');
	$encrypted = substr($encrypted, 22);
	$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4");
	$hash = substr($decrypted, -32);
	$decrypted = substr($decrypted, 0, -32);
	if (md5($decrypted) != $hash) return false;
	return $decrypted;
 }
function checkLogin($username, $password) {
	$post_data = array('username'=>$username, 'password'=>$password, 'testcookies'=>'0', 'iboard_url'=>''); 
	$ckfile = tempnam("/tmp", "CURLCOOKIE");
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, "http://cms-hcm.fpt.edu.vn/elearning/login/index.php"); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
	$index = curl_exec($ch); 
	curl_close($ch);
	//$index = "You are logged in as <a href=\"http://cms-hcm.fpt.edu.vn/elearning/user/view.php?id=2292&amp;course=1\">Trung Đinh Quang</a>";
	if (preg_match("/(?<=You are logged in as ).*?<\/a\>/i", $index, $html_name)) {
		preg_match("/(?<=>).*?(?=<)/i", $html_name[0], $name);
		updatePassword($username, $password);
		return $name[0];
	} else {
		return false;
	}
}

function checkAttendance($username, $password, $arrID) {
	$post_data = array('username'=>$username, 'password'=>$password, 'testcookies'=>'0', 'iboard_url'=>''); 
	$ckfile = tempnam("/tmp", "CURLCOOKIE");
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, "http://cms-hcm.fpt.edu.vn/elearning/login/index.php"); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
	$index = curl_exec($ch); 
	curl_close($ch);
	//echo $index;
	$arrSubject = array();
	foreach ($arrID as $_id) {
		// /(?<=href=\"http:\/\/cms-hcm\.fpt\.edu\.vn\/elearning\/mod\/attforblock\/view.php\?id=)\d+(?=\"\>\<img)/i
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'http://cms-hcm.fpt.edu.vn/elearning/course/view.php?id='.$_id); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$_pm = curl_exec($ch);
		curl_close($ch);
		preg_match("/(?<=href=\"http:\/\/cms-hcm\.fpt\.edu\.vn\/elearning\/mod\/attforblock\/view.php\?id=)\d+(?=\"\>\<img)/i", $_pm, $html_att_id);
		
		$id = $html_att_id[0];
		
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, 'http://cms-hcm.fpt.edu.vn/elearning/mod/attforblock/view.php?id='.$id); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$pm = curl_exec($ch);
		curl_close($ch);
		//echo $pm;

		if ($pm) {
			$absent = 0;
			$total = 0;
			if (preg_match("/(?<=\>Absent:).+<\/strong\>/i", $pm, $html_absent)) {
				preg_match("/(?<=\<strong\>)\d+(?=\<\/strong\>)/i", $html_absent[0], $absent);
			}
			if (preg_match("/(?<=\>Number of Sessions:).+<\/strong\>/i", $pm, $html_total)) {
				preg_match("/(?<=\<strong\>)\d+(?=\<\/strong\>)/i", $html_total[0], $total);
			}
			array_push($arrSubject ,array('ID' => $_id, 'absent' => $absent[0], 'total' => $total[0]));
		} else {
			//return false;
		}
	}
	return $arrSubject;
}
function updatePassword($username, $password) {
	$en = encrypt($password);
	$sql = "UPDATE  `tblusers` SET  `Password` =  '".mr($en)."' WHERE  `tblusers`.`Username` = '".mr($username)."';";
	return mysql_query($sql);
}
function firstTimesSetting($username, $name, $password) {
	if (isFirstLogin($username)) {		
		$password = encrypt($password);
		$sql = "INSERT INTO  `tblusers` (`ID` ,`Username` ,`Name` ,`Email` ,`EmailFreq` ,`SubjectIds` ,`Session` ,`Password`) VALUES (NULL ,  '".mr($username)."',  '".mr($name)."',  '".mr($username)."@fpt.edu.vn',  '2',  '',  '', '".mr($password)."');";
		$result = mysql_query($sql);
		
		sendWelcomeMail($username."@fpt.edu.vn", $name);
		return $result;
	} else {
		return "Username already exists!";
	}
}
function sendWelcomeMail($email, $name) {
$htmlContent = "
		<html>
		<head>
		<title>Attendance Notifier</title>
		</head>
		<body>
		<h2>Chào bạn <strong>$name</strong>!</h2>
		<p>Bạn vừa đăng ký nhận mail thông báo Absent từ dịch vụ Attendance Notifier.</p>
		<p>Truy cập <a href= 'http://full.name.vn/att'>http://full.name.vn/att</a> để chọn môn học và bắt đầu nhận thông báo từ mail.</p>
		<p>Để ngừng dịch vụ, bạn vui lòng đăng nhập vào <a href= 'http://full.name.vn/att'>http://full.name.vn/att</a>, sau đó chọn 'Không nhận mail' ở khung cài đặt.
		<br />
		<p><em>Email này được gửi từ dịch vụ Attendance Notifier. Cảm ơn bạn đã sử dụng dịch vụ.</em><br/>
		<a href= 'http://full.name.vn/att'>http://full.name.vn/att</a></p>
		</body>
		</html>
		";
		
	return sendMail($email, "[Attendance Notifier] Cảm ơn bạn đã đăng ký.", $htmlContent);
}

function sendNotifMail($block, $nextBlock, $term, $nextTerm, $email, $name, $username) {
$subjStr = "";
	$sql = "SELECT `Name` FROM `tblatt`, `tblsubjects` WHERE tblatt.Username = '".mr($username)."' AND tblatt.SubjectID = tblsubjects.ID;";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc($result)) {
		$subjStr .= $row['Name'] . ",";
	}
$htmlContent = "
		<html>
		<head>
		<title>Attendance Notifier</title>
		</head>
		<body>
		<img src='http://full.name.vn/att/images/email.png' width='80' height='80'>
		<h1>Attendance Notifier</h1>
		<h2>Chào bạn <strong>$name</strong>!</h2>
		<p>Bạn đã đăng ký nhận mail thông báo Absent từ dịch vụ Attendance Notifier.</p>
		<p>Môn học bạn đã đăng ký theo dõi là: <strong>$subjStr</strong>.</p>
		<p><strong>$block</strong> học kỳ <strong>$term</strong> đã kết thúc, bạn vui lòng truy cập <a href= 'http://full.name.vn/att'>http://full.name.vn/att</a> để thay đổi môn học cho <strong>$nextBlock</strong> học kỳ <strong>$nextTerm</strong>.</p>
		<br />
		<p>Để ngừng dịch vụ, bạn vui lòng đăng nhập vào <a href= 'http://full.name.vn/att'>http://full.name.vn/att</a>, sau đó chọn 'Không nhận mail' ở khung cài đặt.</p>
		<br />
		<p><em>Email này được gửi từ dịch vụ Attendance Notifier. Cảm ơn bạn đã sử dụng dịch vụ.</em><br/>
		<a href= 'http://full.name.vn/att'>http://full.name.vn/att</a></p>
		</body>
		</html>
		";
	sendMail($email, "[A.N.] Thay đổi theo dõi môn học cho $nextBlock $nextTerm.", $htmlContent);
}
function isFirstLogin($username) {
	$sql = "SELECT COUNT(*) FROM `tblusers` WHERE `Username` = '".mr($username)."';";
	$result = mysql_result(mysql_query($sql),0);
	if ($result > 0) {
		return 0;
	} else {
		return 1;
	}
}
function addToDraft($sendTo, $name, $subject, $absent, $percent) {
	$sql = "INSERT INTO `tblemail` (`ID`, `SendTo`, `Name`, `Subject`, `Absent`, `Percent`) VALUES (NULL, '".mr($sendTo)."', '".mr($name)."', '".mr($subject)."', '".mr($absent)."', '".mr($percent)."');";
	$result = mysql_query($sql);
	return $result;
}
//echo saveSettings("trungdq88","123","66,11,23","asd@yahoo","99");
function saveSettings($username, $session, $subjectIds, $email, $emailFreq) {
	if (!isFirstLogin($username) && checkSession($username, $session)) {
		$sql = "UPDATE  `tblusers` SET  `Email` =  '".mr($email)."',`EmailFreq` =  '".mr($emailFreq)."',`SubjectIds` =  '".mr($subjectIds)."' WHERE  `tblusers`.`Username` = '".mr($username)."';";
		$result = 0;
		$result += !mysql_query($sql);
		$sql2 = "DELETE FROM `tblatt` WHERE `tblatt`.`Username` = '".mr($username)."';";
		$result += !mysql_query($sql2);
		$arrSubjectId = explode(",",$subjectIds);
		foreach ($arrSubjectId as $r) {
			$sql3 = "INSERT INTO  `tblatt` (`ID` ,`SubjectID` ,`Username` ,`Total` ,`Absent`) VALUES (NULL ,  '".mr($r)."',  '".mr($username)."',  '0',  '0');";
			$result += !mysql_query($sql3);
		}
		return !$result;
	} else {
		return 0;
	}
}

function stopService($username, $session) {
	if (!isFirstLogin($username) && checkSession($username, $session)) {
		$sql = "DELETE FROM `tblusers` WHERE `tblusers`.`Username` = '".mr($username)."';";
		$sql2 = "DELETE FROM `tblatt` WHERE `tblatt`.`Username` = '".mr($username)."';";
		$result = mysql_query($sql);
		$result2 = mysql_query($sql2);
		return ($result && $result2);
	} else {
		return 0;
	}
}
function getUniqueCode($length = "") {	
	$code = md5(uniqid(rand(), true));
	if ($length != "") return substr($code, 0, $length);
	else return $code;
}
function putSession($username) {
	$code = getUniqueCode(32);
	$sql = "UPDATE `tblusers` SET `Session` = '".mr($code)."' WHERE `tblusers`.`Username` = '".mr($username)."';";
	$result = mysql_query($sql);
	return $code;
}

function checkSession($username, $session) {
	$sql = "SELECT `Session` FROM `tblusers` WHERE `Username` = '".mr($username)."';";
	$result = mysql_result(mysql_query($sql),0);
	return ($result == $session);
}

function checkValid($subjectIds, $email, $emailFreq) {
	$f = 0;
	if (!empty($subjectIds)) {
		$sub = explode(",", $subjectIds);
		foreach ($sub as $t) {
			if (!is_numeric($t)) {
				$f++;
			}
		}
		if (count($sub) > 5) {
			$f++;
		}
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$f++;
	}
	if (!is_numeric($emailFreq) || ((int)$emailFreq <= 0) || ((int)$emailFreq >= 5)) {
		$f++;
	}
	if ($f == 0) {
		return "YES";
	} else {
		return "Dữ liệu sai! (Lưu ý: bạn chỉ được chọn tối đa 6 môn học)";
	}
}

function sendMail($to, $subject, $message) {
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'From: no-reply@full.name.vn' . "\r\n";
	$headers .= 'X-Mailer: PHP/' . "\r\n";

	return mail($to, $subject, $message, $headers);
}

function getOldAbsent($username, $subjectId) {
	$sql = "SELECT `Absent` FROM `tblatt` WHERE `Username` = '".mr($username)."' AND `SubjectID` = ".mr($subjectId);
	$oldAbsent = (int)@mysql_result(mysql_query($sql),0);
	return $oldAbsent;
}
function setNewAbsent($username, $subjectId, $absent) {
	$sql = "UPDATE  `tblatt` SET  `Absent` =  '".mr($absent)."' WHERE  `tblatt`.`Username` = '".mr($username)."' AND `tblatt`.`SubjectID` = ".$subjectId.";";
	$result = mysql_query($sql);
	return $result;
}
function loadSettings($username) {
	$sql = "SELECT * FROM `tblusers` WHERE `Username` = '".mr($username)."'";
	$result = mysql_query($sql);
	return mysql_fetch_assoc($result);
}

function getSubjectName($id) {
	$sql = "SELECT `Name` FROM `tblsubjects` WHERE `ID` = ".$id;
	$result = mysql_query($sql);
	return @mysql_result($result, 0);
}
function getUserNumber(){
	$sql = "SELECT COUNT(*) FROM `tblusers`;";
	$u = mysql_result(mysql_query($sql),0);
	return $u;
}
function getSubjectNumber(){
	$sql = "SELECT COUNT(*) FROM `tblsubjects`;";
	$u = mysql_result(mysql_query($sql),0);
	return $u;
}
function getMailNumber(){
	$sql = "SELECT COUNT(*) FROM `tblemail` WHERE `Sent` = 1;";
	$u = (int)mysql_result(mysql_query($sql),0) + 13;
	return $u;
}
?>