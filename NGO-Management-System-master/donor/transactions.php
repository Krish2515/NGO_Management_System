<?php
require_once "../pdo.php";
session_start();

if (!isset($_SESSION['donor_id'])) {
    die("Login first");
}

// Fetch donor name
$stmt = $pdo->prepare("SELECT name FROM donor WHERE donor_id = ?");
$stmt->execute([$_SESSION['donor_id']]);
$donor = $stmt->fetch(PDO::FETCH_ASSOC);
$donor_name = $donor ? $donor['name'] : 'Donor';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Transactions</title>
    <?php require_once "bootstrap.php"; ?>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5">
    <a class="navbar-brand" href="javascript:void(0)">NGO</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
        data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Left side -->
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="javascript:void(0)">Donor <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)"><?= htmlentities($donor_name) ?></a>
            </li>
        </ul>

        <!-- Right side -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="../update/donorUpdate.php">Edit Profile</a>
            </li>

            <?php if (isset($indexFlag) && $indexFlag == 1) {?>
                <li class="nav-item">
                    <a class="nav-link" href="./logout.php">Logout</a>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <div class="card shadow-lg rounded">
        <div class="card-body">

            <!-- Heading + Button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">Your Transactions</h3>
                <a href="../index.php" class="btn btn-secondary">
                    ← Go Back
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th style="width:10%">Sno</th>
                            <th style="width:45%">Date & Time</th>
                            <th style="width:45%">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt3 = $pdo->prepare(
                            "SELECT tdate, amount FROM `transaction` WHERE donor_id = ?"
                        );
                        $stmt3->execute([$_SESSION['donor_id']]);
                        $rows = $stmt3->fetchAll(PDO::FETCH_ASSOC);

                        $count = 1;
                        foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>{$count}</td>";
                            echo "<td>" . htmlentities($row['tdate']) . "</td>";
                            echo "<td>" . htmlentities($row['amount']) . "</td>";
                            echo "</tr>";
                            $count++;
                        }

                        if ($count === 1) {
                            echo "<tr>
                                    <td colspan='3' class='text-center text-muted'>
                                        No transactions found
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

</body>
</html>
