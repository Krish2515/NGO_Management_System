<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->query("
    SELECT beneficiaries.*, projects.name AS project_name 
    FROM beneficiaries 
    JOIN projects ON beneficiaries.project_id = projects.project_id
");

$beneficiaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Beneficiaries</title>
    <?php include("bootstrap.php"); ?>
    <style>
        body {
            background-color: #a9c5c0;
        }
        .card-custom {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .btn-success {
            float: right;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5">
  <a class="navbar-brand" href="javascript:void(0)">NGO</a>

  <!-- Admin (next to NGO brand) -->
  <span class="navbar-text text-white ml-2 mr-auto">
    <a href="javascript:void(0)" class="nav-link d-inline p-0">Admin</a>
  </span>

  <!-- Right side of navbar -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">

      <li class="nav-item">
        <a class="nav-link" href="update/adminUpdate.php">Edit Profile</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="#">
          <?php 
            $stmt3 = $pdo->query("SELECT `name` FROM `admin` WHERE `admin_id` =".$_SESSION['admin_id']);
            $rows2 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
            echo htmlspecialchars($rows2[0]['name']);
          ?>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>

    </ul>
  </div>
</nav>

<div class="container mt-5">
    <div class="card shadow-lg rounded">
        <div class="card-body">

            <!-- Heading + Add Button -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">All Beneficiaries</h3>
                <a href="addBeneficiary.php" class="btn btn-success">
                    + Add Beneficiary
                </a>
            </div>

            <!-- Success Message -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Contact</th>
                            <th>City</th>
                            <th>Project</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($beneficiaries): ?>
                            <?php foreach ($beneficiaries as $b): ?>
                                <tr>
                                    <td><?= $b['id']; ?></td>
                                    <td><?= htmlentities($b['name']); ?></td>
                                    <td><?= $b['age']; ?></td>
                                    <td><?= $b['gender']; ?></td>
                                    <td><?= htmlentities($b['contact']); ?></td>
                                    <td><?= htmlentities($b['city']); ?></td>
                                    <td><?= htmlentities($b['project_name']); ?></td>
                                    <td>
                                        <a href="editBeneficiary.php?id=<?= $b['id']; ?>"
                                           class="btn btn-sm btn-primary">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-muted">
                                    No beneficiaries found
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
