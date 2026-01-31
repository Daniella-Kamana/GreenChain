import { Lucid, Blockfrost } from "https://unpkg.com/lucid-cardano/web/mod.js";

let lucid = null;

window.connectWallet = async function () {
  try {
    if (!window.cardano || !window.cardano.lace) {
      alert("Please install Lace wallet");
      return;
    }

    const api = await window.cardano.lace.enable();

    lucid = await Lucid.new(
      new Blockfrost(
        "https://cardano-preprod.blockfrost.io/api/v0",
        "preprodsJw0qxJfYA3iXqc7HRc0rtyI8Dmv9ny3"
      ),
      "Preprod"
    );

    lucid.selectWallet(api);
    window.lucid = lucid; // 👈 VERY IMPORTANT

    const address = await lucid.wallet.address();

    // UI update (safe)
    const el = document.getElementById("walletAddress");
    if (el) el.innerText = address.slice(0, 12) + "...";

    console.log("Wallet connected:", address);
    return address;

  } catch (err) {
    console.error("Wallet connect error:", err);
    alert("Wallet connection failed");
  }
};

window.getVotingPower = async function () {
  if (!window.lucid) {
    alert("Connect wallet first");
    return 0;
  }

  const utxos = await window.lucid.wallet.getUtxos();

  let lovelace = 0;
  utxos.forEach(u => {
    lovelace += Number(u.assets.lovelace || 0);
  });

  const ada = lovelace / 1_000_000;
  return Math.floor(ada); // simple voting power
};
