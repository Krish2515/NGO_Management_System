<?php
require_once "pdo.php";
session_start();

if (!isset($_POST['image_id'], $_POST['project_id'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: adminProject.php");
    exit;
}

$image_id = $_POST['image_id'];
$project_id = $_POST['project_id'];

// Fetch image path
$stmt = $pdo->prepare("SELECT image_path FROM project_images WHERE id = ?");
$stmt->execute([$image_id]);
$image = $stmt->fetch(PDO::FETCH_ASSOC);

if ($image) {
    $image_path = $image['image_path'];

    // Delete file if it exists
    if (!empty($image_path) && file_exists($image_path)) {
        unlink($image_path);
    }

    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM project_images WHERE id = ?");
    $stmt->execute([$image_id]);

    $_SESSION['success'] = "Image removed successfully.";
} else {
    $_SESSION['error'] = "Image not found.";
}

header("Location: editProject.php?id=" . $project_id);
exit;
