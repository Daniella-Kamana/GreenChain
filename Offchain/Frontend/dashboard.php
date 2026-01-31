<?php
// MOCK DATA
// PHP inside JS
// fetch get_dashboard.php
session_start();
require "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: auth/login.php");
    exit;
}

$userId = $_SESSION["user_id"];

/* GreenToken balance */
$balanceStmt = $conn->prepare(
  "SELECT green_balance FROM users WHERE id = ?"
);
$balanceStmt->bind_param("i", $userId);
$balanceStmt->execute();
$balance = $balanceStmt->get_result()->fetch_assoc()["green_balance"] ?? 0;

/* Verified actions */
$actionStmt = $conn->prepare(
  "SELECT COUNT(*) AS total FROM actions 
   WHERE user_id = ? AND status = 'verified'"
);
$actionStmt->bind_param("i", $userId);
$actionStmt->execute();
$actionCount = $actionStmt->get_result()->fetch_assoc()["total"] ?? 0;

/* NFT count (ONLY ONCE ✅) */
$nftStmt = $conn->prepare(
  "SELECT COUNT(*) AS total FROM nfts WHERE user_id = ?"
);
$nftStmt->bind_param("i", $userId);
$nftStmt->execute();
$nftCount = $nftStmt->get_result()->fetch_assoc()["total"] ?? 0;

/* User details */
$stmt = $conn->prepare(
  "SELECT first_name, last_name, email, role, wallet_address
   FROM users WHERE id = ?"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GreenChain | Dashboard</title>
  <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<body>

<nav class="navbar">
  <div class="logo">🌱 GreenChain</div>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a class="active" href="dashboard.php">Dashboard</a></li>
    <li><a href="marketplace.php">Marketplace</a></li>
  </ul>
</nav>

<section class="dashboard">
  <h1>Welcome, <?= htmlspecialchars($user["first_name"]) ?></h1>
  <p class="subtitle">Track your sustainability rewards and achievements</p>

  <div class="dashboard-grid">

    <!-- LEFT: STATS -->
    <div class="stats-grid">

      <div class="stat-card">
        <span class="icon">🪙</span>
        <h3>GreenToken Balance</h3>
        <p class="stat-value"><?= $balance ?> GRT</p>
      </div>

      <div class="stat-card">
        <span class="icon">🌍</span>
        <h3>Actions Verified</h3>
        <p class="stat-value"><?= $actionCount ?></p>
      </div>

      <div class="stat-card">
        <span class="icon">🏆</span>
        <h3>NFT Badges</h3>
        <p class="stat-value"><?= $nftCount ?></p>
      </div>

      <div class="stat-card">
  <span class="icon">💰</span>
  <h3>ADA Balance</h3>
  <p class="stat-value" id="adaBalance">—</p>
</div>

    </div>

    <!-- RIGHT: WALLET -->
    <aside class="wallet-panel">

      <h2>💳 ADA Wallet</h2>

      <div class="wallet-box">
        <h4>Receive ADA</h4>
        <code id="receiveAddress">
  <?= $user["wallet_address"] ?: "Not connected" ?>
</code>

      </div>

      <div class="wallet-box">
        <h4>Send ADA</h4>

        <input id="sendTo" type="text" placeholder="Recipient address">
<input id="amount" type="number" placeholder="Amount (ADA)" min="0.1" step="0.1">

<button id="sendAdaBtn" class="btn-primary">Send ADA</button>
      </div>
      <p id="txStatus" class="tx-status"></p>

      <div class="wallet-meta">
        <p><strong>Email:</strong> <?= $user["email"] ?></p>
        <p><strong>Role:</strong> <?= $user["role"] ?></p>
     <center><a class="logout" href="auth/logout.php">Logout</a></center>
      </div>

    </aside>

  </div>

  <div class="wallet-box">
  <h4>📜 Recent Transactions</h4>
  <ul id="txList">Loading...</ul>
</div>
<ul id="txList"></ul>

</section>


<script type="module">
  import { connectWallet, sendAda } from "/greenchain/assets/js/wallet.js";

  // auto-connect
  connectWallet();

  document
    .getElementById("sendAdaBtn")
    ?.addEventListener("click", () => {
      const to = document.getElementById("sendTo").value;
      const amount = document.getElementById("amount").value;
      sendAda(to, Number(amount));
    });
</script>



</body>
</html>
