<?php
session_start();
include 'includes/conn.php';

$sql = "
SELECT sh.*, u.item, u.brand, u.model, u.branch_name, u.branch_address
FROM services_history sh
JOIN units u ON sh.unit_id = u.unit_id
ORDER BY sh.service_date DESC
";
$result = $conn->query($sql);
$grouped = [];
while ($row = $result->fetch_assoc()) {
    $grouped[$row['unit_id']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Service History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0fa5f6b.js" crossorigin="anonymous"></script>
    <script>
        function toggleDetails(id) {
            const row = document.getElementById('details-' + id);
            row.classList.toggle('hidden');
        }

        function filterServices() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('.service-row').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(input) ? '' : 'none';
            });
        }
    </script>
</head>

<body class="bg-gray-100">
    <?php include 'templates/header.php'; ?>
    <div class="pt-32 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded shadow">
            <div class="flex flex-nowrap justify-between items-center mb-4 gap-3 w-full">

                <!-- Search Input with Icon -->
                <div class="relative w-full sm:w-1/2">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" id="searchInput" onkeyup="filterServices()"
                        class="w-full pl-10 pr-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm"
                        placeholder="Search item, unit, brand, or branch...">
                </div>
            </div>

            <div class="scroll-shadow-wrapper rounded border bg-white overflow-hidden">
                <div class="overflow-x-auto scroll-area">
                    <table class="min-w-full text-sm text-left whitespace-nowrap">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="p-2">Unit</th>
                                <th class="p-2">Branch</th>
                                <th class="p-2">Item</th>
                                <th class="p-2">Last Service</th>
                                <th class="p-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grouped as $unit_id => $services): ?>
                                <?php $latest = $services[0]; ?>
                                <tr class="border-t hover:bg-gray-50 service-row">
                                    <td class="p-2"><?= htmlspecialchars($unit_id) ?></td>
                                    <td class="p-2"><?= htmlspecialchars($latest['branch_name']) ?></td>
                                    <td class="p-2"><?= htmlspecialchars($latest['item']) ?></td>
                                    <td class="p-2"><?= date('d/m/y', strtotime($latest['service_date'])) ?></td>
                                    <td class="p-2">
                                        <button onclick="toggleDetails('<?= $unit_id ?>')" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                            View Details
                                        </button>
                                    </td>
                                </tr>

                                <!-- Hidden Expandable Row -->
                                <tr id="details-<?= $unit_id ?>" class="hidden bg-gray-50">
                                    <td colspan="5" class="p-4">
                                        <div class="text-sm text-gray-700">
                                            <strong>Brand:</strong> <?= $latest['brand'] ?> |
                                            <strong>Model:</strong> <?= $latest['model'] ?> |
                                            <strong>Address:</strong> <?= $latest['branch_address'] ?>
                                        </div>
                                        <div class="mt-4">
                                            <table class="min-w-full text-sm border mt-2">
                                                <thead class="bg-gray-100 text-gray-600 uppercase">
                                                    <tr>
                                                        <th class="px-4 py-2">Date</th>
                                                        <th class="px-4 py-2">Contractor</th>
                                                        <th class="px-4 py-2">Service Type</th>
                                                        <th class="px-4 py-2">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($services as $s): ?>
                                                        <tr class="border-t">
                                                            <td class="px-4 py-2"><?= date('d/m/y', strtotime($s['service_date'])) ?></td>
                                                            <td class="px-4 py-2"><?= htmlspecialchars($s['contractor']) ?></td>
                                                            <td class="px-4 py-2"><?= htmlspecialchars($s['service_type']) ?></td>
                                                            <td class="px-4 py-2"><?= htmlspecialchars($s['remarks']) ?></td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>