<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$trips = $conn->query("SELECT * FROM trips WHERE user_id='$user_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Solo Travel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0; top: 0;
            width: 100%; height: 100%;
            overflow: auto;
            background: rgba(0,0,0,0.5);
        }
        .modal-content {
            background: #fff;
            margin: 8% auto;
            padding: 25px 30px;
            border-radius: 16px;
            width: 400px;
            position: relative;
            animation: fadeInUp 0.5s ease;
            box-shadow: 0 12px 28px rgba(0,0,0,0.2);
        }
        .close-btn {
            position: absolute;
            top: 12px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #555;
        }
        .close-btn:hover { color: #6a11cb; }

        .modal-content input, .modal-content button {
            width: 100%;
            padding: 10px 12px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            transition: 0.3s;
        }
        .modal-content button {
            margin-top: 20px;
            background: #6a11cb;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .modal-content button:hover {
            background: #5a0fb8;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        @keyframes fadeInUp { 
            0%{opacity:0; transform:translateY(20px);} 
            100%{opacity:1; transform:translateY(0);} 
        }

        /* CHAT MODAL */
.chat-modal-content {
    width: 400px;      
    max-height: 500px;
    display: flex;
    flex-direction: column;
    padding: 15px;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

/* Chat messages box */
.chat-box {
    flex: 1;
    background: #e5ddd5;
    border-radius: 12px;
    padding: 10px;
    overflow-y: auto;
    margin-bottom: 10px;
}

/* Single message bubble */
.message {
    padding: 8px 14px;
    margin: 6px 0;
    border-radius: 20px;
    max-width: 75%;
    word-wrap: break-word;
    position: relative;
}

.me {
    background: #dcf8c6;
    align-self: flex-end;
    text-align: right;
}

.other {
    background: #fff;
    align-self: flex-start;
    text-align: left;
}

.message .msg-time {
    font-size: 10px;
    color: #555;
    position: absolute;
    bottom: 2px;
    right: 8px;
}

/* Chat input form */
#chatForm {
    display: flex;
    gap: 10px;
    margin: 0;
}

#chatForm input {
    flex: 1;              
    padding: 10px 15px;
    border-radius: 25px;
    border: 1px solid #ccc;
    outline: none;
    font-size: 14px;
}

#chatForm button {
    padding: 10px 18px;
    border-radius: 25px;
    background: #128c7e;
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
}

#chatForm button:hover {
    background: #075e54;
}

/* Scrollbar for chat */
.chat-box::-webkit-scrollbar {
    width: 6px;
}

.chat-box::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,0.2);
    border-radius: 3px;
}


    </style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <!-- Left: Logo -->
    <div class="logo-container">
        <img src="images/logo.png" alt="SoloTravel Logo" class="logo">
        <h2>SoloTravel</h2>
    </div>

    <!-- Center: Slogan -->
    <div class="slogan">
        Solo but never alone
    </div>

    <!-- Right: Buttons -->
    <div class="nav-links">
        <button class="btn-primary" onclick="openTripModal('')">+ Add Trip</button>
        <a href="logout.php" class="btn-ghost" style="color:Black">Logout</a>
    </div>
</div>

