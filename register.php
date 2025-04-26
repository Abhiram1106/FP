<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'header.php';
include 'db.php'; // Include the database connection

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate match_id
if (!isset($_GET['match_id']) || empty($_GET['match_id']) || !is_numeric($_GET['match_id'])) {
    die("Invalid match ID.");
}

$match_id = intval($_GET['match_id']); // Ensure integer match ID

// Check if the match exists and determine its type (solo or group)
$match_check_stmt = $conn->prepare("
    SELECT 'solo' AS match_type FROM athletics_matches WHERE id = ? 
    UNION 
    SELECT 'group' AS match_type FROM team_matches WHERE id = ?
");
$match_check_stmt->bind_param("ii", $match_id, $match_id);
$match_check_stmt->execute();
$match_check_result = $match_check_stmt->get_result();

if ($match_check_result->num_rows === 0) {
    die("Error: Invalid match. Please try again.");
}

$match_type_row = $match_check_result->fetch_assoc();
$match_type = $match_type_row['match_type'];

// Fetch match details based on match type
if ($match_type === 'solo') {
    $match_stmt = $conn->prepare("
        SELECT m.event_name, m.event_date, m.event_time, m.location, g.game_name, g.id AS game_id
        FROM athletics_matches m
        JOIN solo_games mg ON m.id = mg.match_id
        JOIN athletics_games g ON mg.game_id = g.id
        WHERE m.id = ?
    ");
} else {
    $match_stmt = $conn->prepare("
        SELECT m.event_name, m.event_date, m.event_time, m.location, g.game_name, g.id AS game_id
        FROM team_matches m
        JOIN group_games mg ON m.id = mg.group_game_id  -- Corrected column usage
        JOIN team_games g ON mg.team_game_id = g.id
        WHERE m.id = ?
    ");
}

$match_stmt->bind_param("i", $match_id);
$match_stmt->execute();
$match_result = $match_stmt->get_result();

if ($match_result->num_rows === 0) {
    die("Error: Match not found.");
}

$match = $match_result->fetch_assoc();
$game_id = $match['game_id']; // Correct assignment of game ID

$match_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSportHub - Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="main-content">
        <h2>Register for <?= htmlspecialchars($match['event_name']); ?></h2>
        <p><strong>Event:</strong> <?= htmlspecialchars($match['event_name']); ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($match['event_date']); ?></p>
        <p><strong>Time:</strong> <?= htmlspecialchars($match['event_time']); ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($match['location']); ?></p>
        <p><strong>Game:</strong> <?= htmlspecialchars($match['game_name']); ?></p>

        <form method="POST">
            <h3>Registration Fee</h3>
            <p><strong>Amount:</strong> ₹99.00</p>

            <h3>Payment Section</h3>
            <label for="payment_method">Select Payment Method:</label>
            <select name="payment_method" id="payment_method" required>
                <option value="Credit Card">Credit Card</option>
                <option value="UPI">UPI</option>
                <option value="PayPal">PayPal</option>
                <option value="Bank Transfer">Bank Transfer</option>
            </select>

            <label for="transaction_id">Transaction ID:</label>
            <input type="text" name="transaction_id" id="transaction_id" required>

            <button type="submit" class="btn">Complete Registration</button>
        </form>
    </div>
</body>
</html>