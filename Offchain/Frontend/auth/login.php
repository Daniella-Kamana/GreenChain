<?php
session_start();
require "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ❗ IMPORTANT
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];   // 🔥 THIS IS REQUIRED
            header("Location: ../dashboard.php");
            exit;
        }
    }

    // If login fails
    header("Location: login.php?error=invalid");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>

  <div class="login-container">
    <div class="login-card">
      <h1>Welcome Back</h1>
      <p>Login to your account</p>

      <form method="POST">
        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>

      <div class="register-link">
        <a href="register.php">Create account</a>
      </div>
    </div>
  </div>

</body>
</html>
