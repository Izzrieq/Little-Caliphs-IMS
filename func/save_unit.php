<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'developer') {
    header("Location: ../index.php");
    exit;
}

include '../includes/conn.php';

// Sanitize and fetch input
$item            = $_POST['item'];
$unit_type       = $_POST['unit_type'];
$brand           = $_POST['brand'];
$model           = $_POST['model'];
$capacity        = $_POST['capacity'];
$branch_name     = $_POST['branch_name'];
$branch_address  = $_POST['branch_address'];
$location_desc   = $_POST['location_desc'];
$install_date    = $_POST['install_date'];
$warranty_date   = $_POST['warranty_date'];
$contractor_name = $_POST['contractor'];
$last_service    = $_POST['last_service'];
$next_service    = $_POST['next_service'];
$service_type    = $_POST['service_type'];

$prefixMap = [
    'AC' => 'A',
    'CCTV' => 'C',
    'Fire Extinguisher' => 'F',
    'WIFI' => 'W',
    'TV' => 'T'
];
$prefix = isset($prefixMap[$item]) ? $prefixMap[$item] : 'U';

// Get last unit_id for the same prefix
$stmt = $conn->prepare("SELECT unit_id FROM units WHERE unit_id LIKE CONCAT(?, '%') ORDER BY unit_id DESC LIMIT 1");
$stmt->bind_param("s", $prefix);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $lastNumber = intval(substr($row['unit_id'], 1));
    $nextNumber = $lastNumber + 1;
} else {
    $nextNumber = 1;
}

$unit_id = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
// === 1. Insert or get contractor ===
$stmt = $conn->prepare("SELECT id FROM contractors WHERE name = ?");
$stmt->bind_param("s", $contractor_name);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $contractor_id = $row['id'];
} else {
    $stmt = $conn->prepare("INSERT INTO contractors (name) VALUES (?)");
    $stmt->bind_param("s", $contractor_name);
    $stmt->execute();
    $contractor_id = $stmt->insert_id;
}

// === 2. Insert or get location ===
$stmt = $conn->prepare("SELECT id FROM locations WHERE address = ?");
$stmt->bind_param("s", $branch_address);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $location_id = $row['id'];
} else {
    $stmt = $conn->prepare("INSERT INTO locations (address) VALUES (?)");
    $stmt->bind_param("s", $branch_address);
    $stmt->execute();
    $location_id = $stmt->insert_id;
}

// === 3. Insert or get office ===
$stmt = $conn->prepare("SELECT id FROM offices WHERE name = ? AND location_id = ?");
$stmt->bind_param("si", $branch_name, $location_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $office_id = $row['id'];
} else {
    $stmt = $conn->prepare("INSERT INTO offices (name, location_id) VALUES (?, ?)");
    $stmt->bind_param("si", $branch_name, $location_id);
    $stmt->execute();
    $office_id = $stmt->insert_id;
}

// === 4. Insert or get room ===
$stmt = $conn->prepare("SELECT id FROM rooms WHERE name = ? AND office_id = ?");
$stmt->bind_param("si", $location_desc, $office_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $room_id = $row['id'];
} else {
    $stmt = $conn->prepare("INSERT INTO rooms (name, office_id) VALUES (?, ?)");
    $stmt->bind_param("si", $location_desc, $office_id);
    $stmt->execute();
    $room_id = $stmt->insert_id;
}

// === 5. Insert unit (using contractor_id now) ===
$stmt = $conn->prepare("INSERT INTO units (
    unit_id, room_id, item, unit_type, brand, model, capacity,
    branch_name, branch_address, location_desc,
    install_date, warranty_date, contractor,
    last_service, next_service, service_type
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "sissssssssssssss",
    $unit_id,
    $room_id,
    $item,
    $unit_type,
    $brand,
    $model,
    $capacity,
    $branch_name,
    $branch_address,
    $location_desc,
    $install_date,
    $warranty_date,
    $contractor_name,
    $last_service,
    $next_service,
    $service_type
);

$stmt->execute();
$unit_id = $stmt->insert_id;

// ✅ Redirect back
header("Location: ../unit.php?added=1&unit_id=$unit_id");
exit;
