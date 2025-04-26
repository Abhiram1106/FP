<?php
include 'header.php';
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red; text-align: center;'>Error: User not logged in. <a href='login.php'>Login here</a></p>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT user_id, username, email, dob, city, district, state, college, role, created_at, phone, reg_no, college_id, profile_pic FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<p style='color: red; text-align: center;'>User not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UniSportHub</title>
  <link rel="stylesheet" href="profile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="profile-card">
        <!-- Profile Picture -->
        <?php if ($user['profile_pic']): ?>
            <div class="profile-pic">
                <img src="profile_pics/<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture" width="150">
            </div>
        <?php endif; ?>

        <!-- User Details -->
        <h1>Hello there <?php echo htmlspecialchars($user['username']); ?></h1>
        <p class="role">
        <?php
        $role = htmlspecialchars($user['role']);
        $icon = '';

        // Assign icons based on role
        switch ($role) {
            case 'admin':
                $icon = '<i class="fas fa-user-shield"></i>'; // Admin icon
                break;
            case 'host':
                $icon = '<i class="fas fa-user-tie"></i>'; // Host icon
                break;
            case 'player':
                $icon = '<i class="fas fa-running"></i>'; // Student icon
                break;
            default:
                $icon = '<i class="fas fa-user"></i>'; // Default user icon
                break;
        }

        echo $icon . ' ' . $role; // Display icon and role
        ?>
        </p>

        <div class="details">
            <p><i class="fas fa-id-card"></i> <?php echo htmlspecialchars($user['user_id']); ?></p>
            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></p>
            <p><i class="fas fa-birthday-cake"></i> <?php echo htmlspecialchars($user['dob'] ?? 'N/A'); ?></p>
            <p><i class="fas fa-school"></i> <?php echo htmlspecialchars($user['college'] ?? 'N/A'); ?></p>
            <p><i class="fas fa-id-card"></i> <?php echo htmlspecialchars($user['reg_no'] ?? 'N/A'); ?></p>
            <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($user['city'] ?? 'N/A'); ?>, <?php echo htmlspecialchars($user['district'] ?? 'N/A'); ?>, <?php echo htmlspecialchars($user['state'] ?? 'N/A'); ?></p>
            <p><i class="fas fa-calendar-alt"></i> Joined: <?php echo htmlspecialchars($user['created_at']); ?></p>
        </div>

        <!-- Action Buttons -->
        <div class="actions">
            <button onclick="window.location.href='update.php'" class="update-button1">Update Profile</button>
            <button onclick="window.location.href='logout.php'" class="logout-button">Logout</button>
        </div>
    </div>

</div>
</body>
</html>