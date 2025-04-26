<?php
session_start();
include 'db.php'; // Database connection

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user role from the database
$user_id = $_SESSION['user_id']; // user_id is VARCHAR

$stmt = $conn->prepare("SELECT role FROM users WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$role_result = $stmt->get_result();
$user_data = $role_result->fetch_assoc();
$stmt->close();

if (!$user_data || !isset($user_data['role'])) {
    die("Error: Unable to retrieve user role.");
}

$role = trim(strtolower($user_data['role'])); // Standardize role for comparison

// Handle unexpected role values safely
$allowed_roles = ['host', 'player'];
if (!in_array($role, $allowed_roles)) {
    error_log("Unexpected role value: " . $role);
    header("Location: error.php"); // Redirect to a proper error page
    exit();
}

// Fetch matches and their associated games
$matches_query = "
    SELECT m.*, g.game_name 
    FROM athletics_matches m
    LEFT JOIN solo_games mg ON m.id = mg.match_id
    LEFT JOIN athletics_games g ON mg.game_id = g.id
    ORDER BY m.event_date ASC
";

$matches_result = $conn->query($matches_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSportHub</title>
    <link rel="stylesheet" href="schedule.css"> 
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main-content">
        <h2>Athletics Schedule</h2>
        <div class="back-button">
            <button onclick="window.location.href='matches.php'" class="btn-back">← Back to Matches</button>
        </div>
        <!-- Display Matches -->
        <div class="matches-list">
            <?php if ($matches_result->num_rows > 0): ?>
                <?php while ($match = $matches_result->fetch_assoc()): ?>
                    <div class="match-card">
                        <h3><?= htmlspecialchars($match['event_name']); ?></h3>
                        <p><strong>Date:</strong> <?= htmlspecialchars($match['event_date']); ?></p>
                        <p><strong>Time:</strong> <?= htmlspecialchars($match['event_time']); ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($match['location']); ?></p>
                        <p><strong>Game:</strong> <?= htmlspecialchars($match['game_name'] ?? 'N/A'); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-matches">No matches scheduled yet. Check back later!</p>
            <?php endif; ?>
        </div>

        <?php if ($role === 'host'): ?>
            <div class="add-match-link">
                <a href="add_match.php" class="btn">Add New Match</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
