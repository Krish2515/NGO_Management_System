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
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>NGO Projects</title>

  <!-- Bootstrap 5.3.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #a9d0cd;
      font-family: Arial, sans-serif;
    }

    .custom-box {
      background-color: #2e2e2e;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.3);
      margin-bottom: 40px;
      color: #ffffff;
    }

    h2,
    h4 {
      font-weight: bold;
    }

    .carousel {
      background-color: #2e2e2e;
      padding: 10px;
      border-radius: 10px;
    }

    .carousel img {
      width: 100%;
      height: auto;
      max-height: 400px;
      object-fit: contain;
      border-radius: 8px;
      display: block;
      margin: 0 auto;
    }

    .carousel-indicators [data-bs-target] {
      background-color: #ffffff;
    }

    @media (max-width: 768px) {
      .navbar-brand {
        font-size: 1.3rem;
      }

      .nav-link {
        font-size: 1rem;
      }
    }
  </style>
</head>

<body>

  <!-- ✅ Custom Dark Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark shadow-lg p-3 mb-5" style="background-color: #212529;">
    <a class="navbar-brand" href="#">NGO</a>
    <a class="nav-link text-white" href="project.php" style="margin-right: 20px;">Projects</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Please Login To Continue</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            Login Here
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="login/adminLogin.php">Admin</a></li>
            <li><a class="dropdown-item" href="login/donorLogin.php">Donor</a></li>
            <li><a class="dropdown-item" href="login/volunteerLogin.php">Volunteer</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>

  <!-- ✅ Project Section -->
  <div class="container mt-5">
    <h2 class="text-center mb-4">Our Projects</h2>

    <div class="row justify-content-center">
      <?php foreach ($projects as $project): ?>
        <?php
        // Fetch images for this project
        $stmtImg = $pdo->prepare("SELECT image_path FROM project_images WHERE project_id = :pid");
        $stmtImg->execute([':pid' => $project['project_id']]);
        $images = $stmtImg->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="col-md-12 custom-box">
          <div class="row">
            <div class="col-md-6">
              <h4><?= htmlspecialchars($project['name']) ?></h4>
              <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
              <p><strong>Start Date:</strong> <?= htmlspecialchars($project['start_date']) ?></p>
              <p><strong>End Date:</strong> <?= htmlspecialchars($project['end_date']) ?></p>
              <p><strong>Target Amount:</strong> ₹<?= htmlspecialchars($project['target_amount']) ?></p>
              <p><strong>Remaining Amount:</strong> ₹<?= htmlspecialchars($project['remaining_amount']) ?></p>
              <?php $total = $project['target_amount'] - $project['remaining_amount'];
              $per = ($total / $project['target_amount']) * 100; ?>
              <div class="progress" style="height: 25px;">
                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?= $per ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $per ?>%;"></div>
              </div>
            </div>
            <?php if (!empty($images)): ?>
              <div class="col-md-6">
                <div id="carousel<?= $project['project_id'] ?>" class=" carousel slide mb-3" data-bs-ride="carousel">
                  <div class="carousel-indicators">
                    <?php foreach ($images as $index => $img): ?>
                      <button type="button"
                        data-bs-target="#carousel<?= $project['project_id'] ?>"
                        data-bs-slide-to="<?= $index ?>"
                        class="<?= $index === 0 ? 'active' : '' ?> "
                        aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                        aria-label="Slide <?= $index + 1 ?>"></button>
                    <?php endforeach; ?>
                  </div>

                  <div class="carousel-inner">
                    <?php foreach ($images as $index => $img): ?>
                      <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <img src="<?= htmlspecialchars($img['image_path']) ?>" class="d-block w-100" alt="Project Image">
                      </div>
                    <?php endforeach; ?>
                  </div>

                  <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= $project['project_id'] ?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= $project['project_id'] ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </button>
                </div>
              </div>
            <?php endif; ?>
          </div>
          <!-- ✅ Donate Button with login check -->
          <?php if (isset($_SESSION['donor_id'])): ?>
            <a href="donate.php?project_id=<?= $project['project_id'] ?>" class="btn btn-success w-100 mt-2">
              Donate Now
            </a>
          <?php else: ?>
            <a href="login/donorLogin.php?" class="btn btn-success w-100 mt-2">
              Donate Now
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Bootstrap 5.3.3 Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>