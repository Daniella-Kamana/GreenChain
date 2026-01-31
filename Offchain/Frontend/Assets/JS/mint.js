import {
  Lucid,
  Blockfrost,
  fromText
} from "https://unpkg.com/lucid-cardano@0.10.11/web/mod.js";

export async function mintNFT(lucid, nftName) {

  // 1. Load compiled Plutus policy
  const policy = await fetch("/plutus/policy.plutus")
    .then(res => res.json());

  // 2. Get wallet PKH
  const address = await lucid.wallet.address();
  const pkh =
    lucid.utils.getAddressDetails(address)
      .paymentCredential.hash;

  // 3. Apply parameter (PKH) to policy
  const mintingPolicy = lucid.utils.applyParamsToScript(
    policy,
    [pkh]
  );

  const policyId =
    lucid.utils.mintingPolicyToId(mintingPolicy);

  const assetName = fromText(nftName);

  // 4. Build mint transaction
  const tx = await lucid
    .newTx()
    .mintAssets(
      { [`${policyId}${assetName}`]: 1n },
      mintingPolicy
    )
    .complete();

  const signed = await tx.sign().complete();
  return await signed.submit();
}
