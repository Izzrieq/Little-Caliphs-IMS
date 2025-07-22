<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'developer') {
    header("Location: ../index.php");
    exit;
}

include '../includes/conn.php';

// Fetch branches
$branches = [];
$branch_q = $conn->query("SELECT name, address FROM branches");
while ($row = $branch_q->fetch_assoc()) {
    $branches[] = $row;
}

// Fetch contractors
$contractors = [];
$contractor_q = $conn->query("SELECT name FROM contractors");
while ($row = $contractor_q->fetch_assoc()) {
    $contractors[] = $row['name'];
}

// Dropdown options
$items = ['AC', 'CCTV', 'Fire Extinguisher', 'TV', 'WIFI'];
$unit_types = [
    'AC' => ['Wall Mounted', 'Cassette'],
    'CCTV' => ['Dome', 'Bullet'],
    'Fire Extinguisher' => ['CO2', 'Dry Powder'],
    'TV' => ['LED', 'LCD'],
    'WIFI' => ['Router', 'Access Point']
];

$item_details = [
    'CCTV' => [
        'brands' => ['Hikvision', 'Dahua', 'EZVIZ'],
        'models' => ['DS-2CD1023G0-I', 'HAC-T1A21', 'C3WN'],
        'capacities' => ['2MP', '5MP', '8MP']
    ],
    'AC' => [
        'brands' => ['Panasonic', 'Daikin', 'Midea'],
        'models' => ['CS-PN12WKH', 'FTKF35AV1', 'MSX-09CRN'],
        'capacities' => ['1HP', '1.5HP', '2HP']
    ],
    'Fire Extinguisher' => [
        'brands' => ['FireGuard', 'Chubb', 'Kid'],
        'models' => ['FG-CO2-9KG', 'CH-ABC-6KG', 'KID-CO2-2KG'],
        'capacities' => ['2kg', '6kg', '9kg']
    ],
    'TV' => [
        'brands' => ['Samsung', 'LG', 'Sony'],
        'models' => ['UA43T5300', '43LM5700', 'KD-43X7000G'],
        'capacities' => ['43 inch', '50 inch', '55 inch']
    ],
    'WIFI' => [
        'brands' => ['TP-Link', 'Asus', 'Netgear'],
        'models' => ['Archer C6', 'RT-AX55', 'R7000'],
        'capacities' => ['AX1800', 'AX3000', 'AX5400']
    ]
];

