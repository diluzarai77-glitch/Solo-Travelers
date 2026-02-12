<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$me = $_SESSION['user']['id'];
$other = $_GET['user_id'];

// Handle new message
if(isset($_POST['message'])){
    $msg = $_POST['message'];
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $me, $other, $msg);
    $stmt->execute();
    header("Location: chat.php?user_id=$other");
    exit();
}

// Fetch chat messages
$messages = $conn->query("
    SELECT m.*, u.name as sender_name 
    FROM messages m 
    JOIN users u ON u.id = m.sender_id 
    WHERE (sender_id=$me AND receiver_id=$other) 
       OR (sender_id=$other AND receiver_id=$me)
    ORDER BY m.created_at ASC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <style>
        body{font-family:sans-serif;padding:20px;}
        .chat-box{max-width:600px;margin:auto;background:#f5f5f5;padding:15px;border-radius:12px;}
        .message{padding:8px 12px;margin:5px;border-radius:10px;display:inline-block;}
        .me{background:#6a11cb;color:#fff;float:right;}
        .other{background:#ddd;color:#000;float:left;}
        .clear{clear:both;}
        form{margin-top:20px;}
        input[type=text]{width:80%;padding:10px;border-radius:8px;border:1px solid #ccc;}
        button{padding:10px 15px;background:#6a11cb;color:#fff;border:none;border-radius:8px;cursor:pointer;}
        button:hover{background:#5a0fb8;}
    </style>
</head>
<body>
<div class="chat-box">
    <h3>Chat with User #<?php echo $other; ?></h3>
    <?php while($m = $messages->fetch_assoc()): ?>
        <div class="message <?php echo ($m['sender_id']==$me)?'me':'other'; ?>">
            <?php echo htmlspecialchars($m['message']); ?>
        </div>
        <div class="clear"></div>
    <?php endwhile; ?>

    <form method="POST">
        <input type="text" name="message" placeholder="Type your message..." required>
        <button type="submit">Send</button>
    </form>
</div>
</body>
</html>
