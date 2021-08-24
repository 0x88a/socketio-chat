<!DOCTYPE HTML>
<html lang="en">

<head>
    <title>My Public Chat</title>
    <script src="/js/jquery.js"></script>
    <script src="/js/socket.io.js"></script>
</head>

<body>
    <label for="#msgInput">Shoutbox</label>
    <input type="text" id="msgInput" placeholder="Enter your message...">
    <button id="sendMessage">Send</button>
    <div id="shoutbox">
        <!-- Select all your messages previously sent from the SQL Table and display them -->
    </div>
</body>

</html>

<script>
    var socket = io("https://your.herokuapp.com"); // Your regular Heroku URL or Server IP with port eg. 127.0.0.1:1234

    $("#sendMessage").click(function() {
        $.ajax({
            url: '/web_server/shout.php',
            type: 'POST',
            data: {
                message: $("#msgInput").val()
            },
            dataType: 'json',
            success: function(response) {
                if (response.error == null && response.admin == null) {
                    socket.emit('message', response.message);
                    $("#msgInput").val("");
                } else if (response.admin != null) {
                    if (response.admin == "Shoutbox Cleared") {
                        socket.emit('clear', "yes");
                    }
                } else {
                    // You can display some error -- response.error
                }
            }
        });
    });

    socket.on('message', function(msg) {
        $("#shoutbox").prepend(msg + "<br>");
    });

    socket.on('clear', function clear() {
        $("#shoutbox").html("");
    });
</script>