<?php
include_once('db.inc.php');
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
		//echo ($name[0]);
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
			//Get info
			//Return info
		} else {
			return false;
		}
	}
}

function firstTimesSetting($username, $name) {
	if (isFirstLogin($username)) {
		$sql = "INSERT INTO  `tblusers` (`ID` ,`Username` ,`Name` ,`Email` ,`EmailFreq` ,`SubjectIds` ,`Session`) VALUES (NULL ,  '".$username."',  '".$name."',  '".$username."@fpt.edu.vn',  '2',  '',  '');";
		$result = mysql_query($sql);
		return $result;
	} else {
		return "Username already exists!";
	}
}
function isFirstLogin($username) {
	$sql = "SELECT COUNT(*) FROM `tblusers` WHERE `Username` = '".$username."';";
	$result = mysql_result(mysql_query($sql),0);
	if ($result > 0) {
		return 0;
	} else {
		return 1;
	}
}
function addToDraft($sendTo, $name, $subject, $absent, $percent) {
	$sql = "INSERT INTO `tblemail` (`ID`, `SendTo`, `Name`, `Subject`, `Absent`, `Percent`) VALUES (NULL, '".$sendTo."', '".$name."', '".$subject."', '".$absent."', '".$percent."');";
	$result = mysql_query($sql);
	return $result;
}
//echo saveSettings("trungdq88","123","15,5,5","4","5");
function saveSettings($username, $session, $subjectIds, $email, $emailFreq) {
	if (!isFirstLogin($username) && checkSession($username, $session)) {
		$sql = "UPDATE  `tblusers` SET  `Email` =  '".$email."',`EmailFreq` =  '".$emailFreq."',`SubjectIds` =  '".$subjectIds."' WHERE  `tblusers`.`Username` = '".$username."';";
		$result = mysql_query($sql);
		return $result;
	} else {
		return 0;
	}
}

function stopService($username, $session) {
	if (!isFirstLogin($username) && checkSession($username, $session)) {
		$sql = "DELETE FROM `tblusers` WHERE `tblusers`.`Username` = '".$username."';";
		$result = mysql_query($sql);
		return $result;
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
	$sql = "UPDATE `tblusers` SET `Session` = '".$code."' WHERE `tblusers`.`Username` = '".$username."';";
	$result = mysql_query($sql);
	return $code;
}

function checkSession($username, $session) {
	$sql = "SELECT `Session` FROM `tblusers` WHERE `Username` = '".$username."';";
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
echo sendMail("trungdq88@gmail.com","Hello!","This is a test mail!");
function sendMail($to, $subject, $content) {
$to      = $to;
$subject = $subject;
$message = $content;
$headers = 'From: no-reply@full.name.vn' . "\r\n" .
    'Reply-To: no-reply@full.name.vn' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

return mail($to, $subject, $message, $headers);
}
?>