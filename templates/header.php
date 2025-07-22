<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <title>IMS NFC</title>
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-white shadow fixed top-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-5">
                <img src="assets/img/LC_logo.png" alt="Logo" class="h-10 sm:h-14 lg:h-16 w-auto">
                <span class="font-bold text-xl sm:text-2xl lg:text-3xl tracking-tight">Inventory Management System</span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6 text-base lg:text-lg">
                <a href="dashboard.php" class="hover:text-blue-600">Dashboard</a>
                <a href="unit.php" class="hover:text-blue-600">Unit</a>
                <a href="reports-log.php" class="hover:text-blue-600">Reports</a>
                <a href="service-history.php" class="hover:text-blue-600">Service History</a>
                <a href="auth/logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
            </div>

            <!-- Mobile Hamburger -->
            <div class="md:hidden">
                <button id="mobile-menu-btn" class="text-gray-700 focus:outline-none">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Slide-in Mobile Menu -->
    <div id="mobile-menu" class="fixed top-0 right-0 h-full w-64 bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50 md:hidden">
        <div class="flex justify-between items-center p-4 border-b">
            <span class="font-semibold text-lg">Menu</span>
            <button id="close-menu" class="text-gray-600 hover:text-red-500 text-xl">✕</button>
        </div>
        <div class="p-4 space-y-4 text-base">
            <p class="text-gray-700">Hi, <?= htmlspecialchars($user['username']) ?></p>
            <a href="dashboard.php" class="block hover:text-blue-600">Dashboard</a>
            <a href="unit.php" class="block hover:text-blue-600">Unit</a>
            <a href="reports-log.php" class="block hover:text-blue-600">Reports</a>
            <a href="service-history.php" class="hover:text-blue-600">Service History</a>
            <a href="auth/logout.php" class="block text-red-500 font-medium hover:underline">Logout</a>
        </div>
    </div>

    <!-- Backdrop Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 hidden z-40 md:hidden"></div>

    <!-- Script -->
    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const closeBtn = document.getElementById('close-menu');
        const overlay = document.getElementById('overlay');

        btn.addEventListener('click', () => {
            menu.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
        });

        closeBtn.addEventListener('click', () => {
            menu.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        });

        overlay.addEventListener('click', () => {
            menu.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>