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
    <title>Donate Item</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #a3c6c4;
        }

        .dashboard-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin-top: 40px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
        }

        label {
            font-weight: 600;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php require_once "../donorNev.php"; ?>

<div class="container">
    <div class="dashboard-box">

        <h2 class="text-center mb-4">Donate Items</h2>

        <!-- ✅ Success Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success text-center">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- ❌ Error Message -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger text-center">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="saveItemDonation.php">

            <div class="form-group">
                <label>Item Name</label>
                <input type="text" name="item" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Number of Items</label>
                <input type="number" name="item_count" class="form-control" min="1" required>
            </div>

            <div class="form-group">
                <label>Item Category</label>
                <select name="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <option>Food</option>
                    <option>Clothes</option>
                    <option>Books</option>
                    <option>Stationery</option>
                    <option>Medical Supplies</option>
                    <option>Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Description (Optional)</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-success btn-lg shadow-lg" style="width:200px;">
                    Submit Donation
                </button>

                <a href="donateChoice.php"
                   class="btn btn-secondary btn-lg shadow-lg"
                   style="width:200px;">
                   Back
                </a>
            </div>

        </form>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
