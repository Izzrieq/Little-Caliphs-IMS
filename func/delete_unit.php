<?php
// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../includes/conn.php';

// Check if user is authenticated and a developer
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'developer') {
    header("Location: ../index.php");
    exit;
}

// Check if a valid ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $unit_id = intval($_GET['id']);

    // STEP 1: Delete related reports first (to avoid FK constraint error)
    $delReports = $conn->prepare("DELETE FROM reports WHERE unit_id = ?");
    $delReports->bind_param("i", $unit_id);
    $delReports->execute();
    $delReports->close();

    // STEP 2: Delete the unit
    $stmt = $conn->prepare("DELETE FROM units WHERE id = ?");
    $stmt->bind_param("i", $unit_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Unit deleted successfully.";
    } else {
        $_SESSION['error'] = "❌ Failed to delete unit. Please try again.";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "❗ Invalid unit ID.";
}

$conn->close();

// Redirect back to unit listing page
header("Location: ../unit.php");
exit;
