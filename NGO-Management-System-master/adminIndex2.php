<?php
require_once "pdo.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- NAVBAR -->
<!-- NAVBAR -->
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


<!-- DASHBOARD CARDS -->
<div class="container bg-light p-5 text-center rounded">
  <div class="row justify-content-center">

    <!-- Donors -->
    <div class="col-md-3 mx-3">
      <a href="admin.php" class="text-decoration-none text-dark">
        <img src="images/donor.png" alt="Donors" style="width: 100px; height: 100px;" class="mb-3">
        <h3 class="text-primary">Donors</h3>
      </a>
    </div>

    <!-- Volunteers -->
    <div class="col-md-3 mx-3">
      <a href="adminVolunteer.php" class="text-decoration-none text-dark">
        <img src="images/volunteer.png" alt="Volunteers" style="width: 100px; height: 100px;" class="mb-3">
        <h3 class="text-primary">Volunteers</h3>
      </a>
    </div>

    <!-- Projects -->
    <div class="col-md-3 mx-3">
      <a href="adminProject.php" class="text-decoration-none text-dark">
        <img src="images/project.png" alt="Projects" style="width: 100px; height: 100px;" class="mb-3">
        <h3 class="text-primary">Projects</h3>
      </a>
    </div>

    <!-- Beneficiaries -->
    <div class="col-md-3 mx-3 mt-4">
      <a href="viewBeneficiaries.php" class="text-decoration-none text-dark">
        <img src="images/beneficiary.png" alt="Beneficiaries" style="width: 100px; height: 100px;" class="mb-3">
        <h3 class="text-primary">Beneficiaries</h3>
      </a>
    </div>

    <!-- City -->
    <div class="col-md-3 mx-3 mt-4">
      <a href="city.php" class="text-decoration-none text-dark">
        <img src="images/city.png" alt="City" style="width: 100px; height: 100px;" class="mb-3">
        <h3 class="text-primary">City</h3>
      </a>
    </div>

  </div>
</div>
