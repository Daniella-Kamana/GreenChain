<?php
session_start();
require "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['wallet'])) {
  http_response_code(400);
  echo json_encode(["error" => "Wallet missing"]);
  exit;
}

$wallet = $data['wallet'];

// Save wallet to session
$_SESSION['wallet_address'] = $wallet;

// Optional: save to DB
$stmt = $conn->prepare(
  "INSERT INTO wallets (wallet_address) VALUES (?) 
   ON DUPLICATE KEY UPDATE wallet_address = wallet_address"
);
$stmt->bind_param("s", $wallet);
$stmt->execute();

echo json_encode(["status" => "ok"]);
