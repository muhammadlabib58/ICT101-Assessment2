<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo isset($page_title) ? $page_title . " - Paws & Polish" : "Paws & Polish Pet Grooming"; ?></title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="site-header">
    <div class="logo">Paws &amp; Polish</div>
    <nav>
        <a href="index.html">Home</a>
        <a href="services.html">Services</a>
        <?php if (isset($_SESSION["user_id"])): ?>
            <a href="account.php">My Account</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
        <a href="feedback.php">Feedback</a>
    </nav>
</header>
<main class="page-content">
