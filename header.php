<?php
require_once __DIR__ . '/config.php';
$currentUser = get_current_user_data();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerala State Road Transport Corporation - Online Bus Ticket Reservation</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- ========================================================
         HERO HEADER SECTION
         ======================================================== -->
    <header class="ksrtc-header-wrapper">
        <!-- Geometric Shapes Motif on top-left -->
        <div class="hero-shapes">
            <div class="shape-square shape-sq-1"></div>
            <div class="shape-square shape-sq-2"></div>
            <div class="shape-square shape-sq-3"></div>
            <div class="shape-dot dot-1"></div>
            <div class="shape-dot dot-2"></div>
            <div class="shape-dot dot-3"></div>
            <div class="shape-line line-1"></div>
            <div class="shape-line line-2"></div>
        </div>

        <div class="container">
            <!-- Top Navigation Bar -->
            <nav class="top-nav d-flex align-items-center justify-content-between">
                <!-- Brand / Logo -->
                <a href="index.php" class="brand-container">
                    <img src="assets/images/ksrtc-logo.svg" alt="KSRTC Logo" class="ksrtc-logo-img">
                    <div class="brand-text">
                        <div>Kerala State Road</div>
                        <div>Transport Corporation</div>
                    </div>
                </a>

                <!-- Right Navigation Links -->
                <div class="nav-links-right">
                    <span class="search-nav-icon" data-bs-toggle="modal" data-bs-target="#modifySearchModal" title="Search Routes">
                        <i class="bi bi-search"></i>
                    </span>
                    <a href="#" class="nav-link-item" data-bs-toggle="modal" data-bs-target="#topDestinationsModal">Top Destinations</a>
                    <a href="view_ticket.php" class="nav-link-item">View ticket</a>
                    <a href="cancel_ticket.php" class="nav-link-item">Cancellation</a>
                    <?php if ($currentUser): ?>
                        <a href="my_bookings.php" class="nav-link-item"><i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($currentUser['name']) ?></a>
                        <a href="auth.php?action=logout" class="nav-link-item">Sign out</a>
                    <?php else: ?>
                        <a href="#" class="nav-link-item" data-bs-toggle="modal" data-bs-target="#authModal" data-tab="register">Register</a>
                        <a href="#" class="nav-link-item" data-bs-toggle="modal" data-bs-target="#authModal" data-tab="login">Sign in</a>
                    <?php endif; ?>
                </div>
            </nav>

            <!-- Centered Action Banner ("Continue booking as guest or Click here to sign in and book") -->
            <div class="guest-banner-row">
                <span class="guest-banner-text">Continue booking as guest</span>
                <span class="guest-banner-or">or</span>
                <a href="<?= $currentUser ? 'my_bookings.php' : '#' ?>" class="signin-pill-btn" <?= $currentUser ? '' : 'data-bs-toggle="modal" data-bs-target="#authModal" data-tab="login"' ?>>
                    <span class="signin-pill-icon">
                        <i class="bi bi-x-square"></i>
                    </span>
                    <span class="signin-pill-text"><?= $currentUser ? 'Logged in as ' . htmlspecialchars($currentUser['name']) : 'Click here to sign in and book' ?></span>
                    <span class="signin-pill-arrow"><i class="bi bi-arrow-right"></i></span>
                </a>
            </div>
        </div>
    </header>

