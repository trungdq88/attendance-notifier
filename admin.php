<?php
include_once('func.inc.php');

function sendNewBlockMail() {
	$block = "Block 1";
	$nextBlock = "Block 2";
	$term = "Fall 2012";

	$sql = "SELECT `Username`, `Name`, `Email` FROM `tblusers` WHERE 1";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc($result)) {
		$text = sendNotifMail($block, $nextBlock, $term, $row['Email'], $row['Name'], $row['Username']);
	}
	return $text;
}

echo sendNewBlockMail();
?>