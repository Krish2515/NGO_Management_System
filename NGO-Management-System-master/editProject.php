<?php
require_once "pdo.php";
session_start();

// hide PHP notices/warnings from leaking into HTML
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Project ID missing.";
    header("Location: adminProject.php");
    exit;
}

$project_id = $_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $target_amount = $_POST['target_amount'] ?? 0;
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    // update project details
    $sql = "UPDATE projects 
            SET name = :name, description = :description, target_amount = :target_amount,
                start_date = :start_date, end_date = :end_date
            WHERE project_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':target_amount' => $target_amount,
        ':start_date' => $start_date,
        ':end_date' => $end_date,
        ':id' => $project_id
    ]);

    // handle image uploads
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = "uploads/projects/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] === 0) {
                $fileName = time() . "_" . basename($_FILES['images']['name'][$key]);
                $relativePath = $uploadDir . $fileName;

                if (move_uploaded_file($tmp_name, $relativePath)) {
                    $sqlImg = "INSERT INTO project_images (project_id, image_path)
                               VALUES (:project_id, :image_path)";
                    $stmtImg = $pdo->prepare($sqlImg);
                    $stmtImg->execute([
                        ':project_id' => $project_id,
                        ':image_path' => $relativePath
                    ]);
                }
            }
        }
    }

    $_SESSION['success'] = "Project updated successfully.";
    header("Location: adminProject.php");
    exit;
}

// Fetch project details
$stmt = $pdo->prepare("SELECT * FROM projects WHERE project_id = :id");
$stmt->execute([':id' => $project_id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch existing images
$stmtImgs = $pdo->prepare("SELECT * FROM project_images WHERE project_id = :id");
$stmtImgs->execute([':id' => $project_id]);
$images = $stmtImgs->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Project</title>
    <?php include("bootstrap.php"); ?>
</head>
<body style="background-color: #accfcf;">

<div class="container mt-5">
    <div class="bg-white p-4 rounded shadow-sm mx-auto" style="max-width: 600px;">
        <h3 class="mb-4 text-center">Edit Project</h3>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Project Name:</label>
                <input type="text" class="form-control" name="name" 
                       value="<?= htmlspecialchars($project['name']) ?>" required>
            </div>

            <div class="form-group mt-3">
                <label>Description:</label>
                <textarea class="form-control" name="description" required><?= htmlspecialchars($project['description']) ?></textarea>
            </div>

            <div class="form-group mt-3">
                <label>Target Amount (₹):</label>
                <input type="number" class="form-control" name="target_amount" 
                       value="<?= htmlspecialchars($project['target_amount']) ?>" required min="0" step="0.01">
            </div>

            <div class="form-group mt-3">
                <label>Start Date:</label>
                <input type="date" class="form-control" name="start_date" 
                       value="<?= $project['start_date'] ?>" required>
            </div>

            <div class="form-group mt-3">
                <label>End Date:</label>
                <input type="date" class="form-control" name="end_date" 
                       value="<?= $project['end_date'] ?>" required>
            </div>

            <div class="form-group mt-3">
                <label><strong>Upload New Images:</strong></label>
                <div id="file-inputs">
                    <input type="file" name="images[]" class="form-control mb-2">
                </div>
                <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addNewFileInput()">Add More Images</button>
            </div>

            <div class="form-group mt-4">
                <label><strong>Current Images:</strong></label>
                <div class="row">
                    <?php $totalImages = count($images); ?>
                    <?php foreach ($images as $img): ?>
                        <div class="col-6 col-md-4 mb-3 text-center">
                            <?php if (!empty($img['image_path']) && file_exists($img['image_path'])): ?>
                                <img src="<?= htmlspecialchars($img['image_path']) ?>" 
                                     class="img-thumbnail" style="height: 100px; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-danger small d-block">Image not found</span>
                            <?php endif; ?>

                            <?php if ($totalImages > 1): ?>
                                <form method="POST" action="deleteProjectImage.php" 
                                      onsubmit="return confirm('Remove this image?');">
                                    <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                                    <input type="hidden" name="project_id" value="<?= $project_id ?>">
                                    <button class="btn btn-danger btn-sm btn-block mt-1">Remove</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted d-block mt-1">Can't remove</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="adminProject.php" class="btn btn-secondary">← Back</a>
                <button type="submit" name="update" class="btn btn-primary">Update Project</button>
            </div>
        </form>
    </div>
</div>

<script>
function addNewFileInput() {
    const newInput = document.createElement("input");
    newInput.type = "file";
    newInput.name = "images[]";
    newInput.className = "form-control mb-2";
    document.getElementById("file-inputs").appendChild(newInput);
}
</script>

</body>
</html>
