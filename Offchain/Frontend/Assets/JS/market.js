import { Lucid, Blockfrost } from "https://unpkg.com/lucid-cardano@0.10.11/web/mod.js";
import { mintNFT } from "./wallet.js";

const BLOCKFROST_KEY = "preprodsJw0qxJfYA3iXqc7HRc0rtyI8Dmv9ny3";
let lucid = null;

const walletSpan = document.getElementById("walletAddress");

/* ================= CONNECT WALLET ================= */
async function connectWallet() {
  try {
    if (!window.cardano?.lace) {
      alert("❌ Install Lace wallet");
      return;
    }

    const api = await window.cardano.lace.enable();

    lucid = await Lucid.new(
      new Blockfrost(
        "https://cardano-preprod.blockfrost.io/api/v0",
        BLOCKFROST_KEY
      ),
      "Preprod"
    );

    lucid.selectWallet(api);

    const address = await lucid.wallet.address();

    walletSpan.innerText =
      address.slice(0, 10) + "..." + address.slice(-6);

    // 🔐 Save wallet to backend
    await fetch("api/redeem.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ wallet: address })
    });

    alert("✅ Wallet connected & logged in");
  } catch (err) {
    console.error(err);
    alert("❌ Wallet connection failed");
  }
}

/* 🔓 expose to HTML */
window.connectWallet = connectWallet;

/* ================= BUY / MINT ================= */
document.querySelectorAll(".market-btn[data-item]").forEach(btn => {
  btn.addEventListener("click", async () => {
    if (!lucid) {
      alert("Connect wallet first");
      return;
    }

    btn.disabled = true;
    btn.innerText = "Minting...";

    try {
      const txHash = await mintNFT(btn.dataset.item, lucid);
      alert("🎉 NFT Minted\nTX: " + txHash);
    } catch (e) {
      console.error(e);
      alert("❌ Minting failed");
    }

    btn.disabled = false;
    btn.innerText = "Buy";
  });
});
