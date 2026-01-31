<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GreenChain Marketplace</title>

  <!-- Marketplace Styles -->
  <link rel="stylesheet" href="assets/css/marketplace.css">
  
  <!-- Marketplace Logic -->
    <script type="module" src="assets/js/market.js"></script>
</head>

<body>
<!-- NAVBAR -->
<nav class="navbar">
  <!-- LEFT: LOGO -->
  <div class="logo">🌱 GreenChain</div>

  <!-- CENTER: NAV LINKS -->
  <ul class="nav-links">
    <li><a href="index.php" class="active">Home</a></li>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="governance.php">Governance</a></li>
    <li><a href="nfts.php">NFTs</a></li>
  </ul>

</nav>


  <!-- MARKETPLACE -->
  <div class="marketplace-container">

    <div class="marketplace-header">
      <h1>GreenChain Marketplace</h1>
      <p>Trade eco-friendly products, services, and NFTs powered by Cardano.</p>
    </div>

    <div class="wallet-status">
      Wallet: <span id="walletAddress">Not connected</span>
      <br><br>
      <button class="market-btn" onclick="connectWallet()">
        Connect Wallet
      </button>
    </div>

    <div class="market-grid">

      <!-- Item 1 -->
      <div class="market-card">
        <img src="assets/images/recycle.jpeg" alt="Recycling NFT">
        <div class="market-card-content">
          <h3>Recycling NFT</h3>
          <p>Proof of verified recycling action.</p>
          <div class="market-meta">
            <span class="market-price">5 ADA</span>
            <button class="market-btn" data-item="Recycling NFT" data-price="5">
              Buy
            </button>
          </div>
        </div>
      </div>

      <!-- Item 2 -->
      <div class="market-card">
        <img src="assets/images/solar.jpg" alt="Solar Credit">
        <div class="market-card-content">
          <h3>Solar Credit</h3>
          <p>Tokenized solar energy contribution.</p>
          <div class="market-meta">
            <span class="market-price">12 ADA</span>
            <button class="market-btn" data-item="Solar Credit" data-price="12">
              Buy
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>

</body>
</html>