<div class="dashboard-container fadeIn">

    <!-- EXPLORE -->
    <h2 class="section-title">🌍 Explore Popular Destinations</h2>

    <div class="destination-grid">
        <?php
        $places = [
            ["Annapurna Base Camp","Classic Himalayan trekking route.","annapurna.jpg"],
            ["Everest Base Camp","World’s highest trekking destination.","everest.jpg"],
            ["Pokhara","Lakes, mountains & adventure sports.","pokhara.jpg"],
            ["Langtang Valley","Scenic valley trek near Kathmandu.","langtang.jpg"]
        ];
        foreach($places as $p):
        ?>
        <div class="destination-card">
            <img src="images/<?php echo $p[2]; ?>">
            <div class="destination-info">
                <h3><?php echo $p[0]; ?></h3>
                <p><?php echo $p[1]; ?></p>
                <button class="card-btn" onclick="openTripModal('<?php echo $p[0]; ?>')">Plan Trip →</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- USER TRIPS -->
    <h2 class="section-title">🧭 Your Trips & Matches</h2>

    <?php if($trips->num_rows > 0): ?>
        <?php while($trip = $trips->fetch_assoc()): ?>
            <div class="trip-card slideUp">
                <div class="trip-header">
                    <h3><?php echo $trip['destination']; ?></h3>
                    <span class="trip-date"><?php echo $trip['start_date']; ?> → <?php echo $trip['end_date']; ?></span>
                </div>
                <p class="trip-activity">🎒 <?php echo $trip['activity_type']; ?></p>
                <div class="matches">
                    <strong>Matched Travelers</strong>
                    <div class="match-list">
                    <?php
                    $destination = $trip['destination'];
                    $start = $trip['start_date'];
                    $end = $trip['end_date'];
                    $match_sql = "
                        SELECT users.id, users.name 
                        FROM trips 
                        JOIN users ON trips.user_id = users.id 
                        WHERE trips.destination='$destination'
                        AND trips.user_id != '$user_id'
                        AND trips.start_date <= '$end'
                        AND trips.end_date >= '$start'
                    ";
                    $matches = $conn->query($match_sql);
                    if($matches->num_rows > 0){
                        while($m = $matches->fetch_assoc()){
                            echo "<span class='match-pill'>
                                    👤 {$m['name']} 
                                    <button class='chat-btn' 
                                            data-userid='{$m['id']}' 
                                            data-username='".htmlspecialchars($m['name'])."' 
                                            onclick='openChatModal(this)'>💬 Chat</button>
                                  </span>";
                        }
                    } else {
                        echo "<span class='no-match'>No matches yet</span>";
                    }
                    ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="empty-text">You haven’t planned any trips yet.</p>
    <?php endif; ?>

</div>

<!-- PLAN TRIP MODAL -->
<div id="tripModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeTripModal()">&times;</span>
        <h2>Plan Your Trip</h2>
        <form method="POST" action="add_trip.php">
            <label>Destination</label>
            <input type="text" name="destination" id="modalDestination" placeholder="Enter destination" required>
            <label>Start Date</label>
            <input type="date" name="start_date" required>
            <label>End Date</label>
            <input type="date" name="end_date" required>
            <button type="submit">Save Trip</button>
        </form>
    </div>
</div>

<!-- CHAT MODAL -->
<div id="chatModal" class="modal">
    <div class="modal-content chat-modal-content">
        <span class="close-btn" onclick="closeChatModal()">&times;</span>
        <h3 id="chatWith">Chat</h3>

        <!-- Chat Messages -->
        <div id="chatBox" class="chat-box"></div>

        <!-- Input form -->
        <form id="chatForm" autocomplete="off">
            <input type="text" id="chatMessage" placeholder="Type a message..." required>
            <button type="submit">Send</button>
        </form>
    </div>
</div>




<script>
// ----- Trip Modal -----
let tripModal = document.getElementById('tripModal');

function openTripModal(destination = '') {
    document.getElementById('modalDestination').value = destination;
    tripModal.style.display = 'block';
}

function closeTripModal() {
    tripModal.style.display = 'none';
}

// ----- Chat Modal -----
let chatModal = document.getElementById('chatModal');
let chatBox = document.getElementById('chatBox');
let chatWith = document.getElementById('chatWith');
let chatUserId = null;
let chatUserName = "";

function openChatModal(btn){
    chatUserId = btn.getAttribute('data-userid');
    chatUserName = btn.getAttribute('data-username');
    chatWith.innerText = "Chat with " + chatUserName;
    chatModal.style.display = 'block';
    chatBox.innerHTML = "<p style='text-align:center;color:#888;'>Loading messages...</p>";
    loadMessages();
}

function closeChatModal(){
    chatModal.style.display = 'none';
    chatBox.innerHTML = '';
}

// Close modals if clicked outside
window.onclick = function(event){
    if(event.target == tripModal) closeTripModal();
    if(event.target == chatModal) closeChatModal();
}

// ----- Chat functions -----
function loadMessages(){
    if(!chatUserId) return;
    fetch('chat_ajax.php?user_id=' + chatUserId)
        .then(res => res.text())
        .then(data => {
            chatBox.innerHTML = data;
            chatBox.scrollTop = chatBox.scrollHeight;
        });
}
setInterval(loadMessages, 2000);

document.getElementById('chatForm').addEventListener('submit', function(e){
    e.preventDefault();
    let msg = document.getElementById('chatMessage').value;
    if(msg.trim() == "") return;
    fetch('chat_ajax.php?user_id=' + chatUserId, {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'message=' + encodeURIComponent(msg)
    }).then(res=>{
        document.getElementById('chatMessage').value='';
        loadMessages();
    });
});
</script>

</body>
</html>
