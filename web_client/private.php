<!DOCTYPE HTML>
<html lang="en">

<head>
	<title>Private Chats!</title>
	<script src="/v3/js/jquery.js"></script>
	<script src="/js/socket.io.js"></script>
</head>

<body class="no-overflow">
	<input type="text" id="msgInput" placeholder="Enter your message...">
	<button id="sendMessage">Send</button>
	<div id="messages">
		<!-- Select all your messages previously sent from the SQL Table and display them -->
	</div>
</body>

</html>
<script>
	var id = "123123"; // This should be the ID of the chat you want to join. Can be dynamic etc
	var socket = io("https://yourapp.herokuapp.com");

	$('#privateMessageNew').click(function(ev) {
		$("#privateMessageModal").modal("show");

		socket.emit('privatechat', {
			id: id
		});

		$("#sendMessage").click(function() {
			$.ajax({
				url: '/web_server/privatemessage.php',
				type: 'POST',
				data: {
					message: $("#msgInput").val(),
					user: id
				},
				dataType: 'json',
				success: function(response) {
					if (response.error == null) {
						socket.emit('privatemsg', {
							id: id,
							message: response.message
						});
					} else if (response.error == "Cleared") {
						socket.emit('privatemsgclear', {
							id: id
						});
					} else {
						// Give some error "response.error"
					}
				}
			});
		});

		socket.on('privatemsgnew', function(data) {
			$("#messages").prepend(data.message + "<br>");
		});

		socket.on('privatemsgclear', function() {
			$("#messages").html("");
		});
	});
</script>