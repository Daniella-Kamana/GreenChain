<?php
session_start();
include "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare(
  "INSERT INTO nfts (user_id, nft_name, policy_id, asset_name)
   VALUES (?, ?, ?, ?)"
);

$stmt->bind_param(
  "isss",
  $_SESSION['user_id'],
  $data['name'],
  $data['policyId'],
  $data['assetName']
);

$stmt->execute();
echo json_encode(["status" => "minted"]);
