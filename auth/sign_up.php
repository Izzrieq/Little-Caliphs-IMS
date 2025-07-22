<?php
include '../includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // 'user' or 'developer'

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Account created! You can now login.'); window.location.href='../index.php';</script>";
    } else {
        echo "<script>alert('Username already exists or error occurred.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <div class="flex justify-center mb-6">
            <img src="../assets/img/LC_logo.png" alt="Logo" class="h-auto w-auto object-contain">
        </div>
        <h2 class="text-2xl font-bold text-center mb-6">Create an Account</h2>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Username</label>
                <input name="username" required class="w-full p-2 border border-gray-300 rounded" placeholder="Username">
            </div>
            <div>
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="password" required class="w-full p-2 border border-gray-300 rounded" placeholder="Password">
            </div>
            <div>
                <label class="block text-sm font-medium">Role</label>
                <select name="role" class="w-full p-2 border border-gray-300 rounded">
                    <option value="user">User</option>
                    <option value="developer">Developer</option>
                </select>
            </div>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Sign Up</button>
        </form>

        <p class="text-center text-sm mt-4">
            Already have an account?
            <a href="../index.php" class="text-blue-600 hover:underline">Login</a>
        </p>
    </div>

</body>

</html>