$service_types = ['Minor', 'Major'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Unit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        const unitTypes = <?= json_encode($unit_types) ?>;
        const branches = <?= json_encode($branches) ?>;
        const itemDetails = <?= json_encode($item_details) ?>;

        document.addEventListener("DOMContentLoaded", () => {
            const branchInput = document.getElementById("branchInput");
            const branchAddress = document.getElementById("branchAddress");

            branchInput.addEventListener("input", () => {
                const selected = branchInput.value.trim().toLowerCase();
                const match = branches.find(b => b.name.trim().toLowerCase() === selected);
                branchAddress.value = match ? match.address : '';
            });
        });

        function updateUnitOptions() {
            const item = document.getElementById('item').value;

            // Update Unit Type
            const unitSelect = document.getElementById('unit');
            unitSelect.innerHTML = '<option disabled selected>Select Unit</option>';
            if (unitTypes[item]) {
                unitTypes[item].forEach(unit => {
                    const opt = document.createElement("option");
                    opt.value = unit;
                    opt.text = unit;
                    unitSelect.appendChild(opt);
                });
            }

            // Update Brand
            const brandSelect = document.getElementById('brand');
            brandSelect.innerHTML = '<option disabled selected>Select Brand</option>';
            if (itemDetails[item]) {
                itemDetails[item].brands.forEach(brand => {
                    const opt = document.createElement("option");
                    opt.value = brand;
                    opt.text = brand;
                    brandSelect.appendChild(opt);
                });
            }

            // Update Model
            const modelSelect = document.getElementById('model');
            modelSelect.innerHTML = '<option disabled selected>Select Model</option>';
            if (itemDetails[item]) {
                itemDetails[item].models.forEach(model => {
                    const opt = document.createElement("option");
                    opt.value = model;
                    opt.text = model;
                    modelSelect.appendChild(opt);
                });
            }

            // Update Capacity
            const capacitySelect = document.getElementById('capacity');
            capacitySelect.innerHTML = '<option disabled selected>Select Capacity</option>';
            if (itemDetails[item]) {
                itemDetails[item].capacities.forEach(cap => {
                    const opt = document.createElement("option");
                    opt.value = cap;
                    opt.text = cap;
                    capacitySelect.appendChild(opt);
                });
            }
        }
    </script>
</head>

<body class="bg-gray-100 pt-32 px-6">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-xl font-bold mb-6">➕ Add New Unit</h2>

        <form action="save_unit.php" method="POST" class="space-y-4">

            <!-- Item -->
            <div>
                <label class="block font-medium">Item</label>
                <select name="item" id="item" required onchange="updateUnitOptions()" class="w-full p-2 border rounded">
                    <option disabled selected>Select Item</option>
                    <?php foreach ($items as $item): ?>
                        <option value="<?= $item ?>"><?= $item ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Unit Type -->
            <div>
                <label class="block font-medium">Unit Type</label>
                <select name="unit_type" id="unit" required class="w-full p-2 border rounded">
                    <option disabled selected>Select Unit</option>
                </select>
            </div>

            <!-- Brand -->
            <div>
                <label class="block font-medium">Brand</label>
                <select name="brand" id="brand" required class="w-full p-2 border rounded">
                    <option disabled selected>Select Brand</option>
                </select>
            </div>

            <!-- Model -->
            <div>
                <label class="block font-medium">Model</label>
                <select name="model" id="model" required class="w-full p-2 border rounded">
                    <option disabled selected>Select Model</option>
                </select>
            </div>

            <!-- Capacity -->
            <div>
                <label class="block font-medium">Capacity</label>
                <select name="capacity" id="capacity" required class="w-full p-2 border rounded">
                    <option disabled selected>Select Capacity</option>
                </select>
            </div>


            <!-- Branch Name -->
            <div>
                <label class="block font-medium">Branch Name</label>
                <input list="branchList" id="branchInput" name="branch_name" required placeholder="e.g., Little Caliph Seksyen 7" class="w-full p-2 border rounded">
                <datalist id="branchList">
                    <?php foreach ($branches as $b): ?>
                        <option value="<?= htmlspecialchars(trim($b['name'])) ?>">
                        <?php endforeach; ?>
                </datalist>
            </div>

            <!-- Branch Address -->
            <div>
                <label class="block font-medium">Branch Address</label>
                <input type="text" id="branchAddress" name="branch_address" required placeholder="Auto-filled after branch selection" class="w-full p-2 border rounded">
            </div>

            <!-- Location Description -->
            <div>
                <label class="block font-medium">Location Description</label>
                <input type="text" name="location_desc" required placeholder="e.g., Level G Room AC1" class="w-full p-2 border rounded">
            </div>

            <!-- Install Date -->
            <div>
                <label class="block font-medium">Install Date</label>
                <input type="date" name="install_date" required class="w-full p-2 border rounded">
            </div>

            <!-- Warranty Date -->
            <div>
                <label class="block font-medium">Warranty Date</label>
                <input type="date" name="warranty_date" required class="w-full p-2 border rounded">
            </div>

            <!-- Contractor Name -->
            <div>
                <label class="block font-medium">Contractor Name</label>
                <input list="contractorList" name="contractor" required placeholder="e.g., Ahmad Service Sdn Bhd" class="w-full p-2 border rounded">
                <datalist id="contractorList">
                    <?php foreach ($contractors as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>">
                        <?php endforeach; ?>
                </datalist>
            </div>

            <!-- Last Service Date -->
            <div>
                <label class="block font-medium">Last Service Date</label>
                <input type="date" name="last_service" required class="w-full p-2 border rounded">
            </div>

            <!-- Next Service Date -->
            <div>
                <label class="block font-medium">Incoming Service Date</label>
                <input type="date" name="next_service" required class="w-full p-2 border rounded">
            </div>

            <!-- Service Type -->
            <div>
                <label class="block font-medium">Service Type</label>
                <select name="service_type" required class="w-full p-2 border rounded">
                    <option disabled selected>Select Type</option>
                    <?php foreach ($service_types as $stype): ?>
                        <option value="<?= $stype ?>"><?= $stype ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button type="submit" class="bg-transparent hover:bg-green-600 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">Save Unit</button>
                <a href="../unit.php" class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-3 px-4 border border-red-500 hover:border-transparent rounded">Cancel</a>
            </div>

        </form>
    </div>
</body>

</html>