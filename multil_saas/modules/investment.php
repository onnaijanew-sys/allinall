<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$message = "";

// Handle investment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_investment'])) {
    $investment_name = trim($_POST['investment_name']);
    $amount = floatval($_POST['amount']);

    if (empty($investment_name) || $amount <= 0) {
        $message = "Enter valid investment details.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO investment_entries (user_id, investment_name, amount, status) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $investment_name, $amount, 'Pending'])) {
            $message = "Investment added!";
            $showModalAd = true;
        } else {
            $message = "Failed to add investment.";
        }
    }
}

// Fetch user's investments
$stmt = $pdo->prepare("SELECT * FROM investment_entries WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$investments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Investments Module</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/main.js"></script>
</head>
<body>
<div class="container">
    <h2>Investments Dashboard</h2>
    <a href="../dashboard.php">Back to Dashboard</a> | <a href="../logout.php">Logout</a>

    <?php if($message): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="investment_name" placeholder="Investment Name" required>
        <input type="number" name="amount" placeholder="Amount" step="0.01" required>
        <button type="submit" name="new_investment">Add Investment</button>
    </form>

    <h3>Your Investments</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
        <?php foreach($investments as $inv): ?>
        <tr>
            <td><?php echo $inv['id']; ?></td>
            <td><?php echo htmlspecialchars($inv['investment_name']); ?></td>
            <td><?php echo $inv['amount']; ?></td>
            <td><?php echo $inv['status']; ?></td>
            <td><?php echo $inv['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php
if (isset($showModalAd) && $showModalAd) {
    echo "<script>showAdModal();</script>";
}
?>
</body>
</html>
