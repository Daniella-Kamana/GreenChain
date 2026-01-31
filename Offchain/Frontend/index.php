<?php
session_start();
require 'config/db.php'; // $conn is mysqli connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $first = trim($_POST['first_name'] ?? '');
    $last  = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($first === '' || $last === '' || $email === '' || $pass === '') {
        die("All fields required");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address");
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Email already registered";
        $check->close();
        exit;
    }
    $check->close();

    // Hash password and insert
    $hashed = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)"
    );
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $first, $last, $email, $hashed);

    if ($stmt->execute()) {
        // Optionally log the user in immediately
        session_regenerate_id(true);
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['email'] = $email;

        // Redirect to dashboard or show success
        header("Location: dashboard.php");
        exit;
    } else {
        // More informative error for debugging; hide in production
        echo "ERROR: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>GreenChain</title>
  <link rel="stylesheet" href="assets/css/style.css">
<script type="module" src="assets/js/index-wallet.js"></script>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="logo">🌱 GreenChain</div>

  <ul class="nav-links">
    <li><a href="index.php" class="active">Home</a></li>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="eco submission.php">Eco action submission</a></li>
    <li><a href="governance.php">Governance</a></li>
    <li><a href="nfts.php">NFTs</a></li>
  </ul>

</nav>

<section class="hero">
  <div class="hero-inner">
    <!-- LEFT: Title + intro + CTA + illustration -->
    <div class="hero-left">
      <div class="hero-top">Join the Cardano GreenChain Movement</div>
      <h1 class="hero-title">Earn Rewards While Building a Greener Tomorrow</h1>
      <p class="hero-sub">
       GreenChain is a decentralized platform that rewards eco-friendly actions
      with blockchain-powered tokens, NFTs, and community impact.Take simple, verified actions to recycle correctly, reduce waste,and earn Green Tokens for your positive environmental impact.
      </p>

      <div class="hero-cta-row">
        <button class="cta-primary" onclick="location.href='auth/register.php'">Get Started</button>
        <button class="cta-outline" onclick="location.href='marketplace.php'">Explore Marketplace</button>
      </div>

      <!-- Illustration (responsive) -->
      <div class="hero-illustration">
  <img
    src="assets/images/riendly.webp" 
    alt="Illustration of recycling and community"
    loading="lazy"
    class="hero-illustration-img"
  />
</div>
    </div>

    <!-- RIGHT: three-step feature cards with images -->
    <div class="hero-right">
      <div class="feature-grid">
        <div class="feature-card">
          <img src="assets/images/recycle.jpeg" alt="Blue bin icon" class="feature-icon" loading="lazy">
          <div class="feature-body">
            <div class="feature-number">01</div>
            <h3 class="feature-title">Smart Recycling Basics</h3>
            <p class="feature-desc">Learn how to properly sort and prepare recyclable materials for collection.</p>
          </div>
        </div>

        <div class="feature-card">
          <img src="assets/images/study.jpg" alt="Plastic bottle icon" class="feature-icon" loading="lazy">
          <div class="feature-body">
            <div class="feature-number">02</div>
            <h3 class="feature-title">Packaging That Counts</h3>
            <p class="feature-desc">Understand how packaging materials are recycled and how to make sustainable choices.</p>
          </div>
        </div>

        <div class="feature-card">
          <img src="assets/images/sus.jpg" alt="Heart leaf icon" class="feature-icon" loading="lazy">
          <div class="feature-body">
            <div class="feature-number">03</div>
            <h3 class="feature-title">Make a Difference</h3>
            <p class="feature-desc">Track your environmental impact and see how your actions contribute to a greener future.</p>
          </div>
        </div>
      </div>

      <div class="hero-stay-connected">Stay connected. Stay sustainable.</div>
    </div>
  </div>
</section>
</body>
</html>
