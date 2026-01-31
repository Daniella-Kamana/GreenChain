<?php
require "config.php";

if (!isset($_SESSION["user_id"])) {
  header("Location: ../login.php");
  exit;
}

$user_id = $_SESSION["user_id"];
$action  = $_POST["action"];
$proof   = $_POST["proof"];

$stmt = $conn->prepare(
  "INSERT INTO eco_actions (user_id, action, proof) VALUES (?, ?, ?)"
);
$stmt->bind_param("iss", $user_id, $action, $proof);
$stmt->execute();

header("Location: ../dashboard.php");
