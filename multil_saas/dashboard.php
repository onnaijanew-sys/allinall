<?php
require_once 'config.php';
include_once 'includes/header.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_name = $_SESSION['user_name'];
?>

<div class="container">
    <h2>Welcome to Your Dashboard, <?php echo htmlspecialchars($user_name); ?>!</h2>
    <p>Select a module to manage:</p>

    <div class="module-cards">
        <div class="card">
            <h3>Lotto</h3>
            <p>Generate lotto numbers and track your entries.</p>
            <a href="modules/lotto.php">Open Lotto Module</a>
        </div>

        <div class="card">
            <h3>Shipping</h3>
            <p>Generate tracking numbers and manage shipments.</p>
            <a href="modules/shipping.php">Open Shipping Module</a>
        </div>

        <div class="card">
            <h3>Celebrity</h3>
            <p>Create celebrity posts and track your content.</p>
            <a href="modules/celebrity.php">Open Celebrity Module</a>
        </div>

        <div class="card">
            <h3>Bank</h3>
            <p>Deposit or withdraw funds and view transactions.</p>
            <a href="modules/bank.php">Open Bank Module</a>
        </div>

        <div class="card">
            <h3>Investments</h3>
            <p>Track your investments and their status.</p>
            <a href="modules/investments.php">Open Investments Module</a>
        </div>
    </div>
</div>

<style>
.container { max-width: 900px; margin: 20px auto; font-family: Arial, sans-serif; }
.module-cards { display: flex; flex-wrap: wrap; gap: 20px; }
.card { flex: 1 1 250px; padding: 15px; border: 1px solid #ccc; border-radius: 8px; text-align: center; background: #fafafa; }
.card h3 { margin-top: 0; }
.card a { display: inline-block; margin-top: 10px; padding: 6px 12px; background: #007bff; color: #fff; border-radius: 4px; text-decoration: none; }
.card a:hover { background: #0056b3; }
</style>

<?php include_once 'includes/footer.php'; ?>
