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

// Fetch solo game matches
$solo_match_stmt = $conn->prepare("
    SELECT m.id, m.event_name, m.event_date, m.event_time, m.location, g.game_name 
    FROM athletics_matches m
    JOIN solo_games mg ON m.id = mg.match_id
    JOIN athletics_games g ON mg.game_id = g.id
    ORDER BY m.event_date ASC, m.event_time ASC");
$solo_match_stmt->execute();
$solo_match_result = $solo_match_stmt->get_result();

// Fetch team game matches
$team_match_stmt = $conn->prepare("
    SELECT m.id, m.event_name, m.event_date, m.event_time, m.location, g.game_name 
    FROM team_matches m
    JOIN group_games mg ON m.id = mg.group_game_id
    JOIN team_games g ON mg.team_game_id = g.id
    ORDER BY m.event_date ASC, m.event_time ASC");
$team_match_stmt->execute();
$team_match_result = $team_match_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSportHub</title>
    <link rel="stylesheet" href="schedule.css">
    <style>
        .matches-container {
            display: flex;
            justify-content: space-between;
            gap: 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }
        .matches-column {
            flex: 1;
        }
        .matches-column h3 {
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

.glass-box {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 15px 25px;
    border-radius: 15px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

.glass-box:hover {
    transform: scale(1.05);
}

/* Glass Effect Button */
.btn-glass {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: white;
    padding: 12px 20px;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-glass:hover {
    background: linear-gradient(135deg, #2575fc, #6a11cb);
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
}

    </style>
</head>
<body>
    
    <div class="main-content">
        <h2>Register for Match</h2>

        <div class="matches-container">
            <div class="matches-column">
                <h3>Solo Matches</h3>
                <div class="matches-list">
                    <?php while ($match = $solo_match_result->fetch_assoc()): ?>
                        <div class="match-card">
                            <h3><?= htmlspecialchars($match['event_name']); ?></h3>
                            <p><strong>Date:</strong> <?= htmlspecialchars($match['event_date']); ?></p>
                            <p><strong>Time:</strong> <?= htmlspecialchars($match['event_time']); ?></p>
                            <p><strong>Location:</strong> <?= htmlspecialchars($match['location']); ?></p>
                            <p><strong>Game:</strong> <?= htmlspecialchars($match['game_name']); ?></p>
                            <div class="register-section">
                                <a href="register.php?match_id=<?= $match['id']; ?>" class="btn register-btn">Register for this Match</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="matches-column">
                <h3>Team Matches</h3>
                <div class="matches-list">
                    <?php while ($match = $team_match_result->fetch_assoc()): ?>
                        <div class="match-card">
                            <h3><?= htmlspecialchars($match['event_name']); ?></h3>
                            <p><strong>Date:</strong> <?= htmlspecialchars($match['event_date']); ?></p>
                            <p><strong>Time:</strong> <?= htmlspecialchars($match['event_time']); ?></p>
                            <p><strong>Location:</strong> <?= htmlspecialchars($match['location']); ?></p>
                            <p><strong>Game:</strong> <?= htmlspecialchars($match['game_name']); ?></p>
                            <div class="register-section">
                                <a href="register.php?match_id=<?= $match['id']; ?>" class="btn register-btn">Register for this Match</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <div class="add-match-link">
            <a href="schedule_athletics.php" class="btn">Back to Schedules</a>
        </div>
    </div>
</body>

</html>
