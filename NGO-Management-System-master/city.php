<?php
require_once "pdo.php";
session_start();

// Fetch all cities
$stmt = $pdo->query("SELECT * FROM city ORDER BY city_id ASC");
$cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>City List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #a3c6c4;
        }
        .dashboard-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin-top: 50px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .navbar-text, .text-light {
            font-weight: 500;
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
            $stmt3 = $pdo->query("SELECT name FROM admin WHERE admin_id =".$_SESSION['admin_id']);
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

<!-- Main Content -->
<div class="container dashboard-box">
    <h2 class="mb-4 text-center">List of Cities</h2>

    <div class="text-right mb-3">
        <a href="addCity.php" class="btn btn-success">+ Add City</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>City ID</th>
                <th>City Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cities as $city): ?>
                <tr>
                    <td><?= htmlspecialchars($city['city_id']) ?></td>
                    <td><?= htmlspecialchars($city['cname']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>