<?php
session_start();
include "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare(
  "INSERT INTO transactions (user_id, item_name, price_ada, tx_hash)
   VALUES (?, ?, ?, ?)"
);

$stmt->bind_param(
  "isds",
  $_SESSION['user_id'],
  $data['item'],
  $data['price'],
  $data['txHash']
);

$stmt->execute();
echo json_encode(["status" => "success"]);
