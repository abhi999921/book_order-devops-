<?php
session_start();

if (isset($_POST['username']) && isset($_POST['pwd'])) {
    $username = $_POST['username'];
    $pwd = $_POST['pwd'];

    include "connectDB.php";
    
    $sql = "SELECT * FROM users WHERE UserName = :username AND Password = :pwd;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':username' => $username,
        ':pwd' => $pwd       
    ));

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $_SESSION['id'] = $row['UserID'];
        }
        header("Location: index.php");
        exit(); // Ensure script stops executing after the redirect
    } else {
        header("Location: login.php?errcode=1");
        exit(); // Ensure script stops executing after the redirect
    }
}
?>
