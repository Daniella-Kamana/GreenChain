<?php
session_start();
require "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare("
  INSERT INTO votes
  (proposal_id, wallet_address, choice, message, signature)
  VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param(
  "sssss",
  $data["proposal_id"],
  $data["address"],
  $data["choice"],
  $data["message"],
  json_encode($data["signature"])
);

$stmt->execute();

echo json_encode(["success" => true]);
