<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

include 'includes/conn.php';
$user = $_SESSION['user'];

// Get totals
$totalUnits = $conn->query("SELECT COUNT(*) AS count FROM units")->fetch_assoc()['count'];
$reportMonth = $conn->query("SELECT COUNT(*) AS count FROM reports WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetch_assoc()['count'];
$totalBranches = $conn->query("SELECT COUNT(DISTINCT branch_name) AS total FROM units")->fetch_assoc()['total'];

// Get monthly report stats (last 5 months)
$monthlyData = [];
for ($i = 5; $i >= 0; $i--) {
    $monthLabel = date("M", strtotime("-{$i} months"));
    $monthValue = date("n", strtotime("-{$i} months"));
    $yearValue = date("Y", strtotime("-{$i} months"));
    $count = $conn->query("SELECT COUNT(*) AS count FROM reports WHERE MONTH(created_at) = $monthValue AND YEAR(created_at) = $yearValue")->fetch_assoc()['count'];
    $monthlyData[] = ["month" => $monthLabel, "count" => $count];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <?php include 'templates/header.php'; ?>

    <div class="max-w-6xl mx-auto px-4 space-y-8 pt-32">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow p-6 text-center">
                <h3 class="text-gray-500 text-sm">Total Units</h3>
                <p class="text-2xl font-bold text-blue-600"><?= $totalUnits ?></p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 text-center">
                <h3 class="text-gray-500 text-sm">Reports This Month</h3>
                <p class="text-2xl font-bold text-green-600"><?= $reportMonth ?></p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 text-center">
                <h3 class="text-gray-500 text-sm">Total Branches</h3>
                <p class="text-2xl font-bold text-purple-600"><?= $totalBranches ?></p>
            </div>
        </div>

        <!-- Chart -->
        <div class="bg-white p-4 rounded shadow mt-6">
            <h2 class="text-lg font-semibold mb-4">Report Statistics (Last 5 Months)</h2>
            <div class="relative h-[300px] w-full">
                <canvas id="reportChart" class="!w-full !h-full"></canvas>
            </div>
        </div>


        <!-- Navigation Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-8">
            <!-- List of Units -->
            <a href="units.php"
                class="flex items-center justify-between gap-4 bg-white p-5 rounded-lg shadow hover:shadow-md transition duration-200 hover:bg-blue-50">
                <div>
                    <div class="text-blue-600 text-xl">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <h3 class="text-lg font-semibold mt-1">List of Units</h3>
                    <p class="text-sm text-gray-500">View and manage all units</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 text-lg"></i>
            </a>

            <!-- List of Branches -->
            <a href="branches.php"
                class="flex items-center justify-between gap-4 bg-white p-5 rounded-lg shadow hover:shadow-md transition duration-200 hover:bg-green-50">
                <div>
                    <div class="text-green-600 text-xl">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="text-lg font-semibold mt-1">List of Branches</h3>
                    <p class="text-sm text-gray-500">Explore all available branches</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 text-lg"></i>
            </a>

            <!-- List of Contractors -->
            <a href="contractors.php"
                class="flex items-center justify-between gap-4 bg-white p-5 rounded-lg shadow hover:shadow-md transition duration-200 hover:bg-yellow-50">
                <div>
                    <div class="text-yellow-600 text-xl">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h3 class="text-lg font-semibold mt-1">List of Contractors</h3>
                    <p class="text-sm text-gray-500">View all contractor details</p>
                </div>
                <i class="fas fa-arrow-right text-gray-400 text-lg"></i>
            </a>
        </div>

    </div>

    <script>
        const ctx = document.getElementById('reportChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($monthlyData, 'month')) ?>,
                datasets: [{
                    label: 'Report Count',
                    data: <?= json_encode(array_column($monthlyData, 'count')) ?>,
                    backgroundColor: '#3B82F6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>