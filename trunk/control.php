<?php
include_once('func.inc.php');
if (isset($_POST['do'])) {
	$do = $_POST['do'];	
	$username = $_POST['username'];
	switch ($do) {
	case 'login':
		$password = $_POST['password'];
		$result = checkLogin($username, $password);
			if (isFirstLogin($username)) {
				firstTimesSetting($username);
			}
		echo $result;
		die();
	case 'savesetting':
		$session = $_POST['session'];
		if (checkSession($username, $session)) {
			$subjectIds = $_POST['subjectids'];
			$email = $_POST['email'];
			$emailFreq = $_POST['emailfreq'];
			$valid = checkValid($subjectIds, $email, $emailFreq);
			if ($valid == "YES") {
				saveSettings($username, $subjectIds, $email, $emailFreq);
			}
			echo $valid;
		} else {
			echo "Wrong password!";
		}
		die();
	case 'stopservice':
		$session = $_POST['session'];
		if (checkSession($username, $session)) {
			if (stopService($username)) {
				echo 1;
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
		die();
	}
}
?>