<?php
session_start();
require "../config/db.php";

$email = $_POST["email"];
$password = $_POST["password"];

/* Get user */
$stmt = $conn->prepare(
  "SELECT id, password FROM users WHERE email = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  die("Account not found");
}

$user = $result->fetch_assoc();

/* Verify password */
if (!password_verify($password, $user["password"])) {
  die("Incorrect password");
}

/* Login success */
$_SESSION["user_id"] = $user["id"];
header("Location: ../dashboard.php");
