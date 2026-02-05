
---

# 🌱 GreenChain Governance Smart Contract — Feature Specification

## 🎯 Purpose of the Contract

The GreenChain Governance smart contract is responsible for:

> **Enforcing fair, verifiable, and immutable community decision-making**
> using **wallet ownership, NFTs, and voting power rules**.

---

## 🧩 Contract Architecture (High Level)

GreenChain governance should use **3 contracts** (not one giant one):

| Contract              | Responsibility            |
| --------------------- | ------------------------- |
| **Proposal Contract** | Proposal lifecycle        |
| **Voting Contract**   | Vote validation & tally   |
| **Treasury Contract** | Execute approved outcomes |

This is **best practice on Cardano**.

---

# 1️⃣ Proposal Smart Contract

### 🔹 Core responsibility

Create and lock governance proposals on-chain.

---

## ✅ Required Features

### 1. Proposal Registration

The contract must:

* Accept a **proposal hash**
* Associate it with:

  * proposer wallet
  * creation time
  * proposal ID

📌 Why hash?

* Saves fees
* Prevents edits
* Ensures immutability

```haskell
data Proposal = {
  proposalId :: Integer,
  proposer   :: PubKeyHash,
  hash       :: BuiltinByteString,
  status     :: Active | Closed
}
```

---

### 2. One-Wallet-One-Proposal (optional)

* Prevent spam
* Require:

  * proposal fee (ADA)
  * OR governance NFT

---

### 3. Proposal State Machine

Allowed transitions:

```
Draft → Active → Closed → Executed
```

❌ No skipping states
❌ No re-opening closed proposals

---

### 4. Deadline Enforcement

The contract must:

* Store voting deadline
* Reject votes after expiry

```haskell
txInfoValidRange
```

---

## 🔒 Security Constraints

* Proposal cannot be modified after submission
* Only proposer (or DAO) can cancel before voting

---

# 2️⃣ Voting Smart Contract

### 🔹 Core responsibility

Validate votes and calculate results fairly.

---

## ✅ Required Features

### 5. Vote Authentication (CRITICAL)

The contract must:

* Verify that:

  * vote is signed by wallet
  * signature matches address

This prevents:

* Fake votes
* Replay attacks

---

### 6. NFT-Gated Voting

The contract must check:

* Does the voter own a **GreenChain Governance NFT**?

```haskell
valueOf txOutValue policyId tokenName > 0
```

❌ No NFT → no vote

---

### 7. One Vote per Wallet

Enforce:

* A wallet can vote **once per proposal**

This can be done using:

* Vote-marker NFT
* Or vote UTxO locking

---

### 8. Weighted Voting

Voting power must be:

* Deterministic
* Verifiable on-chain

Example:

```
weight =
  ADA × 1
+ GreenToken × 10
+ GovernanceNFT × 50
```

---

### 9. Vote Tallying

The contract must:

* Accumulate YES / NO votes
* Store totals immutably
* Reject invalid weights

---

## 🔐 Security Constraints

* No double voting
* No vote editing
* No voting after deadline

---

# 3️⃣ Treasury Smart Contract

### 🔹 Core responsibility

Execute **approved proposals**.

---

## ✅ Required Features

### 10. Result Verification

Before execution:

* Proposal must be:

  * Closed
  * Approved by majority

---

### 11. Controlled Fund Release

Treasury can:

* Release ADA
* Mint tokens
* Trigger external actions

Only if:

* Vote passed
* Quorum met

---

### 12. Quorum Enforcement

Require:

* Minimum participation (e.g. 20%)

Prevents:

* Governance capture
* Low-turnout attacks

---

## 🔒 Security Constraints

* Funds locked until approval
* No single wallet control
* Multi-sig or DAO signature

---

# 4️⃣ Governance NFT Minting Policy

### 🔹 Purpose

Control who can vote.

---

## ✅ Required Features

### 13. Controlled Minting

Governance NFTs:

* Minted only by:

  * DAO
  * Verified authority

---

### 14. Non-Transferable (Optional)

NFT can be:

* Soulbound
* Or transferable with cooldown

---

### 15. Burn on Exit

Optional:

* Burn NFT when user leaves DAO

---

# 5️⃣ Anti-Abuse Protections

### 16. Replay Protection

Votes must include:

* Proposal ID
* Timestamp

---

### 17. Front-Running Resistance

Votes validated by:

* signature
* not transaction ordering

---

### 18. Upgrade Safety

Contract must:

* Support migration
* Freeze old version

---

# 6️⃣ Metadata & Transparency

### 19. On-Chain Metadata

Each proposal must include:

* IPFS link
* JSON hash
* Governance version

---

### 20. Auditability

Anyone can:

* Recalculate vote results
* Verify signatures
* Reproduce outcome

---

# 🧠 Minimal Viable Governance Contract (MVP)

If you want **MVP only**, build this first:

✅ Proposal hash registration
✅ NFT-gated voting
✅ One vote per wallet
✅ Weighted tally
✅ Deadline enforcement

Everything else can be added later.

---

# 🚀 What You Should Build First (Recommended Order)

1️⃣ Governance NFT policy
2️⃣ Proposal registry contract
3️⃣ Voting contract
4️⃣ Treasury contract
5️⃣ Upgrade & quorum logic

---

## 📌 Final Thought

GreenChain governance is **not just a voting system**.

It is:

* A reputation system
* A sustainability incentive engine
* A DAO with real economic impact

