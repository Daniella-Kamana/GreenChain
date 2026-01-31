// assets/js/wallet-core.js
import { Lucid, Blockfrost } from "https://unpkg.com/lucid-cardano/web/mod.js";

let lucid = null;

export async function connectLace() {
  if (!window.cardano?.lace) {
    throw new Error("Lace wallet not installed");
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
  return address;
}

export async function getAdaBalance() {
  if (!lucid) throw new Error("Wallet not connected");

  const utxos = await lucid.wallet.getUtxos();
  let lovelace = 0n;

  for (const u of utxos) {
    lovelace += u.assets.lovelace ?? 0n;
  }

  return Number(lovelace) / 1_000_000;
}
