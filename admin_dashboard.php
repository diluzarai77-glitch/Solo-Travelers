<?php
include 'db.php';
session_start();

/* SECURITY CHECK */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

/* FETCH DATA */
$users = $conn->query("SELECT id, name, email, role FROM users");
$trips = $conn->query("
    SELECT trips.id, users.name, trips.destination, trips.start_date, trips.end_date
    FROM trips
    JOIN users ON trips.user_id = users.id
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; background:#f4f6f9; margin:0; }
        header {
            background:#1f2937;
            color:#fff;
            padding:15px 30px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }
        a { color:white; text-decoration:none; }

        .container { padding:30px; }
        h2 { margin-top:40px; }

        table {
            width:100%;
            border-collapse:collapse;
            background:#fff;
            margin-top:15px;
        }
        th, td {
            padding:12px;
            border-bottom:1px solid #ddd;
            text-align:left;
        }
        th { background:#f1f5f9; }

        .btn {
            padding:6px 12px;
            border:none;
            border-radius:6px;
            cursor:pointer;
        }
        .delete {
            background:#ef4444;
            color:white;
        }
    </style>
</head>
<body>

<header>
    <h2>Admin Panel – SoloTravel</h2>
    <a href="logout.php">Logout</a>
</header>

<div class="container">

    <!-- USERS -->
    <h2>👤 All Users</h2>
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Action</th>
        </tr>
        <?php while($u = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role'] ?></td>
            <td>
                <?php if($u['role'] !== 'admin'): ?>
                <a class="btn delete" href="delete_user.php?id=<?= $u['id'] ?>" 
                   onclick="return confirm('Delete user?')">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- TRIPS -->
    <h2>🧭 All Trips</h2>
    <table>
        <tr>
            <th>ID</th><th>User</th><th>Destination</th>
            <th>Start</th><th>End</th><th>Action</th>
        </tr>
        <?php while($t = $trips->fetch_assoc()): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= htmlspecialchars($t['name']) ?></td>
            <td><?= htmlspecialchars($t['destination']) ?></td>
            <td><?= $t['start_date'] ?></td>
            <td><?= $t['end_date'] ?></td>
            <td>
                <a class="btn delete" href="delete_trip.php?id=<?= $t['id'] ?>"
                   onclick="return confirm('Delete trip?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>
</body>
</html>
