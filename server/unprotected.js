const cors = require('cors');
const app = require('express')();
const http = require('http').Server(app);
const io = require('socket.io')(http, {
	allowUpgrades: false,
	transports: ["polling"],
	cors: {
		origin: "*"
	},
});

const port = process.env.PORT || 3000; // For Heroku Hosting (you can change it to a static port)


io.on('connection', (socket) => {
	console.log("client connected");
	socket.on('message', msg => {
		io.emit('message', msg);
		console.log(msg);
	});

	socket.on('clear', function clear() {
		io.emit('clear');
		console.log("Cleared");
	});
});

http.listen(port, () => {
	console.log(`Socket.IO server running at http://localhost:${port}/`);
});	

