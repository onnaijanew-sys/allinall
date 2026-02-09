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

// Handle bank transactions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bank_transaction'])) {
    $type = $_POST['type'];
    $amount = floatval($_POST['amount']);

    if ($amount <= 0) {
        $message = "Enter a valid amount.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO bank_entries (user_id, type, amount) VALUES (?, ?, ?)");
        if ($stmt->execute([$user_id, $type, $amount])) {
            $message = "Transaction successful!";
            $showModalAd = true;
        } else {
            $message = "Transaction failed.";
        }
    }
}

// Fetch user's bank entries
$stmt = $pdo->prepare("SELECT * FROM bank_entries WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bank Module</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/main.js"></script>
</head>
<body>
<div class="container">
    <h2>Bank Dashboard</h2>
    <a href="../dashboard.php">Back to Dashboard</a> | <a href="../logout.php">Logout</a>

    <?php if($message): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <select name="type">
            <option value="deposit">Deposit</option>
            <option value="withdraw">Withdraw</option>
        </select>
        <input type="number" name="amount" placeholder="Amount" step="0.01" required>
        <button type="submit" name="bank_transaction">Submit</button>
    </form>

    <h3>Your Transactions</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
        <?php foreach($entries as $entry): ?>
        <tr>
            <td><?php echo $entry['id']; ?></td>
            <td><?php echo $entry['type']; ?></td>
            <td><?php echo $entry['amount']; ?></td>
            <td><?php echo $entry['created_at']; ?></td>
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
