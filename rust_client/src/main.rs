extern crate sys_info;
use rust_socketio::{Payload, Socket, SocketBuilder};
use serde_json::json;
use std::io::*;
use sys_info::hostname;

/*
	For some unknown reason, the rust_socketio crate doesn't interact with
	websockets hosted on Heroku well, where it doesn't upgrade the connection.
	See: https://github.com/1c3t3a/rust-socketio/issues/67
*/
fn main() {
    let callback = |payload: Payload, mut socket: Socket| {
        match payload {
            Payload::String(str) => println!("{}", str[1..str.len() - 1].to_string()),
            Payload::Binary(bin_data) => println!("{:?}", bin_data),
        }
    };

    let mut username = hostname().unwrap();

    // Here you want to define your socket.io server; I have mine hosted on Heroku, you can have yours wherever :)
    let mut socket = SocketBuilder::new("https://your.herokuapp.com")
        .on("message", callback)
        .on("error", |err, _| eprintln!("Error: {:#?}", err))
        .connect()
        .expect("Connection failed");

        println!("Shoutbox Live!");
        while (true) {
            let mut input = String::new();
            let _input = stdin().read_line(&mut input).ok().expect("Failed to read line");
            // Remove \r\n from user input
            input = input[0..input.len() - 2].to_string();
            // Create our full message
            input = format!("{:?}: {}", username, input);

            socket
                .emit("message", json!(input))
                .expect("Server unreachable");
        }
}