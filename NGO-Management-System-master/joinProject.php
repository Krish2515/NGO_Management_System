<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['volunteer_id'])) {
    $_SESSION['error'] = "Please log in.";
    header("Location: login.php");
    exit();
}

if (isset($_POST['project_id'])) {
    $volunteer_id = $_SESSION['volunteer_id'];
    $project_id = $_POST['project_id'];

    // ✅ Fix table name here
    $stmt = $pdo->prepare("SELECT * FROM volunteer_project WHERE volunteer_id = ? AND project_id = ?");
    $stmt->execute([$volunteer_id, $project_id]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "You already joined this project.";
    } else {
        // ✅ Fix table name here too
        $stmt = $pdo->prepare("INSERT INTO volunteer_project (volunteer_id, project_id) VALUES (?, ?)");
        $stmt->execute([$volunteer_id, $project_id]);
        $_SESSION['success'] = "Successfully joined the project.";
    }
}

header("Location: volunteerIndex.php");
exit();
