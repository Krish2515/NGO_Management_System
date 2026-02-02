<?php 
require_once "pdo.php"; 
session_start(); 

if (!isset($_SESSION['admin_id'])) { 
    header('Location: login.php'); 
    exit; 
} 

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    if ( isset($_POST['name']) && isset($_POST['description']) && isset($_POST['target_amount']) && isset($_POST['start_date']) && isset($_POST['end_date']) ) { 

        $sql = "INSERT INTO projects (name, description, target_amount, start_date, end_date) 
                VALUES (:name, :description, :target_amount, :start_date, :end_date)"; 
        $stmt = $pdo->prepare($sql); 
        $stmt->execute([ 
            ':name' => $_POST['name'], 
            ':description' => $_POST['description'], 
            ':target_amount' => $_POST['target_amount'], 
            ':start_date' => $_POST['start_date'], 
            ':end_date' => $_POST['end_date'] 
        ]); 

        $project_id = $pdo->lastInsertId(); 

        // Handle image uploads
        if (!empty($_FILES['images']['name'][0])) { 
            $uploadDir = "uploads/projects/"; 
            if (!is_dir($uploadDir)) { 
                mkdir($uploadDir, 0777, true); 
            } 
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) { 
                if ($_FILES['images']['error'][$key] === 0) { 
                    $fileName = time() . "_" . basename($_FILES['images']['name'][$key]); 
                    $targetFilePath = $uploadDir . $fileName; 
                    if (move_uploaded_file($tmp_name, $targetFilePath)) { 
                        $sqlImg = "INSERT INTO project_images (project_id, image_path) VALUES (:project_id, :image_path)"; 
                        $stmtImg = $pdo->prepare($sqlImg); 
                        $stmtImg->execute([ 
                            ':project_id' => $project_id, 
                            ':image_path' => $targetFilePath 
                        ]); 
                    } 
                } 
            } 
        } 

        $_SESSION['success'] = "Project created successfully."; 
        header('Location: adminProject.php'); 
        exit; 
    } 
} 
?> 

<!DOCTYPE html> 
<html> 
<head> 
  <title>Create Project</title> 
  <?php include("bootstrap.php"); ?> 
</head> 
<body style="background-color: #accfcf;"> 

<!-- Navbar --> 
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg p-3 mb-5"> 
  <a class="navbar-brand" href="index.php">NGO</a> 
  <span class="navbar-text text-white ml-2 mr-auto"> 
    <a href="adminIndex2.php" class="nav-link d-inline p-0">Admin</a> 
  </span> 
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
            $stmt3 = $pdo->query("SELECT name FROM admin WHERE admin_id =".$_SESSION['admin_id']); 
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

<!-- Project Form Card --> 
<div class="container"> 
  <div class="bg-white p-4 rounded shadow-sm mx-auto" style="max-width: 600px;"> 
    <h3 class="mb-4 text-center">Add New Project</h3> 

    <form method="POST" enctype="multipart/form-data"> 
      <div class="form-group"> 
        <label for="name">Project Name:</label> 
        <input type="text" class="form-control" name="name" required> 
      </div> 

      <div class="form-group mt-3"> 
        <label for="description">Description:</label> 
        <textarea class="form-control" name="description" required></textarea> 
      </div> 

      <div class="form-group mt-3"> 
        <label for="target_amount">Target Amount (₹):</label> 
        <input type="number" class="form-control" name="target_amount" required min="0" step="0.01"> 
      </div> 

      <div class="form-group mt-3"> 
        <label for="start_date">Start Date:</label> 
        <input type="date" class="form-control" name="start_date" required> 
      </div> 

      <div class="form-group mt-3"> 
        <label for="end_date">End Date:</label> 
        <input type="date" class="form-control" name="end_date" required> 
      </div> 

      <!-- Dynamic Image Uploads --> 
      <div id="imageInputs"> 
        <div class="form-group mt-3"> 
          <label>Project Images:</label> 
          <input type="file" name="images[]" class="form-control" accept="image/*"> 
        </div> 
      </div> 

      <!-- Button Row --> 
      <div class="d-flex justify-content-between align-items-center mt-4"> 
        <button type="button" class="btn btn-secondary" onclick="addImageInput()">Add More Images</button> 
        <button type="submit" class="btn btn-primary">Save</button> 
      </div> 
    </form> 
  </div> 

  <!-- Back Button --> 
  <div class="text-center mt-4"> 
    <a href="adminProject.php" class="btn btn-secondary">← Back to Dashboard</a> 
  </div> 
</div> 

<script> 
function addImageInput() { 
  let div = document.createElement('div'); 
  div.classList.add('form-group', 'mt-3'); 
  div.innerHTML = '<input type="file" name="images[]" class="form-control" accept="image/*">'; 
  document.getElementById('imageInputs').appendChild(div); 
} 
</script> 

</body> 
</html>
