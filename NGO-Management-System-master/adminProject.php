<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM projects");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Project List</title>
    <?php include("bootstrap.php"); ?>
    <style>
        body {
            background-color: #a9c5c0;
        }
        .content-wrapper {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            margin-top: 50px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-add {
            float: right;
            margin-bottom: 15px;
        }
        .table thead {
            background-color: #343a40;
            color: white;
        }
        .btn-back {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
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

<div class="container">
    <div class="content-wrapper">
        <h2>List of Projects</h2>
        <a href="createProject.php" class="btn btn-success btn-add">+ Add Project</a>

        <?php if (count($projects) > 0): ?>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?= htmlspecialchars($project['name']) ?></td>
                        <td><?= htmlspecialchars($project['description']) ?></td>
                        <td><?= htmlspecialchars($project['start_date']) ?></td>
                        <td>
                            <a href="editProject.php?id=<?= $project['project_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">No projects found.</p>
        <?php endif; ?>

        <div class="text-center">
            <a href="index.php" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
