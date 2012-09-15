<?php
include_once('func.inc.php');
if (isset($_POST['do'])) {
	$do = $_POST['do'];	
	$username = $_POST['username'];
	switch ($do) {
	case 'login':
		$password = $_POST['password'];
		
		//Giả sử checkLogin = 1;
		//$result = 1;
		$result = @checkLogin($username, $password);
		if ($result) {
			if (isFirstLogin($username)) {
				firstTimesSetting($username, $result, $password);
			}
			$sess = putSession($username);
			$data = loadSettings($username);
			$data = json_encode($data);
		} else {
			$data = 0;
		}
		//Trả về $result và $sess
		echo $data;
		die();
	case 'savesetting':
		$session = $_POST['session'];
		if (@checkSession($username, $session)) {
			$subjectIds = $_POST['subjectids'];
			$email = $_POST['email'];
			$emailFreq = $_POST['emailfreq'];
			$valid = (string)checkValid($subjectIds, $email, $emailFreq);
			if ($valid == "YES") {
				saveSettings($username, $session, $subjectIds, $email, $emailFreq);
			}
			echo $valid;
		} else {
			echo "Không đúng Session!\nVui lòng đăng nhập lại.";
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