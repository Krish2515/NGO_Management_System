<?php
require_once "pdo.php";
session_start();

// Fetch all projects with remaining target amount
$stmt = $pdo->query("
    SELECT 
        p.project_id,
        p.name,
        p.description,
        p.start_date,
        p.end_date,
        p.target_amount,
        IFNULL(SUM(t.amount), 0) AS total_donated,
        (p.target_amount - IFNULL(SUM(t.amount), 0)) AS remaining_amount
    FROM projects p
    LEFT JOIN transaction t ON p.project_id = t.project_id
    GROUP BY p.project_id
    ORDER BY p.project_id DESC
");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Our Projects - NGO Portal</title>

    <!-- Bootstrap 4 (Same as about.php) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #a9d0cd;
        }

        .hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('https://images.unsplash.com/photo-1509099836639-18ba1795216d');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 20px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            margin-bottom: 40px;
        }

        .project-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            margin-bottom: 40px;
            transition: 0.3s;
        }

        .project-box:hover {
            transform: translateY(-5px);
        }

        h2, h4 {
            font-weight: 700;
            color: #1e3c72;
        }

        .progress {
            height: 25px;
        }
    </style>
</head>

<body>

<!-- SAME NAVBAR AS about.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5">
  <a class="navbar-brand" href="#">NGO</a>
  <a class="navbar-brand" href="project.php">Projects</a>

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

<div class="container">

    <!-- Hero Section -->
    <div class="hero">
        <h1 class="font-weight-bold">Our Projects</h1>
        <p class="lead">Support our ongoing initiatives and make a difference.</p>
    </div>

    <?php foreach ($projects as $project): ?>

        <?php
        $stmtImg = $pdo->prepare("SELECT image_path FROM project_images WHERE project_id = :pid");
        $stmtImg->execute([':pid' => $project['project_id']]);
        $images = $stmtImg->fetchAll(PDO::FETCH_ASSOC);

        $total = $project['target_amount'] - $project['remaining_amount'];
        $per = ($project['target_amount'] > 0) 
            ? ($total / $project['target_amount']) * 100 
            : 0;
        ?>

        <div class="project-box">
            <div class="row">
                <div class="col-md-6">
                    <h4><?= htmlspecialchars($project['name']) ?></h4>
                    <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>

                    <p><strong>Start Date:</strong> <?= htmlspecialchars($project['start_date']) ?></p>
                    <p><strong>End Date:</strong> <?= htmlspecialchars($project['end_date']) ?></p>
                    <p><strong>Target Amount:</strong> ₹<?= htmlspecialchars($project['target_amount']) ?></p>
                    <p><strong>Remaining Amount:</strong> ₹<?= htmlspecialchars($project['remaining_amount']) ?></p>

                    <div class="progress">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                             role="progressbar"
                             style="width: <?= $per ?>%;">
                        </div>
                    </div>
                </div>

                <?php if (!empty($images)): ?>
                <div class="col-md-6">
                    <div id="carousel<?= $project['project_id'] ?>" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($images as $index => $img): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <img src="<?= htmlspecialchars($img['image_path']) ?>" 
                                         class="d-block w-100" 
                                         style="border-radius:10px;">
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <a class="carousel-control-prev" 
                           href="#carousel<?= $project['project_id'] ?>" 
                           role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </a>

                        <a class="carousel-control-next" 
                           href="#carousel<?= $project['project_id'] ?>" 
                           role="button" data-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Donate Button -->
            <?php if (isset($_SESSION['donor_id'])): ?>
                <a href="donate.php?project_id=<?= $project['project_id'] ?>" 
                   class="btn btn-success btn-block mt-3">
                   Donate Now
                </a>
            <?php else: ?>
                <a href="login/donorLogin.php" 
                   class="btn btn-success btn-block mt-3">
                   Donate Now
                </a>
            <?php endif; ?>
        </div>

    <?php endforeach; ?>

</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
