<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'includes/conn.php';

$unitId = isset($_GET['unit']) ? intval($_GET['unit']) : 0;
if ($unitId <= 0) die("Invalid unit ID.");

// Fetch unit details
$stmt = $conn->prepare("SELECT * FROM units WHERE id = ?");
$stmt->bind_param("i", $unitId);
$stmt->execute();
$unit = $stmt->get_result()->fetch_assoc();
if (!$unit) die("Unit not found.");

// If track_record is 1, block report
$trackRecord = intval($unit['track_record']);
$reportAllowed = ($trackRecord !== 1);

// Clean up displayed unit fields
unset($unit['id'], $unit['room_id'], $unit['next_service'], $unit['track_record']);

// Get existing report
$unitIdStr = $unit['unit_id'];
$stmt = $conn->prepare("SELECT * FROM reports WHERE unit_id = ?");
$stmt->bind_param("s", $unitIdStr);
$stmt->execute();
$report = $stmt->get_result()->fetch_assoc();

// Get existing service if report exists
$service = null;
if ($report) {
    $stmt = $conn->prepare("SELECT * FROM services WHERE report_id = ?");
    $stmt->bind_param("i", $report['id']);
    $stmt->execute();
    $service = $stmt->get_result()->fetch_assoc();
}

// Handle POST
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_report']) && $reportAllowed) {
        $message = trim($_POST['message'] ?? '');
        $reporter = trim($_POST['reporter_name'] ?? '');

        if ($message && $reporter) {
            $stmt = $conn->prepare("INSERT INTO reports (unit_id, message, reporter_name, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $unitIdStr, $message, $reporter);
            if ($stmt->execute()) {
                $conn->query("UPDATE units SET track_record = 1 WHERE id = $unitId");
                header("Location: report-form.php?unit=$unitId");
                exit;
            } else {
                $error = "❌ Failed to save report.";
            }
        } else {
            $error = "⚠️ All fields are required.";
        }
    }

    if (isset($_POST['submit_service']) && $report) {
        $sname = trim($_POST['service_name'] ?? '');
        $sphone = trim($_POST['service_phone'] ?? '');
        $sdesc = trim($_POST['service_desc'] ?? '');
        $sdate = date('Y-m-d');

        if ($sname && $sphone && $sdesc) {
            $stmt = $conn->prepare("INSERT INTO services (report_id, service_name, service_phone, service_desc, service_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $report['id'], $sname, $sphone, $sdesc, $sdate);
            if ($stmt->execute()) {
                $contractor = $sname;
                $remarks = $sdesc;

                $stmt = $conn->prepare("INSERT INTO services_history (unit_id, service_date, service_type, contractor, remarks) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $unitIdStr, $sdate, $sdesc, $contractor, $remarks);
                $stmt->execute();

                $conn->query("UPDATE units SET track_record = 0 WHERE id = $unitId");

                $stmt = $conn->prepare("INSERT INTO reports_history (unit_id, message, reporter_name, created_at, resolved_at) VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("ssss", $unitIdStr, $report['message'], $report['reporter_name'], $report['created_at']);
                $stmt->execute();

                $conn->query("DELETE FROM services WHERE report_id = " . $report['id']);
                $conn->query("DELETE FROM reports WHERE id = " . $report['id']);

                header("Location: report-form.php?unit=$unitId");
                exit;
            } else {
                $error = "❌ Failed to save service details.";
            }
        } else {
            $error = "⚠️ All service fields are required.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Unit Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen p-4">
    <div class="max-w-lg mx-auto bg-white rounded-xl shadow-md p-5 space-y-6">
        <h1 class="text-2xl font-bold text-center text-indigo-700">🧾 Unit Report</h1>

        <div class="overflow-x-auto">
            <table class="table-auto w-full text-sm border rounded">
                <tbody>
                    <?php foreach ($unit as $key => $value): ?>
                        <tr class="border-b">
                            <td class="p-2 font-semibold text-gray-600 capitalize"><?= ucwords(str_replace('_', ' ', $key)) ?></td>
                            <td class="p-2 text-gray-800"><?= $value ?: '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded text-sm"><?= $error ?></div>
        <?php endif; ?>

        <?php if (!$report): ?>
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">📝 Submit Report</h2>
                <form method="POST" class="space-y-3">
                    <input type="text" name="reporter_name" placeholder="Your Name"
                        class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:ring-indigo-200" required>
                    <textarea name="message" rows="4" placeholder="Report message..."
                        class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:ring-indigo-200" required></textarea>
                    <button type="submit" name="submit_report"
                        class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">📩 Submit Report</button>
                </form>
            </div>
        <?php else: ?>
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">📋 Existing Report</h2>
                <div class="space-y-1 text-sm">
                    <p><strong>Reporter:</strong> <?= htmlspecialchars($report['reporter_name']) ?></p>
                    <p><strong>Message:</strong><br> <?= nl2br(htmlspecialchars($report['message'])) ?></p>
                    <p><strong>Submitted:</strong> <?= $report['created_at'] ?></p>
                </div>
            </div>

            <?php if (!$service): ?>
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mt-5 mb-2">🛠️ Service Details</h2>
                    <form method="POST" class="space-y-3">
                        <input type="text" name="service_name" placeholder="Service Company Name"
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:ring-green-200" required>
                        <input type="text" name="service_phone" placeholder="Phone Number"
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:ring-green-200" required>
                        <textarea name="service_desc" rows="3" placeholder="Description of service..."
                            class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:ring-green-200" required></textarea>
                        <input type="text" value="<?= date('Y-m-d') ?>" disabled
                            class="w-full bg-gray-100 border px-3 py-2 rounded text-gray-600">
                        <button type="submit" name="submit_service"
                            class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">💾 Save Service</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="mt-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">✅ Service Info</h2>
                    <div class="space-y-1 text-sm">
                        <p><strong>Service Name:</strong> <?= htmlspecialchars($service['service_name']) ?></p>
                        <p><strong>Phone:</strong> <?= $service['service_phone'] ?></p>
                        <p><strong>Description:</strong><br><?= nl2br($service['service_desc']) ?></p>
                        <p><strong>Date:</strong> <?= $service['service_date'] ?></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>

</html>