<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);
$conn->query("DELETE FROM trips WHERE id=$id");

header("Location: admin_dashboard.php");
