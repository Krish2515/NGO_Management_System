<?php
require_once "../pdo.php";
session_start();

if (!isset($_SESSION['donor_id'])) {
    die("Login first");
}

// Fetch donor items
$stmt = $pdo->prepare("
    SELECT item, item_count, category, description, donated_at
    FROM items
    WHERE donor_id = ?
    ORDER BY donated_at DESC
");
$stmt->execute([$_SESSION['donor_id']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Donated Items</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body { background-color: #a3c6c4; }
        .box {
            background: #fff;
            padding: 25px;
            margin-top: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

<?php require_once "../donorNev.php"; ?>

<div class="container box">

    <div class="d-flex justify-content-between mb-4">
        <h3>Your Donated Items</h3>
        <a href="../index.php" class="btn btn-secondary">← Go Back</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>No.</th>
                    <th>Item</th>
                    <th>Count</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>

                <?php
                if ($items) {
                    $i = 1;
                    foreach ($items as $row) {
                        echo "<tr>";
                        echo "<td>".$i++."</td>";
                        echo "<td>".htmlentities($row['item'])."</td>";
                        echo "<td>".htmlentities($row['item_count'])."</td>";
                        echo "<td>".htmlentities($row['category'])."</td>";
                        echo "<td>".htmlentities($row['description'])."</td>";
                        echo "<td>".htmlentities($row['donated_at'])."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>
                            <td colspan='6' class='text-center text-muted'>
                                No item donations found
                            </td>
                          </tr>";
                }
                ?>

            </tbody>
        </table>
    </div>

</div>
</body>
</html>
