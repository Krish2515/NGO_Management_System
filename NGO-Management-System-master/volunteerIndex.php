<?php
require_once "pdo.php";

// Redirect if not logged in
if (!isset($_SESSION['volunteer_id'])) {
    header("Location: login.php");
    exit();
}

// Toggle volunteer active/inactive status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    $newStatus = ($_SESSION['volunteer_status'] === 'active') ? 'inactive' : 'active';
    $stmt = $pdo->prepare("UPDATE volunteer SET status = ? WHERE volunteer_id = ?");
    $stmt->execute([$newStatus, $_SESSION['volunteer_id']]);
    $_SESSION['volunteer_status'] = $newStatus;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Join button POST (only if active)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id']) && $_SESSION['volunteer_status'] === 'active') {
    $volunteer_id = $_SESSION['volunteer_id'];
    $project_id = $_POST['project_id'];

    // Check if already joined
    $stmt = $pdo->prepare("SELECT 1 FROM volunteer_project WHERE volunteer_id = ? AND project_id = ?");
    $stmt->execute([$volunteer_id, $project_id]);

    if (!$stmt->fetchColumn()) {
        // Insert only if not already joined
        $stmt = $pdo->prepare("INSERT INTO volunteer_project (volunteer_id, project_id) VALUES (?, ?)");
        $stmt->execute([$volunteer_id, $project_id]);
    }

    // Refresh page to prevent resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch volunteer name and status
$stmt = $pdo->prepare("SELECT name, status FROM volunteer WHERE volunteer_id = ?");
$stmt->execute([$_SESSION['volunteer_id']]);
$volunteer = $stmt->fetch(PDO::FETCH_ASSOC);
$_SESSION['volunteer_name'] = $volunteer['name'] ?? '';
$_SESSION['volunteer_status'] = $volunteer['status'] ?? 'inactive';

// Fetch all projects
$stmt = $pdo->query("SELECT * FROM projects ORDER BY project_id DESC");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch joined project IDs
$stmt = $pdo->prepare("SELECT project_id FROM volunteer_project WHERE volunteer_id = ?");
$stmt->execute([$_SESSION['volunteer_id']]);
$joined_projects = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NGO Volunteer Dashboard</title>
    <?php include("bootstrap.php"); ?>
    <link rel="stylesheet" href="bootstrap/css/style.css">
    <style>
        .content-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
        .btn-join {
            padding: 4px 12px;
            font-size: 0.9rem;
        }
        .badge-in {
            background-color: #007bff;
            color: white;
            padding: 6px 14px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.85rem;
            display: inline-block;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5">
    <a class="navbar-brand" href="javascript:void(0)">NGO</a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav align-items-center">
            <li class="nav-item">
                <a class="nav-link">
                    Volunteer : <?= htmlentities($_SESSION['volunteer_name']) ?> 
                    (<?= ucfirst($_SESSION['volunteer_status']) ?>)
                </a>
            </li>
            <li class="nav-item">
                <form method="post" action="" style="display:inline;">
                    <input type="hidden" name="toggle_status" value="1">
                    <button class="btn btn-warning btn-sm ml-2" type="submit">
                        <?= $_SESSION['volunteer_status'] === 'active' ? 'Set Inactive' : 'Set Active' ?>
                    </button>
                </form>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="update/volunteerUpdate.php">Edit Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Main Content -->
<div class="container content-box">
    <h3 class="text-center mb-4">List of Available Projects</h3>

    <?php if ($_SESSION['volunteer_status'] !== 'active'): ?>
        <div class="alert alert-danger text-center">You are currently inactive and cannot join new projects.</div>
    <?php endif; ?>

    <table class="table table-bordered text-center">
        <thead>
        <tr>
            <th>Project Name</th>
            <th>Description</th>
            <th>Join</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($projects as $project): ?>
            <tr>
                <td><?= htmlentities($project['name']) ?></td>
                <td><?= htmlentities($project['description']) ?></td>
                <td>
                    <?php if (in_array($project['project_id'], $joined_projects)): ?>
                        <span class="badge-in">IN</span>
                    <?php elseif ($_SESSION['volunteer_status'] !== 'active'): ?>
                        <span class="badge badge-secondary">Inactive</span>
                    <?php else: ?>
                        <form method="post" action="">
                            <input type="hidden" name="project_id" value="<?= $project['project_id'] ?>">
                            <button class="btn btn-success btn-sm btn-join" type="submit">Join</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
