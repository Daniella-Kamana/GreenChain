<?php
session_start();
require "../config/db.php";

$userId = $_SESSION["user_id"];

// Green tokens
$g = $conn->prepare("SELECT green_balance FROM users WHERE id=?");
$g->bind_param("i", $userId);
$g->execute();
$green = $g->get_result()->fetch_assoc()["green_balance"] ?? 0;

// NFTs
$n = $conn->prepare("SELECT COUNT(*) total FROM nfts WHERE user_id=?");
$n->bind_param("i", $userId);
$n->execute();
$nfts = $n->get_result()->fetch_assoc()["total"] ?? 0;

echo json_encode([
  "green" => $green,
  "nfts" => $nfts
]);
