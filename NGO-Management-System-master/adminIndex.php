<?php
require_once "pdo.php";
//session_start(); // Ensure session is started for admin name to work

// Calculate total donations
$stmtTotal = $pdo->query("
    SELECT COALESCE(SUM(amount), 0) AS donation_total
    FROM transaction
");
$totalRow = $stmtTotal->fetch(PDO::FETCH_ASSOC);
$totalDonation = $totalRow['donation_total'];

// Fetch all donors with city, donation, and project names
$stmt = $pdo->query("
    SELECT d.donor_id, d.name AS donor_name, c.cname AS city, 
           p.name AS project_name,
           SUM(t.amount) AS amount
    FROM donor d
    JOIN city c ON d.city_id = c.city_id
    JOIN transaction t ON d.donor_id = t.donor_id
    JOIN projects p ON t.project_id = p.project_id
    GROUP BY d.donor_id, d.name, c.cname, p.name
    ORDER BY c.cname, d.name, p.name
");


$donors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>List of Donors</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #a3c2c2;}
        .card-box {
            background: white;
            border-radius: 8px;
            padding: 20px;
            max-width: 1000px;
            margin: 40px auto;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }
        .table thead {
            background-color: #343a40;
            color: white;
        }
        .donation-total {
            text-align: center;
            font-weight: bold;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

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


<div class="container mt-5">
    <div class="card shadow-lg rounded">
        <div class="card-body">

            <!-- Heading -->
            <h3 class="text-center fw-bold mb-3">List of Donors</h3>

            <!-- Total Donation -->
           <div class="donation-total"> The Overall Donations Are ₹ <?php echo number_format($totalDonation); ?> </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>S.No</th>
                            <th>Donor Name</th>
                            <th>City</th>
                            <th>Project</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($donors): ?>
                            <?php $sno = 1; foreach ($donors as $d): ?>
                                <tr>
                                    <td><?= $sno++; ?></td>
                                    <td><?= htmlentities($d['donor_name']); ?></td>
                                    <td><?= htmlentities($d['city']); ?></td>
                                    <td><?= htmlentities($d['project_name']); ?></td>
                                    <td>₹ <?= number_format($d['amount']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-muted">
                                    No donation records found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Back Button -->
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-secondary">
                    ← Back to Dashboard
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
