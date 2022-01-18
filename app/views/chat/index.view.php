<?php

use App\Core\Auth;
use App\Core\Request;
use App\Core\App;

?>
<!DOCTYPE html>
<html>

<head>
    <title>Socket.IO chat</title>
    <style>
        body {
            margin: 0;
            padding-bottom: 3rem;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        #chat-form {
            background: rgba(0, 0, 0, 0.15);
            padding: 0.25rem;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            height: 3rem;
            box-sizing: border-box;
            backdrop-filter: blur(10px);
        }

        #msg {
            border: none;
            padding: 0 1rem;
            flex-grow: 1;
            border-radius: 2rem;
            margin: 0.25rem;
        }

        #msg:focus {
            outline: none;
        }

        #chat-form>button {
            background: #333;
            border: none;
            padding: 0 1rem;
            margin: 0.25rem;
            border-radius: 3px;
            outline: none;
            color: #fff;
        }

        #chat-messages {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        #chat-messages>li {
            padding: 0.5rem 1rem;
        }

        #chat-messages>li:nth-child(odd) {
            background: #efefef;
        }
    </style>
</head>

<body>
    <header class="chat-header">
        <h1><i class="fas fa-smile"></i> ChatCord</h1>
        <a id="leave-btn" class="btn">Leave Room</a>
    </header>
    <main class="chat-main">
        <div class="chat-sidebar">
            <h3><i class="fas fa-comments"></i> Room Name:</h3>
            <h2 id="room-name">TestRoom</h2>
            <h3><i class="fas fa-users"></i> Users</h3>
            <ul id="users"></ul>
        </div>
        <div class="chat-messages"></div>
    </main>
    <form id="chat-form">
        <input type="text" id="msg" autocomplete="off" required /><button>Send</button>
    </form>

    <script src="http://localhost:3000/socket.io/socket.io.js"></script>
    <script src="<?= App::get('base_url') ?>/app/views/chat/chatmain.js"></script>
</body>

</html>