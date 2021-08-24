<?php
require 'core.php'; // this checks session info

$res = new \stdClass();

if (strlen($_POST['message']) > 3) {
	if ($_POST["message"] == "%clear") {
		$core->query("DELETE FROM privatemessages WHERE recipient = ?", $_POST["user"]); // Clear messages for specific chat
		$core->query('INSERT INTO privatemessages (message, sender, recipient) VALUES (?, ?, ?)', "Console: Cleared", $_SESSION['id'], $_POST['user']); // Insert Cleared for validation in server
		$res->error = "Cleared";
	} else {
		$res->message = $core->escape($_POST['message']);
		$core->query('INSERT INTO privatemessages (message, sender, recipient) VALUES (?, ?, ?)', $res->message, $_SESSION['id'], $_POST['user']);
	}
} else {
	$res->error = "Message too short";
}

die(json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
