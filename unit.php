<?php
session_start();
include 'includes/conn.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'developer') {
    header("Location: index.php");
    exit;
}

$sql = "SELECT 
        u.*, 
        r.name AS room, 
        o.name AS office, 
        l.address AS location
        FROM units u
        JOIN rooms r ON u.room_id = r.id
        JOIN offices o ON r.office_id = o.id
        JOIN locations l ON o.location_id = l.id
        ORDER BY u.id DESC";

$result = $conn->query($sql);

// For search suggestions
$units_array = [];
while ($row = $result->fetch_assoc()) {
    $units_array[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Units</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .scroll-shadow-wrapper {
            position: relative;
        }

        .scroll-shadow-wrapper::after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 40px;
            height: 100%;
            background: linear-gradient(to left, rgba(0, 0, 0, 0.1), transparent);
            pointer-events: none;
        }

        .scroll-area {
            -webkit-overflow-scrolling: touch;
        }
    </style>
    <?php include 'templates/header.php'; ?>
</head>

<body class="bg-gray-100">
    <div class="pt-32 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto bg-white p-4 sm:p-6 rounded shadow">
            <div class="flex flex-nowrap justify-between items-center mb-4 gap-3 w-full">

                <!-- Search Input with Icon -->
                <div class="relative w-full sm:w-1/2">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" id="searchInput"
                        class="w-full pl-10 pr-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm"
                        placeholder="Search item, unit, brand, model, or office...">
                    <ul id="suggestions"
                        class="absolute z-10 w-full bg-white border border-gray-300 mt-1 rounded shadow hidden max-h-60 overflow-auto text-sm"></ul>
                </div>

                <!-- Add Unit Button with Icon -->
                <a href="func/add_unit.php"
                    class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    <i class="fas fa-plus"></i>
                    <span class="hidden sm:inline">Add Unit</span>
                </a>
            </div>



            <!-- Horizontal scroll with scroll shadow -->
            <div class="scroll-shadow-wrapper rounded border bg-white overflow-hidden">
                <div class="overflow-x-auto scroll-area">
                    <table class="min-w-full text-sm text-left whitespace-nowrap">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="p-2">#</th>
                                <th class="p-2">Item</th>
                                <th class="p-2">Unit</th>
                                <th class="p-2">Brand</th>
                                <th class="p-2">Model</th>
                                <th class="p-2">Capacity</th>
                                <th class="p-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach ($units_array as $u): ?>
                                <tr id="row-<?= $u['id'] ?>" class="border-t hover:bg-gray-50">
                                    <td class="p-2"><?= $i++ ?></td>
                                    <td class="p-2"><?= $u['item'] ?? '-' ?></td>
                                    <td class="p-2"><?= $u['unit_type'] ?></td>
                                    <td class="p-2"><?= $u['brand'] ?></td>
                                    <td class="p-2"><?= $u['model'] ?></td>
                                    <td class="p-2"><?= $u['capacity'] ?? '-' ?></td>
                                    <td class="p-2">
                                        <button onclick="toggleDetails(<?= $u['id'] ?>)"
                                            class="bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                                            Expand
                                        </button>
                                        <a href="func/delete_unit.php?id=<?= $u['id'] ?>"
                                            onclick="return confirm('Are you sure you want to delete this unit?')"
                                            class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded inline-flex items-center gap-2">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </td>
                                </tr>

                                <!-- Expandable Row -->
                                <tr id="details-<?= $u['id'] ?>" class="hidden bg-gray-50">
                                    <td colspan="7" class="pt-2 p-4">
                                        <div class="border-t border-gray-300 my-2"></div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                                            <div class="break-words whitespace-normal"><strong>Location:</strong> <?= $u['location'] ?></div>
                                            <div><strong>Branch Name:</strong> <?= $u['office'] ?></div>
                                            <div><strong>Room:</strong> <?= $u['room'] ?></div>
                                            <div><strong>Install Date:</strong> <?= date("d/m/y", strtotime($u['install_date'])) ?></div>
                                            <div><strong>Warranty Date:</strong> <?= date("d/m/y", strtotime($u['warranty_date'])) ?? '-' ?></div>
                                            <div><strong>Contractor:</strong> <?= $u['contractor'] ?? '-' ?></div>
                                            <div><strong>Last Service:</strong> <?= date("d/m/y", strtotime($u['last_service'])) ?? '-' ?></div>
                                            <div><strong>Next Service:</strong> <?= date("d/m/y", strtotime($u['next_service'])) ?? '-' ?></div>
                                            <div><strong>Service Type:</strong> <?= $u['service_type'] ?? '-' ?></div>

                                            <!-- NFC Status -->
                                            <div>
                                                <strong>NFC:</strong>
                                                <?php if ($u['nfc_written']): ?>
                                                    <span class="text-green-600 font-semibold">✅ Written</span>
                                                <?php else: ?>
                                                    <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
                                                        onclick="handleWriteNFC(<?= $u['id'] ?>)">
                                                        <i class="fas fa-pen"></i>
                                                        Write NFC
                                                    </button>
                                                <?php endif; ?>
                                            </div>

                                            <!-- QR Code -->
                                            <div>
                                                <strong>QR Code:</strong><br>
                                                <button onclick="toggleQRCode(<?= $u['id'] ?>)" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow">
                                                    Show QR Code
                                                </button>

                                                <div id="qr-<?= $u['id'] ?>" class="mt-2 hidden">
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=http://192.168.50.84:8081/Little-Caliphs-IMS/report-form.php?unit=<?= $u['id'] ?>"
                                                        alt="QR Code for Unit <?= $u['id'] ?>" class="mt-1 rounded border shadow">
                                                    <br>
                                                    <button onclick="printQRCode('qr-<?= $u['id'] ?>')" class="mt-2 bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded">
                                                        Print QR
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleQRCode(id) {
            const qrDiv = document.getElementById('qr-' + id);
            qrDiv.classList.toggle('hidden');
        }

        function printQRCode(id) {
            const qrDiv = document.getElementById(id);
            const clonedDiv = qrDiv.cloneNode(true);

            // Remove the print button if it exists
            const printBtn = clonedDiv.querySelector('button');
            if (printBtn) {
                printBtn.remove();
            }

            // Extract the QR image
            const qrImg = clonedDiv.querySelector('img');
            const imgSrc = qrImg ? qrImg.src : null;

            if (!imgSrc) {
                alert("QR image not found.");
                return;
            }

            const printWindow = window.open('', '', 'width=400,height=400');
            printWindow.document.write(`
        <html>
        <head>
            <title>Print QR Code</title>
            <style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                img {
                    max-width: 100%;
                    height: auto;
                }
            </style>
        </head>
        <body>
            <img id="qr-print-image" src="${imgSrc}" alt="QR Code">
        </body>
        </html>
    `);
            printWindow.document.close();

            // Wait for the image to load before printing
            printWindow.onload = () => {
                const qrImage = printWindow.document.getElementById('qr-print-image');
                qrImage.onload = () => {
                    printWindow.focus();
                    printWindow.print();
                    printWindow.close();
                };
            };
        }


        function toggleDetails(id) {
            const row = document.getElementById('details-' + id);
            row.classList.toggle('hidden');
        }

        function fallbackCopyTextToClipboard(text) {
            const textarea = document.createElement("textarea");
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            try {
                document.execCommand('copy');
                alert("🔗 URL copied to clipboard.");
            } catch (err) {
                alert("❗ Copy failed. Please copy manually:\n" + text);
            }
            document.body.removeChild(textarea);
        }

        async function handleWriteNFC(unitId) {
            const url = `http://192.168.50.84:8081/Little-Caliphs-IMS/report-form.php?unit=${unitId}`;
            if ('NDEFWriter' in window) {
                try {
                    if (!confirm(`Write NFC tag for this URL?\n\n${url}`)) return;
                    const writer = new NDEFWriter();
                    await writer.write(url);
                    alert("✅ NFC tag written successfully!");
                    fetch(`func/mark_nfc_written.php?unit_id=${unitId}`);
                    location.reload();
                } catch (err) {
                    console.error(err);
                    alert("❌ Failed to write NFC using browser: " + err);
                }
            } else {
                fallbackCopyTextToClipboard(url);
                const ua = navigator.userAgent.toLowerCase();
                let appLink = "https://www.wakdev.com/apps/nfc-tools.html";
                if (ua.includes('android')) {
                    appLink = "intent://#Intent;package=com.wakdev.wdnfc;scheme=launch;end";
                } else if (ua.includes('iphone') || ua.includes('ipad')) {
                    appLink = "https://apps.apple.com/app/nfc-tools/id1252962749";
                }
                window.open(appLink, "_blank");
            }
        }

        // Search functionality
        const searchInput = document.getElementById("searchInput");
        const suggestionsBox = document.getElementById("suggestions");
        const unitData = <?= json_encode($units_array) ?>;

        searchInput.addEventListener("input", function() {
            const keyword = this.value.toLowerCase();
            suggestionsBox.innerHTML = "";

            if (!keyword) {
                suggestionsBox.classList.add("hidden");
                return;
            }

            const matches = unitData.filter(u =>
                (u.item || '').toLowerCase().includes(keyword) ||
                (u.unit_type || '').toLowerCase().includes(keyword) ||
                (u.brand || '').toLowerCase().includes(keyword) ||
                (u.model || '').toLowerCase().includes(keyword) ||
                (u.office || '').toLowerCase().includes(keyword)
            );

            if (matches.length === 0) {
                suggestionsBox.classList.add("hidden");
                return;
            }

            matches.slice(0, 10).forEach(u => {
                const li = document.createElement("li");
                li.textContent = `${u.item} - ${u.unit_type} (${u.brand} ${u.model}) - ${u.office}`;
                li.className = "cursor-pointer px-4 py-2 hover:bg-blue-100";
                li.onclick = () => {
                    const targetRow = document.getElementById("row-" + u.id);
                    if (targetRow) {
                        targetRow.scrollIntoView({
                            behavior: "smooth",
                            block: "center"
                        });
                        targetRow.classList.add("bg-yellow-100");
                        setTimeout(() => targetRow.classList.remove("bg-yellow-100"), 1500);
                    }
                    searchInput.value = "";
                    suggestionsBox.classList.add("hidden");
                };
                suggestionsBox.appendChild(li);
            });

            suggestionsBox.classList.remove("hidden");
        });

        document.addEventListener("click", function(e) {
            if (!suggestionsBox.contains(e.target) && e.target !== searchInput) {
                suggestionsBox.classList.add("hidden");
            }
        });
    </script>
</body>

</html>