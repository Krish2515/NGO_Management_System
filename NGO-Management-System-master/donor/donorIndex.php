<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "pdo.php";

// Redirect if not logged in
if (!isset($_SESSION['donor_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch donor name
$stmt = $pdo->prepare("SELECT name FROM donor WHERE donor_id = ?");
$stmt->execute([$_SESSION['donor_id']]);
$donor = $stmt->fetch(PDO::FETCH_ASSOC);
$donor_name = $donor ? $donor['name'] : 'Donor';
$indexFlag=1;
// Fetch total donations from `transaction` table
$stmt = $pdo->prepare("
    SELECT SUM(amount) AS total_donations
    FROM transaction
    WHERE donor_id = ?
");
$stmt->execute([$_SESSION['donor_id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total_donations = $result['total_donations'] !== null ? (float)$result['total_donations'] : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Donor Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #a3c6c4;
        }
        .dashboard-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .donation-banner img {
            max-height: 400px;
            object-fit: cover;
        }
    </style>
</head>
<body>


<?php require_once "donorNev.php"; ?>


<!-- Main Container -->
<div class="container dashboard-box">

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success text-center">
            <?= htmlentities($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center">
            <?= htmlentities($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Banner -->
    <div class="row mb-4 donation-banner">
        <div class="col-12 text-center">
            <img src="images/index/donate.jpg" alt="Donate" 
                 class="img-fluid rounded shadow-lg">
        </div>
    </div>

    <!-- Donation Section -->
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <h2 class="shadow-lg p-3 mb-4 bg-light rounded">
                Your Overall Donations Are ₹ <?= htmlentities(number_format($total_donations, 2)) ?>
            </h2>
            <a class="btn btn-primary btn-lg m-2 shadow-lg" style="width:200px;" 
               href="donor/donateMoney.php">Donate</a>
            <a class="btn btn-secondary btn-lg m-2 shadow-lg" style="width:200px;" 
                href="donor/transactions.php">Transaction</a>

        </div>
    </div>
</div>

</body>
</html>
