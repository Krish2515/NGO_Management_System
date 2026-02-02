<?php
require_once "pdo.php";
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cname'])) {
    $cname = trim($_POST['cname']);

    if (!empty($cname)) {
        $stmt = $pdo->prepare("INSERT INTO city (cname) VALUES (:cname)");
        $stmt->execute([':cname' => $cname]);

        $_SESSION['success'] = "City added successfully.";
        header("Location: city.php");
        exit;
    } else {
        $_SESSION['error'] = "City name cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add City</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #accdcd;
        }
        .card-custom {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5">
  <a class="navbar-brand font-weight-bold" href="index.php">NGO</a>

  <span class="navbar-text text-white ml-2 mr-auto">
    <a href="adminIndex2.php" class="nav-link d-inline p-0 text-white">Admin</a>
  </span>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="update/adminUpdate.php">Edit Profile</a>
      </li>
      <li class="nav-item">
        <span class="nav-link">
          <?php 
            $stmt3 = $pdo->prepare("SELECT name FROM admin WHERE admin_id = ?");
            $stmt3->execute([$_SESSION['admin_id']]);
            $admin = $stmt3->fetch(PDO::FETCH_ASSOC);
            echo htmlspecialchars($admin['name']);
          ?>
        </span>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<!-- Main content -->
<div class="container mt-5">
    <div class="card-custom">
        <h2 class="mb-4">Add New City</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="cname">City Name</label>
                <input type="text" class="form-control" id="cname" name="cname" required>
            </div>
            <button type="submit" class="btn btn-success">Add City</button>
            <a href="city.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>
