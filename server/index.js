const cors = require('cors');
const app = require('express')();
const http = require('http').Server(app);
const mysql = require('mysql');
const io = require('socket.io')(http, {
allowUpgrades: false,
	transports: ["polling"],
	cors: {
		origin: "https://example.com"
	},
});

const port = process.env.PORT || 3000; // For Heroku Hosting (you can change it to a static port)

//Initalise our SQL DB
const connection = mysql.createConnection(	{
	host: 'host',
	port: '3306',
	user: 'username',
	password: 'password',
	database: 'database'
});

connection.connect();

io.on('connection', (socket) => {
	socket.on('message', msg => {
		// Get the most recent message from the Shoutbox and validate it has been sent.
		connection.query('SELECT * FROM shoutbox ORDER BY id DESC LIMIT 1', function (err, rows, fields) {
			if (rows != undefined) {
				// Validate the message sent to be emitted is the same as the one in the database.
				if (msg == rows[0].message) {
					io.emit('message', msg);
				}
			}
		});
	});

	// See "privatemsgclear" on how to validate regular clears.
	socket.on('clear', function clear() {
		io.emit('clear');
	});

	socket.on('privatechat', function (data) {
		socket.join(data.id);
	});
	
	// Same as a regular message, but emit in specific room.
	socket.on('privatemsg', function (data) {
		connection.query('SELECT * FROM privatemessages ORDER BY id DESC LIMIT 1', function (err, rows, fields) {
			if (rows[0] != undefined) {
				if (rows[0].message == data.message && rows[0].recipient == data.id) {
					io.sockets.in(data.id).emit('privatemsgnew', { message: data.message });
				}
			}
		});
	});
	
	
	// Validate a "clear" message has been sent and clear.
	socket.on('privatemsgclear', function (data) {
		connection.query('SELECT * FROM privatemessages ORDER BY id DESC LIMIT 1', function (err, rows, fields) {
			if (rows[0] != undefined) {
				if (rows[0].message == "Console: Cleared" && rows[0].recipient == data.id) {
					connection.query('DELETE FROM privatemessages ORDER BY id DESC LIMIT 1');
					io.sockets.in(data.id).emit('privatemsgclear', { message: data.message });
				}
			}
		});
	});
});

http.listen(port, () => {
	console.log(`Socket.IO server running at http://localhost:${port}/`);
});

