<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user'] = $user['username'];
        // Redirect to teams.php after successful login
        header("Location: home.php");
        exit();
    } else {
        echo "Invalid username or password!";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UniSportHub</title>
  <link rel="stylesheet" href="login.css"> <!-- Link to external CSS file -->
</head>
<body>
  <div class="container">
      <h2>Login</h2>
      <form action="login.php" method="POST">
          <input type="text" name="username" placeholder="Username" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit">Login</button>
      </form>
      <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
  </div>
</body>
</html>