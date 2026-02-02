<?php
require_once "pdo.php";
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Fetch all projects (id + name) from DB
$stmt = $pdo->query("SELECT project_id, name FROM projects");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Beneficiary</title>
    <?php include("bootstrap.php"); ?>
    <style>
        body {
            background-color: #a9c5c0;
        }
        .card-custom {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            max-width: 700px; /* Limit width */
            margin: auto;     /* Center card */
        }
        label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5">
        <a class="navbar-brand" href="index.php">NGO</a>
        <!-- Admin (next to NGO brand) -->
        <span class="navbar-text text-white ml-2 mr-auto">
            <a href="adminIndex2.php" class="nav-link d-inline p-0">Admin</a>
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
                        $stmt3 = $pdo->query("SELECT name FROM admin WHERE admin_id =" . $_SESSION['admin_id']);
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

    <!-- Beneficiary Form -->
    <div class="container mt-5">
        <div class="card card-custom">
            <h2 class="text-center mb-4">Add New Beneficiary</h2>
            <form method="POST" action="insertBeneficiary.php">
                <div class="form-group mb-3">
                    <label>Name:</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>Age:</label>
                    <input type="number" name="age" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>Gender:</label>
                    <select name="gender" class="form-control" required>
                        <option value="">--Select--</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>Contact:</label>
                    <input type="text" name="contact" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>Address:</label>
                    <textarea name="address" class="form-control" rows="2" required></textarea>
                </div>
                <div class="form-group mb-3">
                    <label>City:</label>
                    <input type="text" name="city" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>Project:</label>
                    <select name="project_id" class="form-control" required>
                        <option value="">--Select Project--</option>
                        <?php foreach ($projects as $project): ?>
                            <option value="<?= $project['project_id'] ?>">
                                <?= htmlspecialchars($project['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="viewBeneficiaries.php" class="btn btn-secondary">← Cancel</a>
                    <button type="submit" class="btn btn-success">Add Beneficiary</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
