<?php
include_once('func.inc.php');

function sendNewBlockMail() {
	$block = "Block 6";
	$nextBlock = "Block 1";
	$term = "Fall 2012";
	$nextTerm = "Spring 2013";

	$sql = "SELECT `Username`, `Name`, `Email` FROM `tblusers` WHERE 1";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_assoc($result)) {
		$text = sendNotifMail($block, $nextBlock, $term, $nextTerm, $row['Email'], $row['Name'], $row['Username']);
	}
	return $text;
}

echo sendNewBlockMail();
?>