<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Check if beneficiary ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Missing beneficiary ID.";
    header("Location: viewBeneficiaries.php");
    exit;
}

$id = $_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $city = $_POST['city'] ?? '';
    $project_id = $_POST['project_id'] ?? '';

    $stmt = $pdo->prepare("UPDATE beneficiaries SET name = ?, age = ?, gender = ?, contact = ?, city = ?, project_id = ? WHERE id = ?");
    $stmt->execute([$name, $age, $gender, $contact, $city, $project_id, $id]);

    $_SESSION['success'] = "Beneficiary updated successfully.";
    header("Location: viewBeneficiaries.php");
    exit;
}

// Fetch beneficiary data
$stmt = $pdo->prepare("SELECT * FROM beneficiaries WHERE id = ?");
$stmt->execute([$id]);
$beneficiary = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$beneficiary) {
    $_SESSION['error'] = "Beneficiary not found.";
    header("Location: viewBeneficiaries.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Beneficiary</title>
    <?php include("bootstrap.php"); ?>
</head>
<body>
<div class="container mt-5">
    <h2>Edit Beneficiary</h2>
    <form method="POST">
        <div class="form-group">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($beneficiary['name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Age:</label>
            <input type="number" name="age" class="form-control" value="<?= $beneficiary['age'] ?>" required>
        </div>
        <div class="form-group">
            <label>Gender:</label>
            <select name="gender" class="form-control" required>
                <option value="Male" <?= $beneficiary['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $beneficiary['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $beneficiary['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label>Contact:</label>
            <input type="text" name="contact" class="form-control" value="<?= $beneficiary['contact'] ?>" required>
        </div>
        <div class="form-group">
            <label>City:</label>
            <input type="text" name="city" class="form-control" value="<?= $beneficiary['city'] ?>" required>
        </div>
        <div class="form-group">
            <label>Project ID:</label>
            <input type="number" name="project_id" class="form-control" value="<?= $beneficiary['project_id'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Beneficiary</button>
        <a href="viewBeneficiaries.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
