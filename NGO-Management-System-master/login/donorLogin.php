<?php
session_start();
require_once "../pdo.php";

if (isset($_POST['cancel'])) {
    header('Location: ../index.php');
    return;
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    if (strlen($_POST['username']) > 0 && strlen($_POST['password']) > 0) {

        // ⚠️ For learning purpose (can be secured later)
        $stmt = $pdo->query(
            "SELECT donor_id FROM donor_login 
             WHERE username = '" . $_POST['username'] . "' 
             AND password = '" . $_POST['password'] . "'"
        );

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $_SESSION['donor_id'] = $row['donor_id'];
            $_SESSION['role'] = 1;
            header("Location: ../index.php");
            return;
        } else {
            $_SESSION['error'] = "Wrong Username or Password";
            header("Location: donorLogin.php");
            return;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor Login</title>

    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #a3c2c2; /* SAME AS DONOR LIST */
        }

        .login-card {
            max-width: 600px;
            margin: 80px auto;
        }

        .table thead {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="login-card card shadow-lg rounded">
        <div class="card-body">

            <h3 class="text-center font-weight-bold mb-4">Donor Login</h3>

            <?php
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success text-center">'
                    . $_SESSION['success'] .
                    '</div>';
                unset($_SESSION['success']);
            }

            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger text-center">'
                    . $_SESSION['error'] .
                    '</div>';
                unset($_SESSION['error']);
            }
            ?>

            <form method="post">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th colspan="2">Login Credentials</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td width="30%"><strong>Username</strong></td>
                            <td>
                                <input type="text"
                                       name="username"
                                       class="form-control"
                                       required>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>Password</strong></td>
                            <td>
                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       required>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <button type="submit"
                                        class="btn btn-secondary px-4">
                                    Login
                                </button>

                                <button type="submit"
                                        name="cancel"
                                        class="btn btn-dark px-4 ml-2">
                                    Cancel
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>

            <div class="text-center mt-3">
                <a href="../signup/donorSignup.php" class="btn btn-link">
                    New Donor? Sign Up
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
