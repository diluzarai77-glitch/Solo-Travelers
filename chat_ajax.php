<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user'])) exit;

$me = $_SESSION['user']['id'];
$other = intval($_GET['user_id']);

// Send message if POST
if(isset($_POST['message'])){
    $msg = $_POST['message'];
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $me, $other, $msg);
    $stmt->execute();
    exit;
}

// Fetch messages
$messages = $conn->query("
    SELECT m.*, u.name as sender_name 
    FROM messages m 
    JOIN users u ON u.id = m.sender_id 
    WHERE (sender_id=$me AND receiver_id=$other) 
       OR (sender_id=$other AND receiver_id=$me)
    ORDER BY m.created_at ASC
");

while($m = $messages->fetch_assoc()){
    $class = ($m['sender_id'] == $me) ? 'me' : 'other';
    echo "<div class='message $class'>".htmlspecialchars($m['message'])."</div>";
}
?>
