<?php
// header.php - includes test ads and session check
session_start();

// If user is not logged in and trying to access protected pages
$current_page = basename($_SERVER['PHP_SELF']);
$protected_pages = ['dashboard.php', 'lotto.php', 'shipping.php', 'celebrity.php', 'bank.php', 'investments.php'];
if (in_array($current_page, $protected_pages) && !isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Multi SaaS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/main.js"></script>
</head>
<body>
<!-- ===== HEADER BANNER ADS ===== -->
<div class="ad-container ad-top">
    <div style="background:#f9f9f9; padding:10px; border:1px dashed #333; text-align:center;">
        TEST HEADER AD
    </div>
</div>

<!-- ===== NAVIGATION ===== -->
<div class="navbar">
    <a href="../dashboard.php">Dashboard</a>
    <?php if(isset($_SESSION['user_name'])): ?>
        | Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        | <a href="../logout.php">Logout</a>
    <?php else: ?>
        | <a href="../index.php">Login</a>
        | <a href="../register.php">Register</a>
    <?php endif; ?>
</div>

<!-- ===== MODAL TEST AD ===== -->
<div id="adModal" class="ad-modal">
    <div class="ad-modal-content">
        <span class="close-ad">&times;</span>
        <div style="background:#ffe; padding:20px; border:1px dashed #333; text-align:center;">
            TEST MODAL AD
        </div>
    </div>
</div>
