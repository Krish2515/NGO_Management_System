<?php 
require_once "../pdo.php";
session_start();
if(!isset($_SESSION['donor_id'])){
    die("Login first");
}
  if(isset($_POST['Cancel'])){
    header('Location: ../index.php');
    return;
  }


    if(isset($_POST['name'])&&isset($_POST['email'])&&isset($_POST['address'])&&isset($_POST['city'])&&isset($_POST['phone'])){
        if((strlen($_POST['name'])>0)&&(strlen($_POST['email'])>0)&&(strlen($_POST['address'])>0)&&(strlen($_POST['city'])>0)&&(strlen($_POST['phone'])>0)){           
        $stmt = $pdo->prepare( "UPDATE `donor` SET  `email`= :em ,`address`= :add ,`city_id`= :ci ,`phone`= :ph ,`name`= :nm WHERE `donor_id` =". $_SESSION['donor_id']);
            $stmt->execute(array(
            ':nm' => $_POST['name'],
            ':em' => $_POST['email'],
            ':add' => $_POST['address'],
            ':ci' => $_POST['city'],
            ':ph' => $_POST['phone'])
            );
            $_SESSION['success'] = "Record inserted";
            header('Location: ../index.php');
        }
        else{
            $_SESSION['error'] = "everything Is Required";
            header("Location: donorUpdate.php");  
            return;         
        }
     }
    

$stmt4 = $pdo->query("SELECT * FROM `donor` WHERE `donor_id`=". $_SESSION['donor_id']);
$rows3 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

$name = htmlentities($rows3[0]['name']);
$email = htmlentities($rows3[0]['email']);
$address = htmlentities($rows3[0]['address']);
$city = htmlentities($rows3[0]['city_id']);
$phone = htmlentities($rows3[0]['phone']);
     
$stmt3 = $pdo->query("SELECT * FROM city");
$rows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DONOR UPDATE</title>
    <?php include("bootstrap.php"); ?>

</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh; background-color: #b8c7c3;">
    <?php 
        if(isset($_SESSION['error'])){
            echo '<div class="alert alert-danger text-center position-absolute top-0 w-100" role="alert">';
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            echo '</div>';
        }
    ?>

    <form method="post" class="bg-white p-5 rounded shadow-lg" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <img src="../images/index/logo.png" width="60" height="60" alt="Logo">
            <h4 class="mt-3">Edit Donor Profile</h4>
        </div>

        <div class="form-group mb-3">
            <label for="name" class="fw-bold">Name</label>
            <input type="text" class="form-control" name="name" value="<?= $name ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="email" class="fw-bold">Email</label>
            <input type="email" class="form-control" name="email" value="<?= $email ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="address" class="fw-bold">Address</label>
            <input type="text" class="form-control" name="address" value="<?= $address ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="phone" class="fw-bold">Phone</label>
            <input type="text" class="form-control" name="phone" value="<?= $phone ?>" required>
        </div>

        <div class="form-group mb-4">
            <label for="city" class="fw-bold">City</label>
            <select class="form-select" name="city" required>
                <?php
                foreach ($rows as $row) {
                    echo '<option value="'.$row['city_id'].'"';
                    if ($row['city_id'] == $city) echo ' selected';
                    echo '>'.htmlentities($row['cname']).'</option>';
                }
                ?>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary w-45">Submit</button>
            <button type="submit" name="Cancel" class="btn btn-secondary w-45">Cancel</button>
        </div>
    </form>
</body>

</html>