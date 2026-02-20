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

$indexFlag = 1;

// ✅ Total MONEY donated
$stmt = $pdo->prepare("
    SELECT SUM(amount) 
    FROM `transaction`
    WHERE donor_id = ?
");
$stmt->execute([$_SESSION['donor_id']]);
$totalMoney = $stmt->fetchColumn();
$totalMoney = $totalMoney ? $totalMoney : 0;

// ✅ Total ITEMS donated
$stmt2 = $pdo->prepare("
    SELECT SUM(item_count)
    FROM items
    WHERE donor_id = ?
");
$stmt2->execute([$_SESSION['donor_id']]);
$totalItems = $stmt2->fetchColumn();
$totalItems = $totalItems ? $totalItems : 0;
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

    <!-- Donation Summary -->
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">

            <div class="shadow-lg p-4 mb-4 bg-light rounded">

                <h3>Your Overall Donations</h3>

                <h4 class="mt-3">
                    💰 ₹ <?= number_format($totalMoney, 2) ?>
                </h4>

                <h5 class="mt-3">
                    📦 Items Donated: <?= $totalItems ?>
                </h5>

            </div>

            <a class="btn btn-primary btn-lg m-2 shadow-lg" style="width:200px;"
               href="donor/donateChoice.php">Donate</a>

            <a class="btn btn-secondary btn-lg m-2 shadow-lg" style="width:200px;"
               href="donor/transactions.php">Transaction</a>

            <a class="btn btn-info btn-lg m-2 shadow-lg" style="width:200px;"
                href="donor/itemHistory.php">Donated Items</a>

        </div>
    </div>
</div>

</body>
</html>
