<?php
session_start();
require_once "./pdo.php";

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Handle delete request
if (isset($_GET['volunteer_id']) && isset($_GET['project_id'])) {
    $volunteer_id = intval($_GET['volunteer_id']);
    $project_id = intval($_GET['project_id']);

    // Delete only that specific project for this volunteer
    $stmtDel = $pdo->prepare("DELETE FROM volunteer_project WHERE volunteer_id = ? AND project_id = ?");
    $stmtDel->execute([$volunteer_id, $project_id]);

    // Reload page to update list
    header("Location: adminVolunteer.php");
    exit();
}

// Fetch volunteers and their assigned projects + status
$stmt = $pdo->query("
    SELECT 
        v.volunteer_id, 
        v.name AS volunteer_name, 
        v.status,
        p.project_id,
        p.name AS project_name
    FROM volunteer v
    LEFT JOIN volunteer_project vp 
        ON v.volunteer_id = vp.volunteer_id
    LEFT JOIN projects p 
        ON vp.project_id = p.project_id
    ORDER BY v.volunteer_id ASC
");
$volunteers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count distinct active volunteers
$stmtActive = $pdo->query("SELECT COUNT(DISTINCT volunteer_id) AS total_active FROM volunteer WHERE status = 'active'");
$totalActive = $stmtActive->fetch(PDO::FETCH_ASSOC)['total_active'];

// Count total volunteers
$stmtTotal = $pdo->query("SELECT COUNT(*) AS total_volunteers FROM volunteer");
$totalVolunteers = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total_volunteers'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteers & Projects - Admin</title>
    <?php include("bootstrap.php"); ?>
    <style>
        body { background-color: #a9c5c0; }
        .content-wrapper { background-color: white; padding: 30px; border-radius: 10px; margin-top: 50px; box-shadow: 0 0 10px rgba(0,0,0,0.2); }
        h2 { text-align: center; margin-bottom: 30px; }
        .btn-add { float: right; margin-bottom: 15px; }
        .table thead { background-color: #343a40; color: white; }
        .btn-back { margin-top: 20px; }
        .status-active { color: green; font-weight: bold; }
        .status-inactive { color: red; font-weight: bold; }
        .stats-box { margin-bottom: 20px; font-size: 16px; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5">
  <a class="navbar-brand" href="javascript:void(0)">NGO</a>
  <span class="navbar-text text-white ml-2 mr-auto">
    <a href="javascript:void(0)" class="nav-link d-inline p-0">Admin</a>
  </span>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="update/adminUpdate.php">Edit Profile</a></li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <?php 
            $stmt3 = $pdo->prepare("SELECT name FROM admin WHERE admin_id = ?");
            $stmt3->execute([$_SESSION['admin_id']]);
            $admin = $stmt3->fetch(PDO::FETCH_ASSOC);
            echo htmlspecialchars($admin['name']);
          ?>
        </a>
      </li>
      <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
  </div>
</nav>

<!-- Main Content -->
<div class="container">
    <div class="content-wrapper">
        <h2>List of Volunteers & Their Projects</h2>
        <a href="signup/volunteerSignup.php" class="btn btn-success btn-add">+ Add Volunteer</a>

        <!-- Volunteer Stats -->
        <!-- Volunteer Stats -->
        <div class="stats-box">
        <p><b>Total Volunteers : <?= $totalVolunteers ?></b></p>
        <p><b>Total Active Volunteers : <?= $totalActive ?></b></p>
        </div>

        <?php if (count($volunteers) > 0): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Volunteer ID</th>
                        <th>Volunteer Name</th>
                        <th>Project Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sno = 1;
                    foreach ($volunteers as $v): ?>
                        <tr>
                            <td><?= $sno++ ?></td>
                            <td><?= htmlspecialchars($v['volunteer_id']) ?></td>
                            <td><?= htmlspecialchars($v['volunteer_name']) ?></td>
                            <td><?= $v['project_name'] ? htmlspecialchars($v['project_name']) : '<em>No Project Assigned</em>' ?></td>
                            <td>
                                <?php if (strtolower($v['status']) === 'active'): ?>
                                    <span class="status-active"><?= htmlspecialchars($v['status']) ?></span>
                                <?php else: ?>
                                    <span class="status-inactive"><?= htmlspecialchars($v['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($v['project_id']): ?>
                                    <a href="adminVolunteer.php?volunteer_id=<?= urlencode($v['volunteer_id']) ?>&project_id=<?= urlencode($v['project_id']) ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to remove this project from this volunteer?')">
                                       Remove
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">No volunteers found.</p>
        <?php endif; ?>

        <div class="text-center">
            <a href="index.php" class="btn btn-secondary btn-back">← Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
