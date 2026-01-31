<?php
session_start();
include "../config/db.php";

$user = $_SESSION['user_id'];

$result = $conn->query("
  SELECT
    (SELECT COUNT(*) FROM transactions WHERE user_id=$user) AS actions,
    (SELECT COUNT(*) FROM nfts WHERE user_id=$user) AS nfts
");

echo json_encode($result->fetch_assoc());
