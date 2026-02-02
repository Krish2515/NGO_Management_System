<?php 
require_once "../pdo.php";
session_start();

if (isset($_POST['username'])) {
    if (strlen($_POST['username']) > 0) {
        $stmt5 = $pdo->query("SELECT `username` FROM `donor_login` WHERE `username`= '".$_POST['username']."';");
        $rows5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows5) > 0) {
            $_SESSION['error'] = "Username Already Exists. Choose a different username";
            header('Location: donorSignup.php');
            return;
        }
        if (
            isset($_POST['name']) && isset($_POST['email']) && 
            isset($_POST['address']) && isset($_POST['city']) && isset($_POST['phone'])
        ) {
            if (
                strlen($_POST['name']) > 0 && strlen($_POST['email']) > 0 &&
                strlen($_POST['address']) > 0 && strlen($_POST['city']) > 0 &&
                strlen($_POST['phone']) > 0
            ) {
                $stmt5 = $pdo->query("SELECT `email` FROM `donor` WHERE `email`= '".$_POST['email']."';");
                $rows5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows5) > 0) {
                    $_SESSION['error'] = "Email Already Exists. Choose a different email";
                    header('Location: donorSignup.php');
                    return;
                }
                $stmt6 = $pdo->query("SELECT `phone` FROM `donor` WHERE `phone`= ".$_POST['phone']);
                $rows6 = $stmt6->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows6) > 0) {
                    $_SESSION['error'] = "Phone number Already Exists. Choose a different phone";
                    header('Location: donorSignup.php');
                    return;
                }
                $stmt = $pdo->prepare('INSERT INTO donor (name, email, address, city_id, phone) 
                                        VALUES (:nm, :em, :add, :ci, :ph)');
                $stmt->execute(array(
                    ':nm' => $_POST['name'],
                    ':em' => $_POST['email'],
                    ':add' => $_POST['address'],
                    ':ci' => $_POST['city'],
                    ':ph' => $_POST['phone']
                ));

                $stmt3 = $pdo->query("SELECT * FROM `donor` WHERE `donor_id`= (SELECT MAX(`donor_id`) FROM `donor`)");
                $rows2 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                if (($_POST['email'] != $rows2[0]['email']) || ($_POST['phone'] != $rows2[0]['phone'])) {
                    $_SESSION['error'] = "Something went wrong, please try again";
                    header('Location: donorSignup.php');
                    return;
                }

                $stmt1 = $pdo->prepare('INSERT INTO donor_login(username, password, donor_id) VALUES (:ur, :pw, :dn)');
                $stmt1->execute(array(
                    ':ur' => $_POST['username'],
                    ':pw' => $_POST['password'],
                    ':dn' => $rows2[0]['donor_id']
                ));

                $_SESSION['success'] = "Record inserted";
                header('Location: ../login/donorLogin.php');
                return;
            } else {
                $_SESSION['error'] = "Everything is required";
                header("Location: donorSignup.php");  
                return;         
            }
        }
    }
}

$stmt3 = $pdo->query("SELECT * FROM city");
$rows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Signup</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <h3>Donor Signup</h3>

    <?php 
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger text-center" role="alert">'.$_SESSION['error'].'</div>';
            unset($_SESSION['error']);
        }
    ?>

    <form method="post" action="donorSignup.php">
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
            <label>Address</label>
            <input type="text" name="address" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="form-group">
            <label>City</label>
            <select name="city" class="form-control" required>
                <option value="">-- Select City --</option>
                <?php foreach($rows as $row): ?>
                    <option value="<?= $row['city_id'] ?>"><?= htmlentities($row['cname']) ?></option>
                <?php endforeach; ?>
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
