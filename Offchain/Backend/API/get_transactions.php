<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION["user_id"])) {
  http_response_code(401);
  exit;
}

$userId = $_SESSION["user_id"];

// get wallet address
$stmt = $conn->prepare("SELECT wallet_address FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || !$user["wallet_address"]) {
  echo json_encode([]);
  exit;
}

$address = $user["wallet_address"];
$blockfrostKey = "preprodsJw0qxJfYA3iXqc7HRc0rtyI8Dmv9ny3";

$url = "https://cardano-preprod.blockfrost.io/api/v0/addresses/$address/transactions?count=5";

$ch = curl_init($url);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    "project_id: $blockfrostKey"
  ]
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;

$stmt = $conn->prepare("
  SELECT tx_hash, type, created_at
  FROM transactions
  WHERE user_id = ?
  ORDER BY created_at DESC
");
