<!DOCTYPE html><?php
require_once "../pdo.php";
session_start();

if (!isset($_SESSION['admin_id'])) {
    die("Login first");
}

if (isset($_POST['Cancel'])) {
    header('Location: ../index.php');
    return;
}

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['city']) && isset($_POST['phone'])) {
    if (strlen($_POST['name']) > 0 && strlen($_POST['email']) > 0 && strlen($_POST['city']) > 0 && strlen($_POST['phone']) > 0) {
        $stmt = $pdo->prepare("UPDATE `admin` SET `email` = :em, `city_id` = :ci, `phone` = :ph, `name` = :nm WHERE `admin_id` = :id");
        $stmt->execute(array(
            ':nm' => $_POST['name'],
            ':em' => $_POST['email'],
            ':ci' => $_POST['city'],
            ':ph' => $_POST['phone'],
            ':id' => $_SESSION['admin_id']
        ));
        $_SESSION['success'] = "Record updated";
        header('Location: ../index.php');
        return;
    } else {
        $_SESSION['error'] = "Everything is required";
        header("Location: adminUpdate.php");
        return;
    }
}

// ✅ Fetch values to pre-fill the form
$stmt4 = $pdo->prepare("SELECT * FROM `admin` WHERE `admin_id` = :id");
$stmt4->execute([':id' => $_SESSION['admin_id']]);
$row = $stmt4->fetch(PDO::FETCH_ASSOC);

$name = htmlentities($row['name']);
$email = htmlentities($row['email']);
$city = $row['city_id'];
$phone = htmlentities($row['phone']);

$stmt3 = $pdo->query("SELECT * FROM city");
$rows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Admin Profile</title>
  <!-- Bootstrap CDN -->
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
  <form method="post" action="adminUpdate.php">
    <img src="../images/index/logo.png" alt="Logo" width="72" height="72">
    <h3>Edit Admin Profile</h3>

    <div class="form-group">
      <label>Name</label>
      <input type="text" name="name" class="form-control" value="<?= $name ?>" required>
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="<?= $email ?>" required>
    </div>

    <div class="form-group">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" value="<?= $phone ?>" required>
    </div>

    <div class="form-group">
      <label>City</label>
      <select name="city" class="form-control" required>
        <option value="">-- Select City --</option>
        <?php foreach ($rows as $row): ?>
          <option value="<?= $row['city_id'] ?>" <?= $row['city_id'] == $city ? 'selected' : '' ?>>
            <?= htmlentities($row['cname']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="d-flex justify-content-between mt-4">
      <input type="submit" class="btn btn-submit" value="Submit">
      <input type="submit" name="Cancel" class="btn btn-cancel" value="Cancel">
    </div>
  </form>
</div>

</body>
</html>
