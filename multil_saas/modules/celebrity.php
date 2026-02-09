<?php
require_once '../config.php';
include_once '../includes/header.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$message = "";

// Celebrity post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_celebrity'])) {
    $content = trim($_POST['content']);
    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO celebrity_entries (user_id, content) VALUES (?, ?)");
        if ($stmt->execute([$user_id, $content])) {
            $message = "Celebrity post submitted!";
            $showModalAd = true;
        } else {
            $message = "Failed to save content.";
        }
    } else {
        $message = "Please enter content.";
    }
}

// Fetch user celebrity posts
$stmt = $pdo->prepare("SELECT * FROM celebrity_entries WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2>Celebrity Dashboard</h2>

    <?php if($message): ?><p style="color:green;"><?php echo $message; ?></p><?php endif; ?>

    <form method="POST">
        <textarea name="content" placeholder="Enter content" required></textarea><br><br>
        <button type="submit" name="post_celebrity">Submit</button>
    </form>

    <h3>Your Celebrity Posts</h3>
    <table border="1" cellpadding="8">
        <tr><th>ID</th><th>Content</th><th>Date</th></tr>
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
include_once '../includes/footer.php';
if (isset($showModalAd)) echo "<script>showAdModal();</script>";
?>
