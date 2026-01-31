<?php
session_start();
require __DIR__ . "/config/db.php";

/* ===============================
   AUTH CHECK
================================ */
if (!isset($_SESSION["user_id"])) {
    header("Location: auth/login.php");
    exit;
}

$userId = $_SESSION["user_id"];

/* ===============================
   USER DATA
================================ */
$userStmt = $conn->prepare("
  SELECT wallet_address, green_balance 
  FROM users WHERE id = ?
");
$userStmt->bind_param("i", $userId);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

$walletAddress = $user["wallet_address"] ?? null;
$votingPower  = $user["green_balance"] ?? 0;

/* ===============================
   GOVERNANCE STATS
================================ */
$totalStmt = $conn->query("SELECT COUNT(*) AS total FROM proposals");
$totalProposals = $totalStmt->fetch_assoc()["total"];

$activeStmt = $conn->query("
  SELECT COUNT(*) AS active 
  FROM proposals WHERE status = 'active'
");
$activeVotes = $activeStmt->fetch_assoc()["active"];

/* ===============================
   FETCH ACTIVE PROPOSALS
================================ */
$proposalStmt = $conn->query("
  SELECT 
    p.id,
    p.title,
    p.description,
    p.yes_votes,
    p.no_votes
  FROM proposals p
  WHERE p.status = 'active'
  ORDER BY p.created_at DESC
");

$proposals = [];
while ($row = $proposalStmt->fetch_assoc()) {
    $totalVotes = $row["yes_votes"] + $row["no_votes"];
    $row["yes_percent"] = $totalVotes > 0
        ? round(($row["yes_votes"] / $totalVotes) * 100)
        : 0;
    $row["no_percent"] = 100 - $row["yes_percent"];
    $proposals[] = $row;
}

/* ===============================
   HANDLE CREATE PROPOSAL (AJAX)
================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_proposal"])) {

    if (!$walletAddress) {
        http_response_code(403);
        echo json_encode(["error" => "Wallet not connected"]);
        exit;
    }

    $title = trim($_POST["title"]);
    $desc  = trim($_POST["description"]);

    if ($title && $desc) {
        $stmt = $conn->prepare("
          INSERT INTO proposals 
          (user_id, title, description, status)
          VALUES (?, ?, ?, 'active')
        ");
        $stmt->bind_param("iss", $userId, $title, $desc);
        $stmt->execute();

        echo json_encode(["success" => true]);
        exit;
    }
}

/* ===============================
   HANDLE VOTE (AJAX)
================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["vote"])) {

    if (!$walletAddress) {
        http_response_code(403);
        echo json_encode(["error" => "Wallet not connected"]);
        exit;
    }

    $proposalId = (int) $_POST["proposal_id"];
    $voteType   = $_POST["vote"]; // yes | no

    if ($voteType === "yes") {
        $sql = "UPDATE proposals SET yes_votes = yes_votes + ? WHERE id = ?";
    } else {
        $sql = "UPDATE proposals SET no_votes = no_votes + ? WHERE id = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $votingPower, $proposalId);
    $stmt->execute();

    echo json_encode(["success" => true]);
    exit;
}

/* ===============================
   EXPOSE DATA TO JS
================================ */
echo "<script>
window.GOV_DATA = {
  wallet: " . json_encode($walletAddress ?: "Not connected") . ",
  stats: {
    totalProposals: $totalProposals,
    activeVotes: $activeVotes,
    votingPower: $votingPower
  },
  proposals: " . json_encode($proposals) . "
};
</script>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GreenChain Governance</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="assets/css/governance.css">
   <script type="module" src="assets/js/governance.js" defer></script>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="logo">🌱 GreenChain</div>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="marketplace.php">Marketplace</a></li>
    <li><a href="governance.php" class="active">Governance</a></li>
    <li><a href="nfts.php">NFTs</a></li>
  </ul>
  <button class="wallet-btn" id="connectWalletBtn">Connect Wallet</button>
</nav>

<!-- HEADER -->
<header class="gov-header">
  <h1>Community Governance</h1>
  <p>
    GreenChain is governed by its community.  
    Propose, vote, and shape the future of sustainability.
  </p>

  <div class="wallet-status">
    Wallet: <span id="walletAddress">Not connected</span>
  </div>
</header>

<!-- GOVERNANCE CONTENT -->
<main class="gov-container">

  <!-- STATS -->
  <section class="gov-stats">
    <div class="stat-card">
      <h3>Total Proposals</h3>
      <p id="totalProposals">0</p>
    </div>
    <div class="stat-card">
      <h3>Active Votes</h3>
      <p id="activeVotes">0</p>
    </div>
    <div class="stat-card">
      <h3>Your Voting Power</h3>
      <p id="votingPower">0 GRT</p>
    </div>
  </section>

  <!-- CREATE PROPOSAL -->
  <section class="gov-card">
    <h2>Create Proposal</h2>

    <form id="proposalForm">
      <input type="text" placeholder="Proposal title" required>
      <textarea placeholder="Describe your proposal..." required></textarea>
      <button type="submit" class="btn-primary">
        Submit Proposal
      </button>
    </form>
  </section>

  <!-- PROPOSALS -->

<section class="gov-card" id="proposalsSection">
  <h2>Active Proposals</h2>
</section>


  </section>

</main>

</body>
</html>
