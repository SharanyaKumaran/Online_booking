<?php
session_start();

$validEmail = 'admin@gmail.com';
$validPassword = 'password';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    
    if ($email === $validEmail && $password === $validPassword) {
        $_SESSION['user_id'] = 1; 
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid email or password.";
    }
}
?>
