<?php
// dashboard.php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch all modules
$stmt = $pdo->query("SELECT * FROM modules");
$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch modules activated by user
$stmt = $pdo->prepare("SELECT module_id FROM user_modules WHERE user_id = ?");
$stmt->execute([$user_id]);
$user_modules_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Multi SaaS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
    <a href="logout.php">Logout</a>
    
    <h3>Your Modules</h3>
    <div class="modules-container">
        <?php foreach ($modules as $module): ?>
            <div class="module-card">
                <h4><?php echo ucfirst($module['name']); ?></h4>
                
                <?php if (in_array($module['id'], $user_modules_ids)): ?>
                    <a href="modules/<?php echo $module['name']; ?>.php">Open</a>
                <?php else: ?>
                    <form method="POST" action="">
                        <input type="hidden" name="module_id" value="<?php echo $module['id']; ?>">
                        <button type="submit" name="activate_module">Activate</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php
    // Handle module activation
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activate_module'])) {
        $module_id = intval($_POST['module_id']);

        $stmt = $pdo->prepare("INSERT INTO user_modules (user_id, module_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $module_id]);

        // Refresh to update dashboard
        header("Location: dashboard.php");
        exit;
    }
    ?>
</div>
</body>
</html>
