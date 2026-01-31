<?php
require "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare("
  INSERT INTO votes (wallet_address, proposal_id, vote, proof)
  VALUES (?, ?, ?, ?)
");

$msg = json_decode($data["message"], true);

$stmt->bind_param(
  "siss",
  $data["address"],
  $msg["proposalId"],
  $msg["choice"],
  json_encode($data["signed"])
);

$stmt->execute();

echo json_encode(["status" => "ok"]);
