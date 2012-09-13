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
	foreach ($arrID as $id) {
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
			array_push($arrSubject ,array('ID' => $id, 'absent' => $absent[0], 'total' => $total[0]));
		} else {
			//return false;
		}
	}
	return $arrSubject;
}

function firstTimesSetting($username, $name, $password) {

	if (isFirstLogin($username)) {		
		$password = encrypt($password);
		$sql = "INSERT INTO  `tblusers` (`ID` ,`Username` ,`Name` ,`Email` ,`EmailFreq` ,`SubjectIds` ,`Session` ,`Password`) VALUES (NULL ,  '".mr($username)."',  '".mr($name)."',  '".mr($username)."@fpt.edu.vn',  '2',  '',  '', '".mr($password)."');";
		$result = mysql_query($sql);
		return $result;
	} else {
		return "Username already exists!";
	}
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
	$sub = explode(",",$subjectIds);
	$f = 0;
	foreach ($sub as $t) {
		if (!is_numeric($t)) {
			$f++;
		}
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$f++;
	}
	if (!is_numeric($emailFreq) || ((int)$emailFreq <= 0) || ((int)$emailFreq >= 5)) {
		$f++;
	}
	return ($f == 0);
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

?>