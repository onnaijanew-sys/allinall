<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$message = "";

// Handle new tracking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_tracking'])) {
    $tracking_number = trim($_POST['tracking_number']);

    if (empty($tracking_number)) {
        $message = "Please enter a tracking number.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO tracking_entries (user_id, tracking_number, status) VALUES (?, ?, ?)");
        if ($stmt->execute([$user_id, $tracking_number, 'Pending'])) {
            $message = "Tracking number generated successfully!";
            $showModalAd = true; // trigger modal ad
        } else {
            $message = "Failed to save tracking number.";
        }
    }
}

// Fetch user's tracking entries
$stmt = $pdo->prepare("SELECT * FROM tracking_entries WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$tracking_entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipping Module</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/main.js"></script>
</head>
<body>
<div class="container">
    <h2>Shipping Dashboard</h2>
    <a href="../dashboard.php">Back to Dashboard</a> | <a href="../logout.php">Logout</a>

    <?php if($message): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Tracking Form -->
    <form method="POST" action="">
        <input type="text" name="tracking_number" placeholder="Enter tracking number" required>
        <button type="submit" name="generate_tracking">Generate Tracking</button>
    </form>

    <!-- Display user tracking entries -->
    <h3>Your Tracking Entries</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Tracking Number</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        <?php foreach($tracking_entries as $entry): ?>
        <tr>
            <td><?php echo $entry['id']; ?></td>
            <td><?php echo htmlspecialchars($entry['tracking_number']); ?></td>
            <td><?php echo $entry['status']; ?></td>
            <td><?php echo $entry['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php
// Trigger modal ad only after successful tracking generation
if (isset($showModalAd) && $showModalAd) {
    echo "<script>showAdModal();</script>";
}
?>
</body>
</html>
