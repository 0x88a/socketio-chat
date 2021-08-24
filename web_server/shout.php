<?php
// This uses my Database wrapper- you can change it to your own.
// https://github.com/Jefff000/db-wrapper

require 'core.php';

$res = new \stdClass();

if (strlen($_POST['message']) > 3) {
	// Split their message into individual strings.
	// More useful in future, if you want to - for example - do %mute username in your application
	$params = preg_split('/\s+/', $_POST['message'], -1, PREG_SPLIT_NO_EMPTY);
	if ($params[0] == "%clear") {
		$core->query('DELETE FROM shoutbox');
		$res->admin = "Shoutbox Cleared";
	} else {
		$res->message = $core->escape($_POST['message']); // Escape our messsage :)
		$core->query('INSERT INTO shoutbox (user, message) VALUES (?, ?)', $_SESSION['id'], $res->message); // Session ID is the user identifier, you can insert your own here.
	}
} else {
	$res->error = "Message too short";
}

die(json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
