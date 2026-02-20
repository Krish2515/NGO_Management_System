<?php
require_once "pdo.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* ============================
   TOTAL DONATIONS
============================ */
$totalDonation = $pdo->query("
    SELECT COALESCE(SUM(amount),0) 
    FROM transaction
")->fetchColumn();

$totalItems = $pdo->query("
    SELECT COALESCE(SUM(item_count),0) 
    FROM items
")->fetchColumn();

// Fetch money donations
$moneyStmt = $pdo->query("
    SELECT d.name AS donor_name, p.name AS project,
           t.amount, t.tdate
    FROM transaction t
    JOIN donor d ON t.donor_id = d.donor_id
    JOIN projects p ON t.project_id = p.project_id
    ORDER BY t.tdate DESC
");

$moneyDonations = $moneyStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch item donations
$itemStmt = $pdo->query("
    SELECT d.name AS donor_name, i.item, i.item_count,
           i.category, i.donated_at
    FROM items i
    JOIN donor d ON i.donor_id = d.donor_id
    ORDER BY i.donated_at DESC
");

$itemDonations = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>List of Donors</title>

    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
body { background:#a3c2c2; }

.box {
    background:white;
    border-radius:10px;
    padding:25px;
    margin-top:30px;
    box-shadow:0 0 15px rgba(0,0,0,0.15);
}

/* scrollable table */
.scroll-box {
    max-height: 300px;
    overflow-y: auto;
}

.donation-total {
    text-align: center;
    margin: 20px 0;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5">
    <a class="navbar-brand" href="javascript:void(0)">NGO</a>

    <span class="navbar-text text-white ml-3 mr-auto">
        <a href="javascript:void(0)" class="nav-link d-inline p-0 text-white">Admin</a>
    </span>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">

            <li class="nav-item mx-2">
                <a class="nav-link" href="update/adminUpdate.php">Edit Profile</a>
            </li>

            <li class="nav-item mx-2">
                <a class="nav-link" href="#">
                    <?php
                    if (isset($_SESSION['admin_id'])) {
                        $stmt3 = $pdo->query(
                            "SELECT name FROM admin WHERE admin_id = " . $_SESSION['admin_id']
                        );
                        $admin = $stmt3->fetch(PDO::FETCH_ASSOC);
                        echo htmlspecialchars($admin['name']);
                    }
                    ?>
                </a>
            </li>

            <li class="nav-item mx-2">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>

        </ul>
    </div>
</nav>



   <div class="container box">

    <div class="d-flex justify-content-between mb-3">
        <h3>All Donations</h3>
    </div>

    <div class="donation-total">
        <h4>Overall Donations : ₹ <?= number_format($totalDonation); ?></h4>
    </div>

    <div class="donation-total">
        <h4>Overall Items Donated : <?= number_format($totalItems); ?></h4>
    </div>
    
    <!-- MONEY DONATIONS -->
    <h5 class="mt-3">Money Donations</h5>

    <div class="scroll-box">
        <table class="table table-bordered table-striped text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Donor</th>
                    <th>Project</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($moneyDonations): ?>
                <?php foreach ($moneyDonations as $m): ?>
                    <tr>
                        <td><?= htmlentities($m['donor_name']) ?></td>
                        <td><?= htmlentities($m['project']) ?></td>
                        <td>₹ <?= number_format($m['amount']) ?></td>
                        <td><?= $m['tdate'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No records</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ITEM DONATIONS -->
    <h5 class="mt-4">Item Donations</h5>

    <div class="scroll-box">
        <table class="table table-bordered table-striped text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Donor</th>
                    <th>Item</th>
                    <th>Count</th>
                    <th>Category</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($itemDonations): ?>
                <?php foreach ($itemDonations as $i): ?>
                    <tr>
                        <td><?= htmlentities($i['donor_name']) ?></td>
                        <td><?= htmlentities($i['item']) ?></td>
                        <td><?= $i['item_count'] ?></td>
                        <td><?= htmlentities($i['category']) ?></td>
                        <td><?= $i['donated_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No records</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
