<?php
session_start();
include '../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        $_SESSION['login_success'] = true;
        header("Location: ../dashboard.php");
        exit;
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href='../index.php';</script>";
    }
}
