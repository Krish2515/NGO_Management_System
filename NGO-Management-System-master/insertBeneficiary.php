<?php
require_once "pdo.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name      = $_POST['name'];
    $age       = $_POST['age'];
    $gender    = $_POST['gender'];
    $contact   = $_POST['contact'];
    $email     = $_POST['email'];
    $address   = $_POST['address'];
    $city      = $_POST['city'];
    $projectId = $_POST['project_id'];

    try {
        $sql = "INSERT INTO beneficiaries 
                (name, age, gender, contact, email, address, city, project_id) 
                VALUES (:name, :age, :gender, :contact, :email, :address, :city, :project_id)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'      => $name,
            ':age'       => $age,
            ':gender'    => $gender,
            ':contact'   => $contact,
            ':email'     => $email,
            ':address'   => $address,
            ':city'      => $city,
            ':project_id'=> $projectId
        ]);

        // Redirect to view page after success
        $_SESSION['success'] = "Beneficiary added successfully!";
        header("Location: viewBeneficiaries.php");
        exit;

    } catch (Exception $e) {
        echo "Error inserting data: " . $e->getMessage();
    }
} else {
    echo "Invalid Request!";
}
?>
