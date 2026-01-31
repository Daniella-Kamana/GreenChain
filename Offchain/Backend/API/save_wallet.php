<?php
session_start();
require "../config/db.php";

header("Content-Type: application/json");

/* =========================
   AUTH CHECK
========================= */
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

/* =========================
   READ INPUT
========================= */
$data = json_decode(file_get_contents("php://input"), true);

if (empty($data["wallet_address"])) {
    http_response_code(400);
    echo json_encode(["error" => "Wallet address required"]);
    exit;
}

$wallet = trim($data["wallet_address"]);
$userId = $_SESSION["user_id"];

/* Optional: basic validation */
if (strlen($wallet) < 20) {
    http_response_code(422);
    echo json_encode(["error" => "Invalid wallet address"]);
    exit;
}

/* =========================
   UPDATE DB
========================= */
$stmt = $conn->prepare(
    "UPDATE users SET wallet_address = ? WHERE id = ?"
);
$stmt->bind_param("si", $wallet, $userId);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Database update failed"]);
    exit;
}

echo json_encode([
    "success" => true,
    "wallet"  => $wallet
]);
