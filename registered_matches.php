<?php
include 'header.php';
include 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Treat as string since user_id is VARCHAR

// Fetch registered matches
$matches_stmt = $conn->prepare("
    SELECT r.match_id, r.match_type, m.event_name, m.event_date, m.event_time, m.location, g.game_name, r.payment_method, r.transaction_id
    FROM registrations r
    JOIN (
        SELECT id, event_name, event_date, event_time, location FROM athletics_matches
        UNION ALL
        SELECT id, event_name, event_date, event_time, location FROM team_matches
    ) m ON r.match_id = m.id
    JOIN (
        SELECT id, game_name FROM athletics_games
        UNION ALL
        SELECT id, game_name FROM team_games
    ) g ON r.game_id = g.id
    WHERE r.user_id = ?
    ORDER BY m.event_date ASC
");

$matches_stmt->bind_param("s", $user_id);
$matches_stmt->execute();
$matches_result = $matches_stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Matches</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="main-content">
        <h2>Your Registered Matches</h2>

        <?php if ($matches_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Game</th>
                        <th>Payment Method</th>
                        <th>Transaction ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($match = $matches_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($match['event_name']); ?></td>
                            <td><?= htmlspecialchars($match['event_date']); ?></td>
                            <td><?= htmlspecialchars($match['event_time']); ?></td>
                            <td><?= htmlspecialchars($match['location']); ?></td>
                            <td><?= htmlspecialchars($match['game_name']); ?></td>
                            <td><?= htmlspecialchars($match['payment_method']); ?></td>
                            <td><?= htmlspecialchars($match['transaction_id']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have not registered for any matches yet.</p>
        <?php endif; ?>

        <a href="schedule.php" class="btn">Back to Schedule</a>
    </div>
</body>
</html>

<?php
$matches_stmt->close();
$conn->close();
?>
