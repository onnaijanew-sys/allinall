<?php
require_once '../config.php';
include_once '../includes/header.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$message = "";

// Lotto submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_lotto'])) {
    $numbers = trim($_POST['numbers']);
    if (!empty($numbers)) {
        $stmt = $pdo->prepare("INSERT INTO lotto_entries (user_id, numbers) VALUES (?, ?)");
        if ($stmt->execute([$user_id, $numbers])) {
            $message = "Lotto numbers saved!";
            $showModalAd = true;
        } else {
            $message = "Failed to save numbers.";
        }
    } else {
        $message = "Please enter numbers.";
    }
}

// Fetch user's lotto entries
$stmt = $pdo->prepare("SELECT * FROM lotto_entries WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$lotto_entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2>Lotto Dashboard</h2>

    <?php if($message): ?><p style="color:green;"><?php echo $message; ?></p><?php endif; ?>

    <form method="POST">
        <input type="text" name="numbers" placeholder="Enter lotto numbers" required>
        <button type="submit" name="generate_lotto">Generate</button>
    </form>

    <h3>Your Entries</h3>
    <table border="1" cellpadding="8">
        <tr><th>ID</th><th>Numbers</th><th>Date</th></tr>
        <?php foreach($lotto_entries as $entry): ?>
        <tr>
            <td><?php echo $entry['id']; ?></td>
            <td><?php echo htmlspecialchars($entry['numbers']); ?></td>
            <td><?php echo $entry['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php
include_once '../includes/footer.php';
if (isset($showModalAd)) echo "<script>showAdModal();</script>";
?>
