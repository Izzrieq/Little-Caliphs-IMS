<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'includes/conn.php';

$sql = "
    SELECT rh.*, u.item, u.brand, u.model, u.branch_name, u.branch_address
    FROM reports_history rh
    LEFT JOIN units u ON rh.unit_id = u.unit_id
    ORDER BY rh.created_at DESC
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
    <title>Reports Log</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleDetails(id) {
            const content = document.getElementById('details-' + id);
            const arrow = document.getElementById('arrow-' + id);
            content.classList.toggle('max-h-0');
            content.classList.toggle('max-h-[1000px]');
            content.classList.toggle('opacity-0');
            content.classList.toggle('opacity-100');
            arrow.classList.toggle('rotate-90');
        }

        function filterReports() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('.report-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(search) ? 'block' : 'none';
            });
        }

        function sortReports() {
            const sortBy = document.getElementById('sortSelect').value;
            const container = document.getElementById('reportContainer');
            const cards = Array.from(container.children);
            cards.sort((a, b) => {
                const aDate = new Date(a.getAttribute('data-created'));
                const bDate = new Date(b.getAttribute('data-created'));
                return sortBy === 'newest' ? bDate - aDate : aDate - bDate;
            });
            container.innerHTML = '';
            cards.forEach(card => container.appendChild(card));
        }
    </script>
</head>

<body class="bg-gray-100 min-h-screen">
    <?php include 'templates/header.php'; ?>

    <div class="pt-32 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded shadow">
            <h1 class="text-3xl font-bold mb-6 text-blue-600 text-center">Reports Log</h1>

            <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
                <input id="searchInput" type="text" onkeyup="filterReports()" placeholder="Search reports..." class="w-full md:w-1/2 p-2 border border-gray-300 rounded-lg shadow-sm" />

                <select id="sortSelect" onchange="sortReports()" class="p-2 border border-gray-300 rounded-lg shadow-sm">
                    <option value="newest">Sort: Newest First</option>
                    <option value="oldest">Sort: Oldest First</option>
                </select>
            </div>

            <?php if (empty($grouped)): ?>
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded-md text-center">
                    No reports found.
                </div>
            <?php endif; ?>

            <div id="reportContainer">
                <?php foreach ($grouped as $unit_id => $reports): ?>
                    <?php $unit = $reports[0]; ?>
                    <div class="report-card bg-white shadow-md rounded-lg mb-4" data-created="<?= $unit['created_at'] ?>">
                        <div class="flex items-center justify-between bg-blue-500 text-white p-4 rounded-t-lg cursor-pointer" onclick="toggleDetails('<?= $unit_id ?>')">
                            <div>
                                <p><strong>Unit ID:</strong> <?= htmlspecialchars($unit_id) ?></p>
                                <p><strong>Item:</strong> <?= htmlspecialchars($unit['item']) ?> |
                                    <strong>Brand:</strong> <?= htmlspecialchars($unit['brand']) ?> |
                                    <strong>Model:</strong> <?= htmlspecialchars($unit['model']) ?>
                                </p>
                                <p><strong>Branch:</strong> <?= htmlspecialchars($unit['branch_name']) ?> - <?= htmlspecialchars($unit['branch_address']) ?></p>
                            </div>
                            <svg id="arrow-<?= $unit_id ?>" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <div id="details-<?= $unit_id ?>" class="transition-all duration-500 ease-in-out overflow-hidden max-h-0 opacity-0 bg-white">
                            <div class="overflow-x-auto p-4">
                                <table class="min-w-full text-sm text-left border">
                                    <thead class="bg-gray-100 text-gray-600 uppercase">
                                        <tr>
                                            <th class="px-4 py-2">Date Reported</th>
                                            <th class="px-4 py-2">Resolved At</th>
                                            <th class="px-4 py-2">Reporter</th>
                                            <th class="px-4 py-2">Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($reports as $report): ?>
                                            <tr class="border-t hover:bg-gray-50">
                                                <td class="px-4 py-2"><?= date('d/m/y H:i', strtotime($report['created_at'])) ?></td>
                                                <td class="px-4 py-2">
                                                    <?= $report['resolved_at'] ? date('d/m/y H:i', strtotime($report['resolved_at'])) : 'Not Resolved' ?>
                                                </td>
                                                <td class="px-4 py-2"><?= htmlspecialchars($report['reporter_name']) ?></td>
                                                <td class="px-4 py-2 whitespace-pre-wrap"><?= nl2br(htmlspecialchars($report['message'])) ?></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</body>

</html>