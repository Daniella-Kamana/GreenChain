import { Lucid, Blockfrost } from "https://unpkg.com/lucid-cardano/web/mod.js";

let lucid = null;

/* =========================
   RESTORE WALLET (AUTO)
========================= */
export async function restoreWalletIfConnected() {
  if (!window.cardano?.lace) return false;

  const isEnabled = await window.cardano.lace.isEnabled();
  if (!isEnabled) return false;

  const api = await window.cardano.lace.enable();

  lucid = await Lucid.new(
    new Blockfrost(
      "https://cardano-preprod.blockfrost.io/api/v0",
      "preprodsJw0qxJfYA3iXqc7HRc0rtyI8Dmv9ny3"
    ),
    "Preprod"
  );

  lucid.selectWallet(api);
  window.lucid = lucid;

  const address = await lucid.wallet.address();

  const addrEl = document.getElementById("receiveAddress");
  if (addrEl) addrEl.innerText = address;

  saveWalletToDB(address);

  try {
    const balance = await getAdaBalance();
    const balEl = document.getElementById("adaBalance");
    if (balEl) balEl.innerText = balance.toFixed(2) + " ADA";
  } catch {}

  return true;
}

/* =========================
   CONNECT WALLET (BUTTON)
========================= */
export async function connectWallet() {
  if (!window.cardano?.lace) {
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
  window.lucid = lucid;

  const address = await lucid.wallet.address();

  const addrEl = document.getElementById("receiveAddress");
  if (addrEl) addrEl.innerText = address;

  saveWalletToDB(address);

  const balance = await getAdaBalance();
  const balEl = document.getElementById("adaBalance");
  if (balEl) balEl.innerText = balance.toFixed(2) + " ADA";
}

/* =========================
   SEND ADA
========================= */
export async function sendAda(toAddress, amountAda) {
  if (!lucid) {
    alert("Connect wallet first");
    return;
  }

  const statusEl = document.getElementById("txStatus");
  if (statusEl) statusEl.innerText = "⏳ Sending transaction...";

  try {
    const tx = await lucid
      .newTx()
      .payToAddress(toAddress, {
        lovelace: BigInt(Math.floor(amountAda * 1_000_000))
      })
      .complete();

    const signedTx = await tx.sign().complete();
    const txHash = await signedTx.submit();

    if (statusEl) statusEl.innerText = "✅ Sent! Tx: " + txHash;

  } catch (err) {
    console.error(err);
    if (statusEl) statusEl.innerText = "❌ Transaction failed";
  }
}

/* =========================
   GET ADA BALANCE
========================= */
export async function getAdaBalance() {
  if (!lucid) throw new Error("Wallet not connected");

  const utxos = await lucid.wallet.getUtxos();
  let lovelace = 0n;

  for (const utxo of utxos) {
    lovelace += utxo.assets.lovelace ?? 0n;
  }

  return Number(lovelace) / 1_000_000;
}

/* =========================
   SAVE WALLET
========================= */
export async function saveWalletToDB(address) {
  if (!address) return;

  await fetch("/greenchain/api/save_wallet.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ wallet_address: address })
  });
}

export async function signVote(proposalId, vote) {
  if (!lucid) throw new Error("Wallet not connected");

  const address = await lucid.wallet.address();
  const payload = {
    proposalId,
    vote,
    address,
    timestamp: Date.now()
  };

  const message = JSON.stringify(payload);

  const signature = await lucid.wallet.signMessage(
    address,
    new TextEncoder().encode(message)
  );

  return { payload, signature };
}
