// assets/js/index-wallet.js
import { connectWallet } from "./wallet.js";

document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("connectWalletBtn");

  if (!btn) return;

  btn.addEventListener("click", async () => {
    try {
      const address = await connectWallet();
      btn.textContent =
        address.slice(0, 8) + "..." + address.slice(-5);
    } catch (err) {
      alert(err.message || "Wallet connection failed");
    }
  });
});
