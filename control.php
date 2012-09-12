<?php
include_once('func.inc.php');
if (isset($_POST['do'])) {
	$do = $_POST['do'];	
	$username = $_POST['username'];
	switch ($do) {
	case 'login':
		//Dữ liệu giả --------------------------
			$data[0] = "Tên $username"; //Name
			$data[1] = "SeSsIoNiD";   //Session
			$data = json_encode($data);
			echo $data;
			die();
		//Dữ liệu giả --------------------------
		$password = $_POST['password'];
		$result = checkLogin($username, $password);
		if ($result) {
			if (isFirstLogin($username)) {
				firstTimesSetting($username, $result);
			}
			$sess = putSession($username);
			$data[0] = $result; //Name
			$data[1] = $sess;   //Session
			$data = json_encode($data);
		} else {
			$data = 0;
		}
		//Trả về $result và $sess
		echo $data;
		die();
	case 'savesetting':
		$session = $_POST['session'];
		if (checkSession($username, $session)) {
			$subjectIds = $_POST['subjectids'];
			$email = $_POST['email'];
			$emailFreq = $_POST['emailfreq'];
			$valid = checkValid($subjectIds, $email, $emailFreq);
			if ($valid == "YES") {
				saveSettings($username, $session, $subjectIds, $email, $emailFreq);
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