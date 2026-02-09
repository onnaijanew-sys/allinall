<?php
require_once '../config.php';
include_once '../includes/header.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$message = "";

// Generate tracking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_tracking'])) {
    $tracking_number = trim($_POST['tracking_number']);
    if (!empty($tracking_number)) {
        $stmt = $pdo->prepare("INSERT INTO tracking_entries (user_id, tracking_number, status) VALUES (?, ?, 'Pending')");
        if ($stmt->execute([$user_id, $tracking_number])) {
            $message = "Tracking number generated!";
            $showModalAd = true;
        } else {
            $message = "Failed to save tracking.";
        }
    } else {
        $message = "Please enter tracking number.";
    }
}

// Fetch user tracking
$stmt = $pdo->prepare("SELECT * FROM tracking_entries WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$tracking_entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2>Shipping Dashboard</h2>

    <?php if($message): ?><p style="color:green;"><?php echo $message; ?></p><?php endif; ?>

    <form method="POST">
        <input type="text" name="tracking_number" placeholder="Enter tracking number" required>
        <button type="submit" name="generate_tracking">Generate</button>
    </form>

    <h3>Your Tracking Entries</h3>
    <table border="1" cellpadding="8">
        <tr><th>ID</th><th>Tracking Number</th><th>Status</th><th>Date</th></tr>
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
include_once '../includes/footer.php';
if (isset($showModalAd)) echo "<script>showAdModal();</script>";
?>
