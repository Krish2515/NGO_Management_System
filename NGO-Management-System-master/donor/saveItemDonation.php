<?php
session_start();
require_once "../pdo.php";

if (!isset($_SESSION['donor_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $donor_id  = $_SESSION['donor_id'];
    $item      = trim($_POST['item']);
    $count     = (int) $_POST['item_count'];
    $category  = trim($_POST['category']);
    $desc      = trim($_POST['description']);

    // Validation
    if ($item == "" || $count <= 0) {
        $_SESSION['error'] = "Please enter valid item details.";
        header("Location: donateItem.php");
        exit();
    }

    $stmt = $pdo->prepare("
        INSERT INTO items (donor_id, item, item_count, category, description)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $donor_id,
        $item,
        $count,
        $category,
        $desc
    ]);

    $_SESSION['success'] = "Item donation submitted successfully ❤️";
    header("Location: donateItem.php");
    exit();
}
?>
