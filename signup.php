<?php
include 'db.php';

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username      = trim($_POST['username']);
  $email         = trim($_POST['email']);
  $passwordInput = $_POST['password'];
  $confirmPass   = $_POST['confirm_password'];
  $role          = $_POST['role']; // Get the selected role

  if (!in_array($role, ['host', 'player'])) {
      $errorMessage = "Invalid role selected.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errorMessage = "Invalid email format.";
  } elseif (strlen($passwordInput) < 6) {
      $errorMessage = "Password must be at least 6 characters long.";
  } elseif ($passwordInput !== $confirmPass) {
      $errorMessage = "Passwords do not match.";
  } else {
      $password = password_hash($passwordInput, PASSWORD_DEFAULT);

      // Check for duplicate username or email
      $checkSql = "SELECT * FROM users WHERE username = ? OR email = ?";
      $checkStmt = $conn->prepare($checkSql);
      $checkStmt->bind_param("ss", $username, $email);
      $checkStmt->execute();
      $result = $checkStmt->get_result();

      if ($result->num_rows > 0) {
          $existingUser = $result->fetch_assoc();
          if ($existingUser['username'] == $username) {
              $errorMessage = "Username is not available";
          } elseif ($existingUser['email'] == $email) {
              $errorMessage = "Email is not available";
          }
      } else {
          // Generate unique user ID
          do {
              $userID = "SPM" . mt_rand(100000, 999999);
              $checkID = "SELECT user_id FROM users WHERE user_id = ?";
              $checkIDStmt = $conn->prepare($checkID);
              $checkIDStmt->bind_param("s", $userID);
              $checkIDStmt->execute();
              $checkIDStmt->store_result();
          } while ($checkIDStmt->num_rows > 0);

          $checkIDStmt->close();

          // Insert new user record with the role
          $sql = "INSERT INTO users (user_id, username, email, password, role) VALUES (?, ?, ?, ?, ?)";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("sssss", $userID, $username, $email, $password, $role);

          if ($stmt->execute()) {
              header("Location: login.php");
              exit();
          } else {
              $errorMessage = "Error: " . $stmt->error;
          }
          $stmt->close();
      }
      $checkStmt->close();
  }
  $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UniSportHub</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <!-- Popup for error messages -->
  <div id="popup"><?php echo $errorMessage; ?></div>

  <div class="container">
      <h2>Sign Up</h2>
      <form action="signup.php" method="POST" id="signupForm">
          <select name="role" id="role" required>
              <option value="player">Player</option>
              <option value="host">Host</option>
          </select>
          <input type="text" name="username" id="username" placeholder="Username" required>
          <input type="email" name="email" id="email" placeholder="Email" required>
          <input type="password" name="password" id="password" placeholder="Password" required>
          <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
          <button type="submit">Sign Up</button>
      </form>
      <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>

  <script>
    // Show the pop-up error message if it exists on page load
    window.addEventListener('load', function() {
      var errorMessage = "<?php echo $errorMessage; ?>";
      if (errorMessage !== "") {
        var popup = document.getElementById('popup');
        popup.style.display = 'block';
        setTimeout(function() {
          popup.style.display = 'none';
        }, 3000);
      }
    });

    // Client-side form validation
    document.getElementById('signupForm').addEventListener('submit', function(e) {
      var password = document.getElementById('password').value;
      var confirmPassword = document.getElementById('confirm_password').value;
      var email = document.getElementById('email').value;
      var popup = document.getElementById('popup');
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailRegex.test(email)) {
        e.preventDefault();
        popup.textContent = "Please enter a valid email address.";
        popup.style.display = 'block';
        setTimeout(function() {
          popup.style.display = 'none';
        }, 3000);
        return;
      }

      if (password.length < 6) {
        e.preventDefault();
        popup.textContent = "Password must be at least 6 characters long.";
        popup.style.display = 'block';
        setTimeout(function() {
          popup.style.display = 'none';
        }, 3000);
        return;
      }

      if (password !== confirmPassword) {
        e.preventDefault();
        popup.textContent = "Passwords do not match.";
        popup.style.display = 'block';
        setTimeout(function() {
          popup.style.display = 'none';
        }, 3000);
        return;
      }
    });
  </script>
</body>
</html>