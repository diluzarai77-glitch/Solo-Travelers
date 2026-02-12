<?php
session_start();
include "db.php";

/* 1️⃣ Check login */
if(!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

/* 2️⃣ Handle form submission */
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destination = trim($_POST['destination'] ?? '');
    $start_date  = $_POST['start_date'] ?? '';
    $end_date    = $_POST['end_date'] ?? '';

    // Basic validation
    if($destination=="" || $start_date=="" || $end_date=="") {
        $error = "All fields are required.";
    } elseif($start_date > $end_date) {
        $error = "Start date cannot be after end date.";
    } else {
        // Prevent duplicate trip
        $check = $conn->prepare("SELECT id FROM trips WHERE user_id=? AND destination=? AND start_date=? AND end_date=?");
        $check->bind_param("isss", $user_id, $destination, $start_date, $end_date);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0) {
            $error = "You already planned this trip.";
        } else {
            // Insert trip
            $stmt = $conn->prepare("INSERT INTO trips (user_id, destination, start_date, end_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $destination, $start_date, $end_date);
            if($stmt->execute()) {
                header("Location: dashboard.php?success=trip_added");
                exit;
            } else {
                $error = "Database error. Try again.";
            }
        }
    }
}

/* 3️⃣ Pre-fill destination from GET parameter */
$prefill_destination = $_GET['place'] ?? '';
?>
