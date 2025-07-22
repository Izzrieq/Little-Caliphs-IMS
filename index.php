<?php
session_start();
$loginSuccess = isset($_SESSION['login_success']);
unset($_SESSION['login_success']); // reset flag after showing
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IMS NFC Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Optional: smooth transition for success alert
        document.addEventListener('DOMContentLoaded', () => {
            const alert = document.getElementById('successAlert');
            if (alert) {
                setTimeout(() => {
                    alert.classList.add('opacity-0');
                }, 3000);
            }
        });
    </script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg relative">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img src="assets/img/LC_logo.png" alt="Logo" class="h-auto w-auto object-contain">
        </div>

        <!-- Login Success Alert -->
        <?php if ($loginSuccess): ?>
            <div id="successAlert" class="bg-green-100 text-green-800 p-3 rounded mb-4 transition-opacity duration-700">
                🎉 Login Successful!
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="auth/login.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Username</label>
                <input name="username" required class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter your username">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Enter your password">
            </div>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-300">Login</button>
        </form>

        <!-- Sign Up Link -->
        <p class="text-center text-sm mt-4 text-gray-600">
            Don’t have an account?
            <a href="auth/sign_up.php" class="text-blue-600 hover:underline">Sign up</a>
        </p>

    </div>

</body>

</html>