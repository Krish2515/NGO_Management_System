<!DOCTYPE html>
<?php
require_once "../pdo.php";
session_start();

// Fetch all cities from city table
$stmt = $pdo->query("SELECT city_id, cname FROM city");
$cities = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (
    isset($_POST['username']) && isset($_POST['password']) &&
    isset($_POST['name']) && isset($_POST['email']) &&
    isset($_POST['intrests']) && isset($_POST['date']) &&
    isset($_POST['phone']) && isset($_POST['city'])
) {
    // 1. Insert into volunteer table
    $stmt1 = $pdo->prepare(
        "INSERT INTO volunteer (name, email, intrests, dob, city_id, phone)
         VALUES (:name, :email, :intrests, :dob, :city, :phone)"
    );
    $stmt1->execute([
        ':name'     => $_POST['name'],
        ':email'    => $_POST['email'],
        ':intrests' => $_POST['intrests'],
        ':dob'      => $_POST['date'],
        ':city'     => $_POST['city'],
        ':phone'    => $_POST['phone']
    ]);

    $volunteer_id = $pdo->lastInsertId();

    // 2. Insert into volunteer_login (PLAIN TEXT PASSWORD)
    $stmt2 = $pdo->prepare(
        "INSERT INTO volunteer_login (username, password, volunteer_id)
         VALUES (:username, :password, :volunteer_id)"
    );
    $stmt2->execute([
        ':username'     => $_POST['username'],
        ':password'     => $_POST['password'], // PLAIN TEXT
        ':volunteer_id' => $volunteer_id
    ]);

    $_SESSION['success'] = "Volunteer registered successfully.";
    header("Location: ../login/volunteerLogin.php");
    exit;
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Volunteer Signup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CDN -->
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #b5c8c4;
        }
        .form-container {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
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
            color: white;
            width: 48%;
        }
        .btn-cancel {
            background-color: #6c757d;
            color: white;
            width: 48%;
        }
    </style>
</head>

<body>

<div class="form-container">
    <img src="../images/index/logo.png" width="72" height="72" alt="Logo">
    <h3>Volunteer Signup</h3>

    <form method="post" action="volunteerSignup.php">

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
            <label>Address / Interests</label>
            <input type="text" name="intrests" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="form-group">
            <label>City</label>
            <select name="city" class="form-control" required>
                <option value="">-- Select City --</option>
                <?php foreach ($cities as $city): ?>
                    <option value="<?= $city['city_id'] ?>">
                        <?= htmlentities($city['cname']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <input type="submit" class="btn btn-submit" value="Submit">
            <button type="button" class="btn btn-cancel" onclick="window.history.back()">
                Cancel
            </button>
        </div>
    </form>
</div>

</body>
</html>
