import { Lucid, Blockfrost } from "https://unpkg.com/lucid-cardano/web/mod.js";

let lucid = null;

export async function connectWallet() {
  if (!window.cardano?.lace) {
    alert("Please install Lace wallet");
    return;
  }

  const api = await window.cardano.lace.enable();

  lucid = await Lucid.new(
    new Blockfrost(
      "https://cardano-preprod.blockfrost.io/api/v0",
      "YOUR_BLOCKFROST_KEY"
    ),
    "Preprod"
  );

  lucid.selectWallet(api);

  // 1️⃣ Get wallet address
  const address = await lucid.wallet.address();

  // 2️⃣ Show address on UI
  const addrEl = document.getElementById("receiveAddress");
  if (addrEl) addrEl.innerText = address;

  // 3️⃣ Get ADA balance
  const utxos = await lucid.wallet.getUtxos();

  let lovelace = 0n;
  utxos.forEach(u => {
    lovelace += u.assets.lovelace ?? 0n;
  });

  const ada = Number(lovelace) / 1_000_000;
  document.getElementById("adaBalance").innerText = ada.toFixed(2);

  // 4️⃣ Save wallet to DB (next step 👇)
  saveWalletAddress(address);
}
