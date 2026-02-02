<?php
// ----------------------
// Database connection
// ----------------------
$host = "localhost"; 
$user = "root";      // your DB username
$pass = "";          // your DB password
$dbname = "ngo";     // your database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ----------------------
// Handle Form Submission
// ----------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cancel'])) {
        // If cancel clicked, redirect to homepage
        header("Location: ../index.php");
        exit();
    }

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // secure password
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];

    // Step 1: Insert into admin table
    $sql_admin = "INSERT INTO admin (name, email, phone, city_id) VALUES (?, ?, ?, ?)";
    $stmt1 = $conn->prepare($sql_admin);
    $stmt1->bind_param("sssi", $name, $email, $phone, $city);

    if ($stmt1->execute()) {
        $admin_id = $stmt1->insert_id; // Get inserted admin_id

        // Step 2: Insert into admin_login table
        $sql_login = "INSERT INTO admin_login (username, password, admin_id) VALUES (?, ?, ?)";
        $stmt2 = $conn->prepare($sql_login);
        $stmt2->bind_param("ssi", $username, $password, $admin_id);

        if ($stmt2->execute()) {
            echo "<script>alert('Admin registered successfully!'); window.location.href='../login/adminLogin.php';</script>";
        } else {
            echo "Error inserting into admin_login: " . $stmt2->error;
        }
        $stmt2->close();
    } else {
        echo "Error inserting into admin: " . $stmt1->error;
    }

    $stmt1->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Signup</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #b5c8c4;
    }
    .form-container {
      max-width: 500px;
      margin: 50px auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    .form-container img {
      display: block;
      margin: 0 auto 20px;
    }
    .form-container h3 {
      text-align: center;
      margin-bottom: 30px;
    }
    .btn-submit {
      background-color: #2d80f7;
      color: #fff;
    }
    .btn-cancel {
      background-color: #6c757d;
      color: #fff;
    }
  </style>
</head>
<body>

<div class="form-container">
  <form method="post" action="">
    <img src="../images/index/logo.png" alt="Logo" width="72" height="72">
    <h3>Admin Signup</h3>

    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" required>
    </div>

    <div class="form-group">
      <label>City</label>
      <select name="city" class="form-control" required>
        <option value="">-- Select City --</option>
        <option value="1">Rajkot</option>
        <option value="2">Ahmedabad</option>
        <option value="3">Surat</option>
      </select>
    </div>

    <div class="d-flex justify-content-between mt-4">
      <input type="submit" class="btn btn-submit" value="Submit">
      <button type="button" class="btn btn-cancel" onclick="window.history.back()">Cancel</button>
    </div>
  </form>
</div>

</body>
</html>
