<div align="center">
  <h1>Socket.io Shoutbox with MySQL Validation</h1>
  <p>
    <strong>The purpouse of this demonstration is to allow users to enter messages in either a public or private chat. These messages are then 
    added to a MySQL Database aswell as being sent off to a socket.io server; the server then checks the data being sent against the MySQL Database.
    A fairly easy way of preventing malicious messages being sent manually.</strong>
  </p>
  <p>
  </p>
</div>

## Flow
![flow](https://i.imgur.com/SbdhOIo.png)

## Dependencies:
```
[dependencies]
  "cors": "^2.8.5",
  "express": "^4.17.1",
  "mysql": "^2.18.1",
  "querystring": "^0.2.1",
  "socket.io": "^4.1.3"
```
