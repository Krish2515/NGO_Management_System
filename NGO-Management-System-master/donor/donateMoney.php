<?php
require_once __DIR__ . '/../pdo.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['donor_id'])) {
    header("Location: donorLogin.php");
    exit();
}
// Fetch donor name
$stmt = $pdo->prepare("SELECT name FROM donor WHERE donor_id = ?");
$stmt->execute([$_SESSION['donor_id']]);
$donor = $stmt->fetch(PDO::FETCH_ASSOC);
$donor_name = $donor ? $donor['name'] : 'Donor';
// Fetch all projects from the 'projects' table
$projectsStmt = $pdo->query("SELECT project_id, name FROM projects ORDER BY name ASC");
$projects = $projectsStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['donation'], $_POST['from_account'], $_POST['project_id']) && !isset($_POST['cancel'])) {
    $donation     = trim($_POST['donation']);
    $from_account = trim($_POST['from_account']);
    $project_id   = trim($_POST['project_id']);

    if (!is_numeric($donation) || $donation <= 0) {
        $_SESSION['error'] = "Please enter a valid donation amount.";
    } elseif (!ctype_digit($from_account) || strlen($from_account) < 5) {
        $_SESSION['error'] = "Please enter a valid account number.";
    } elseif (!ctype_digit($project_id)) {
        $_SESSION['error'] = "Please select a valid project.";
    } else {
        // Fetch project name
        $projectStmt = $pdo->prepare("SELECT name FROM projects WHERE project_id = :project_id");
        $projectStmt->execute([':project_id' => $project_id]);
        $projectName = $projectStmt->fetchColumn();

        // Insert donation with project_id and project_name
        $stmt = $pdo->prepare("INSERT INTO `transaction` 
            (donor_id, project_id, project_name, tdate, amount, account_no) 
            VALUES (:donor_id, :project_id, :project_name, NOW(), :amount, :account_no)");
        $stmt->execute([
            ':donor_id'     => $_SESSION['donor_id'],
            ':project_id'   => $project_id,
            ':project_name' => $projectName,
            ':amount'       => $donation,
            ':account_no'   => $from_account
        ]);

        $_SESSION['success'] = "Donation of ₹" . htmlspecialchars($donation) . " has been successfully made to project '{$projectName}'.";
        header("Location: donateMoney.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Donation Form</title>
    <?php require_once "bootstrap.php" ?>
</head>

<body>

    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5">
    <a class="navbar-brand" href="javascript:void(0)">NGO</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
        data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Left side -->
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="javascript:void(0)">Donor <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)"><?= htmlentities($donor_name) ?></a>
            </li>
        </ul>

        <!-- Right side -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="../update/donorUpdate.php">Edit Profile</a>
            </li>

            <?php if (isset($indexFlag) && $indexFlag == 1) {?>
                <li class="nav-item">
                    <a class="nav-link" href="./logout.php">Logout</a>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>

    <div class="container dashboard-box">
        <h2>Donation Form</h2>

        <?php
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success text-center'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger text-center'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <form method="post">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>To (NGO Account No):</th>
                        <td><input type="text" class="form-control" value="1234560987" readonly /></td>
                    </tr>
                    <tr>
                        <th>Select Project:</th>
                        <td>
                            <select name="project_id" class="form-control" required>
                                <option value="">-- Choose a Project --</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?= htmlentities($project['project_id']) ?>">
                                        <?= htmlentities($project['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>From (Your Account No):</th>
                        <td><input type="text" class="form-control" name="from_account" required /></td>
                    </tr>
                    <tr>
                        <th>Donation Amount (₹):</th>
                        <td><input type="number" class="form-control" value="0" name="donation" min="1" required /></td>
                    </tr>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <input type="submit" class="btn btn-primary" value="Submit">
                <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancel</button>
            </div>
        </form>
    </div>

</body>

</html>