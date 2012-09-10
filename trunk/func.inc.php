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
		saveName($username, $name[0]);
		return putSession($username);
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

function firstTimesSetting($username) {
	
}
function isFirstLogin($username) {
	
}
function checkValid($subjectIds, $email, $emailFreq) {
	
}
function sendMail($to, $content) {
	
}
function addToDraft($sendTo, $name, $subject, $absent, $percent) {
	
}
function saveSettings($username, $subjectIds, $email, $emailFreq) {
	
}
function stopService($username) {
	
}
function putSession($username) {
	
}
function getName($username) {
	
}
function saveName($username, $name) {
	
}
function checkSession($username, $session) {
	
}
?>