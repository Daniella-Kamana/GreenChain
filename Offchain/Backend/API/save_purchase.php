<?php
require "config.php";

$data = json_decode(file_get_contents("php://input"), true);

if (
    empty($data["wallet"]) ||
    empty($data["item"]) ||
    empty($data["price"]) ||
    empty($data["txHash"])
) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO purchases (wallet_address, item_name, price_ada, tx_hash)
     VALUES (:wallet, :item, :price, :tx)"
);

$stmt->execute([
    ":wallet" => $data["wallet"],
    ":item"   => $data["item"],
    ":price"  => $data["price"],
    ":tx"     => $data["txHash"]
]);

echo json_encode(["status" => "ok"]);
