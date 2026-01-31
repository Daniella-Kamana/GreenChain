# 🌱 GreenChain

**Decentralized Sustainability Rewards & Governance Platform (Cardano)**

---

## 📌 Overview

**GreenChain** is a hybrid **Web2 + Web3** platform built on **Cardano** that rewards sustainable actions, issues NFTs, and enables community governance through wallet-based voting.

The project integrates:

* **Cardano wallets (Lace)**
* **Lucid SDK**
* **Blockfrost API**
* **PHP + MySQL backend**
* **Hybrid off-chain governance with on-chain anchoring**

---

## 📚 Table of Contents

1. [Features](#-features)
2. [Architecture](#-architecture)
3. [Technology Stack](#-technology-stack)
4. [Repository Structure](#-repository-structure)
5. [Wallet Integration](#-wallet-integration)
6. [On-Chain Components](#-on-chain-components)
7. [Off-Chain Components](#-off-chain-components)
8. [Governance System](#-governance-system)
9. [NFT System](#-nft-system)
10. [Database Schema](#-database-schema)
11. [Security Model](#-security-model)
12. [Environment Setup](#-environment-setup)
13. [Future Improvements](#-future-improvements)
14. [License](#-license)

---

## ✨ Features

* ✅ User authentication (PHP sessions)
* ✅ Lace wallet connection (Lucid)
* ✅ Real ADA balance display
* ✅ ADA send & receive
* ✅ GreenToken (GRT) rewards
* ✅ NFT badges for sustainability actions
* ✅ DAO-style governance
* ✅ Wallet-signed voting
* ✅ On-chain proposal anchoring
* ✅ NFT-gated voting
* ✅ Transaction history

---

## 🏗 Architecture

```
Browser (UI)
 ├── HTML / CSS / JS
 ├── wallet.js (Lucid)
 │
 ├── PHP API Layer
 │   ├── Auth
 │   ├── Wallet sync
 │   ├── Governance logic
 │   └── Transaction logging
 │
 ├── MySQL Database
 │
 └── Cardano Blockchain (Preprod)
     ├── Wallet signing
     ├── ADA transfers
     ├── Metadata anchoring
     └── NFT ownership
```

---

## 🧰 Technology Stack

### Frontend

* HTML5
* CSS3
* Vanilla JavaScript
* ES Modules

### Backend

* PHP 8+
* MySQL
* REST-style APIs

### Blockchain

* Cardano (Preprod)
* Lucid SDK
* Blockfrost API
* Lace Wallet

---

## 🗂 Repository Structure

```
greenchain/
│
├── assets/
│   ├── css/
│   │   ├── dashboard.css
│   │   ├── governance.css
│   │   └── main.css
│   │
│   └── js/
│       ├── wallet.js          # Wallet connection & ADA logic
│       ├── governance.js      # Voting power & governance logic
│       ├── dashboard.js
│       └── marketplace.js
│
├── api/                        # OFF-CHAIN API LAYER
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   │
│   ├── save_wallet.php
│   ├── get_dashboard.php
│   ├── get_voting_power.php
│   ├── submit_vote.php
│   ├── anchor_proposal.php
│   └── get_transactions.php
│
├── onchain/                    # ON-CHAIN LOGIC (Lucid)
│   ├── proposals/
│   │   └── anchorProposal.js
│   ├── voting/
│   │   └── signVote.js
│   └── nfts/
│       └── checkOwnership.js
│
├── database/
│   ├── schema.sql
│   └── seed.sql
│
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
│
├── dashboard.php
├── governance.php
├── marketplace.php
├── nfts.php
├── index.php
│
├── config/
│   └── db.php
│
├── README.md
└── .env.example
```

---

## 🔑 Wallet Integration

### Supported Wallet

* **Lace**

### Core Wallet Features

* Persistent connection across pages
* Safe signing (no private keys exposed)
* ADA balance via UTXO scan
* Transaction submission

```js
lucid.selectWallet(api);
window.lucid = lucid;
```

---

## ⛓ On-Chain Components

### 1️⃣ ADA Transactions

* Built & signed client-side
* Submitted via Lucid
* Hash stored off-chain

### 2️⃣ Proposal Anchoring

* Proposal content hashed
* Hash stored as Cardano metadata
* Tx hash saved to DB

### 3️⃣ Vote Signing

* Wallet signs vote payload
* Signature stored for verification

### 4️⃣ NFT Ownership

* NFT policy ID checked in wallet UTXOs
* Used for gated voting

---

## 🖥 Off-Chain Components

### PHP APIs

* Wallet persistence
* Voting power calculation
* Governance records
* Transaction history
* User rewards & NFTs

### Why Hybrid?

* Faster UX
* Lower fees
* On-chain integrity preserved

---

## 🏛 Governance System

### Voting Power Formula

```
Voting Power =
  ADA × 1
+ GreenToken × 10
+ NFT × 50
```

### Governance Model

* Wallet-authenticated
* NFT-gated
* Signed votes
* On-chain anchored proposals

---

## 🏆 NFT System

* NFTs represent verified sustainability actions
* Stored on Cardano
* Used for:

  * Voting eligibility
  * Voting power boosts
  * Community reputation

---

## 🗄 Database Schema (Core)

### users

* id
* email
* green_balance
* wallet_address
* role

### proposals

* id
* title
* description
* proposal_hash
* onchain_tx

### votes

* proposal_id
* wallet_address
* vote
* signature

### nfts

* user_id
* policy_id
* asset_name

---

## 🔐 Security Model

* ✅ No private keys stored
* ✅ Wallet-signed transactions
* ✅ Prepared SQL statements
* ✅ Session-based authentication
* ✅ On-chain integrity anchoring

---

## ⚙ Environment Setup

1. Install XAMPP
2. Import database schema
3. Add Blockfrost API key
4. Use Cardano **Preprod**
5. Open in HTTPS (required for wallets)

---

## 🚀 Future Improvements

* Smart contract treasury
* On-chain voting (CIP-1694)
* NFT staking
* Mainnet deployment
* DAO proposal execution

---

## 🌱 Final Note

GreenChain demonstrates a **real-world Cardano dApp architecture**, combining:

* Sustainability incentives
* NFTs
* DAO governance
* Secure wallet interactions

---
