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

// Handle lotto number submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_lotto'])) {
    $numbers = trim($_POST['numbers']);

    if (empty($numbers)) {
        $message = "Please enter numbers.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO lotto_entries (user_id, numbers) VALUES (?, ?)");
        if ($stmt->execute([$user_id, $numbers])) {
            $message = "Lotto numbers generated successfully!";
            $showModalAd = true; // trigger modal ad
        } else {
            $message = "Failed to save lotto numbers.";
        }
    }
}

// Fetch user's lotto entries
$stmt = $pdo->prepare("SELECT * FROM lotto_entries WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$lotto_entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lotto Module</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/main.js"></script>
</head>
<body>
<div class="container">
    <h2>Lotto Dashboard</h2>
    <a href="../dashboard.php">Back to Dashboard</a> | <a href="../logout.php">Logout</a>

    <?php if($message): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Lotto Form -->
    <form method="POST" action="">
        <input type="text" name="numbers" placeholder="Enter your lotto numbers" required>
        <button type="submit" name="generate_lotto">Generate</button>
    </form>

    <!-- Display user lotto entries -->
    <h3>Your Lotto Entries</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Numbers</th>
            <th>Date</th>
        </tr>
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
// Trigger modal ad only after successful lotto generation
if (isset($showModalAd) && $showModalAd) {
    echo "<script>showAdModal();</script>";
}
?>
</body>
</html>
