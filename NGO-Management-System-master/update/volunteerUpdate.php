<?php 
require_once "../pdo.php";
session_start();
if(!isset($_SESSION['volunteer_id'])){
    die("Login first");
}

if(isset($_POST['Cancel'])){
    header('Location: ../index.php');
    return;
}

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['intrests']) && isset($_POST['date']) && isset($_POST['city']) && isset($_POST['phone'])){
    if(strlen($_POST['name']) > 0 && strlen($_POST['email']) > 0 && strlen($_POST['date']) > 0 && strlen($_POST['intrests']) > 0 && strlen($_POST['city']) > 0 && strlen($_POST['phone']) > 0){   
        $stmt = $pdo->prepare("UPDATE `volunteer` SET `name`= :nm, `email`= :em, `intrests`= :inn, `dob`= :db, `city_id`= :ci, `phone`= :ph WHERE `volunteer_id` = :id");
        $stmt->execute(array(
            ':nm' => $_POST['name'],
            ':em' => $_POST['email'],
            ':inn' => $_POST['intrests'],
            ':db' => $_POST['date'],
            ':ci' => $_POST['city'],
            ':ph' => $_POST['phone'],
            ':id' => $_SESSION['volunteer_id']
        ));

        $_SESSION['success'] = "Record updated";
        header('Location: ../index.php');
        return;
    } else {
        $_SESSION['error'] = "All fields are required";
        header("Location: volunteerUpdate.php");
        return;
    }
}

$stmt4 = $pdo->query("SELECT * FROM `volunteer` WHERE `volunteer_id`=" . $_SESSION['volunteer_id']);
$rows3 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

$name = htmlentities($rows3[0]['name']);
$email = htmlentities($rows3[0]['email']);
$intrests = htmlentities($rows3[0]['intrests']);
$date = htmlentities($rows3[0]['dob']);
$city = htmlentities($rows3[0]['city_id']);
$phone = htmlentities($rows3[0]['phone']);

$stmt3 = $pdo->query("SELECT * FROM city");
$rows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Volunteer Profile Update</title>
  <?php include("bootstrap.php"); ?>
  <style>
    body {
      background-color: #a9c3bf;
    }
    .form-container {
      max-width: 400px;
      margin: auto;
      margin-top: 50px;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0px 0px 10px #888;
    }
    label {
      float: left;
      font-weight: bold;
    }
  </style>
</head>
<body>

<?php 
  if(isset($_SESSION['error'])){
    echo '<div class="alert alert-danger text-center" role="alert">';
    echo $_SESSION['error'];
    unset($_SESSION['error']);
    echo '</div>';
  }
?>

<div class="form-container">
  <form method="post">
    <div class="text-center">
      <img src="../images/index/logo.png" width="60" height="60" alt="Logo">
      <h4 class="mb-3">Edit Volunteer Profile</h4>
    </div>

    <div class="form-group">
      <label>Name</label>
      <input type="text" name="name" class="form-control" value="<?= $name ?>">
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="<?= $email ?>">
    </div>

    <div class="form-group">
      <label>Address</label>
      <input type="text" name="intrests" class="form-control" value="<?= $intrests ?>">
    </div>

    <div class="form-group">
      <label>Date of Birth</label>
      <input type="date" name="date" class="form-control" value="<?= $date ?>">
    </div>

    <div class="form-group">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" value="<?= $phone ?>">
    </div>

    <div class="form-group">
      <label>City</label>
      <select name="city" class="form-control">
        <?php
          foreach($rows as $row){
            $selected = ($row['city_id'] == $city) ? "selected" : "";
            echo "<option value='{$row['city_id']}' $selected>" . htmlentities($row['cname']) . "</option>";
          }
        ?>
      </select>
    </div>

    <div class="form-group text-center mt-4">
      <input type="submit" class="btn btn-primary" value="Submit">
      <input type="submit" class="btn btn-secondary" name="Cancel" value="Cancel">
    </div>
  </form>
</div>

</body>
</html>
