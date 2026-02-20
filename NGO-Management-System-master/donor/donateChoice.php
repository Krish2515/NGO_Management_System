<?php
session_start();
if (!isset($_SESSION['donor_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Choose Donation Type</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #a3c6c4;
        }

        .dashboard-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 40px;
            margin-top: 80px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>

<?php require_once "../donorNev.php"; ?>

<div class="container">
    <div class="dashboard-box text-center">

        <h2 class="mb-4">What would you like to donate?</h2>

        <p class="mb-5 text-muted">
            Choose the type of donation you want to make.
        </p>

        <a class="btn btn-primary btn-lg m-3 shadow-lg"
           style="width:220px;"
           href="donateMoney.php">
            Donate Money
        </a>

        <a class="btn btn-success btn-lg m-3 shadow-lg"
           style="width:220px;"
           href="donateItem.php">
            Donate Item
        </a>

        <br><br>

        <a class="btn btn-secondary"
           style="width:150px;"
           href="../index.php">
            ← Back
        </a>

    </div>
</div>

</body>
</html>
