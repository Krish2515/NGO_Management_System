<?php
require_once "pdo.php";
session_start();

// Fetch cities
$stmt = $pdo->query("SELECT cname, address, mobile_no FROM city");
$cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>About Us - NGO Portal</title>

    <!-- Bootstrap 4 (Same as city.php) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #a9d0cd;
        }

        .hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('https://images.unsplash.com/photo-1526256262350-7da7584cf5eb');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 20px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .section-box {
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            transition: 0.3s;
        }

        .section-box:hover {
            transform: translateY(-5px);
        }

        .branch-card {
            transition: 0.3s;
            border-radius: 15px;
        }

        .branch-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .branch-title {
            color: #1e3c72;
            font-weight: 600;
        }

        h2 {
            font-weight: 700;
            color: #1e3c72;
        }
    </style>
</head>
<body>

<!-- SAME NAVBAR AS city.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5">
  <a class="navbar-brand" href="#">NGO</a>
  <a class="navbar-brand" href="about.php">About Us</a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">

      <li class="nav-item">
        <a class="navbar-brand" href="index.php"><- Back</a>
      </li>

    </ul>
  </div>
</nav>

<div class="container mt-5">

    <!-- Hero Section -->
    <div class="hero mb-5">
        <h1 class="font-weight-bold">About Our NGO</h1>
        <p class="lead">
            Dedicated to serving communities and creating positive social impact.
        </p>
    </div>

    <!-- Mission -->
    <div class="section-box">
        <h3>Our Mission</h3>
        <p>
            To empower underprivileged communities through education, healthcare support,
            food distribution, and sustainable development initiatives.
        </p>
    </div>

    <!-- Vision -->
    <div class="section-box">
        <h3>Our Vision</h3>
        <p>
            To build a society where everyone has equal access to opportunities,
            resources, and a better future.
        </p>
    </div>

    <!-- Branches -->
    <h2 class="text-center mb-4 mt-5">Our Branches</h2>

    <div class="row">
        <?php foreach ($cities as $city): ?>
            <div class="col-md-4 mb-4">
                <div class="card branch-card shadow-sm">
                    <div class="card-body">
                        <h5 class="branch-title">
                            <?php echo htmlspecialchars($city['cname']); ?>
                        </h5>
                        <p><strong>Address:</strong><br>
                            <?php echo htmlspecialchars($city['address']); ?>
                        </p>
                        <p><strong>Mobile:</strong>
                            <?php echo htmlspecialchars($city['mobile_no']); ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
