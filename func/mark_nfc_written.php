<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'developer') {
    http_response_code(403);
    exit;
}

include '../includes/conn.php';

$unit_id = isset($_GET['unit_id']) ? (int)$_GET['unit_id'] : 0;
if ($unit_id > 0) {
    $stmt = $conn->prepare("UPDATE units SET nfc_written = 1 WHERE id = ?");
    $stmt->bind_param("i", $unit_id);
    $stmt->execute();
}
