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

// Handle celebrity post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_celebrity'])) {
    $content = trim($_POST['content']);

    if (empty($content)) {
        $message = "Please enter content.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO celebrity_entries (user_id, content) VALUES (?, ?)");
        if ($stmt->execute([$user_id, $content])) {
            $message = "Celebrity post submitted!";
            $showModalAd = true;
        } else {
            $message = "Failed to save content.";
        }
    }
}

// Fetch user's celebrity entries
$stmt = $pdo->prepare("SELECT * FROM celebrity_entries WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Celebrity Module</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/main.js"></script>
</head>
<body>
<div class="container">
    <h2>Celebrity Dashboard</h2>
    <a href="../dashboard.php">Back to Dashboard</a> | <a href="../logout.php">Logout</a>

    <?php if($message): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <textarea name="content" placeholder="Enter celebrity content" required></textarea><br><br>
        <button type="submit" name="post_celebrity">Submit</button>
    </form>

    <h3>Your Celebrity Posts</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Content</th>
            <th>Date</th>
        </tr>
        <?php foreach($entries as $entry): ?>
        <tr>
            <td><?php echo $entry['id']; ?></td>
            <td><?php echo htmlspecialchars($entry['content']); ?></td>
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
