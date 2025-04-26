<?php 
include 'header.php';
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check user role
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$role_result = $stmt->get_result();
$user_data = $role_result->fetch_assoc();

if (!$user_data || $user_data['role'] !== 'host') {
    die("Access denied. You do not have permission to add group games.");
}

// Fetch games from `team_games`
$games_query = "SELECT * FROM team_games";
$games_result = mysqli_query($conn, $games_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_name = mysqli_real_escape_string($conn, $_POST['game_name']);
    $game_date = $_POST['game_date'];
    $game_time = $_POST['game_time'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $selected_games = isset($_POST['team_games']) ? $_POST['team_games'] : [];

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert group game
        $query = "INSERT INTO team_matches (event_name, event_date, event_time, location) 
                  VALUES ('$game_name', '$game_date', '$game_time', '$location')";
        if (!mysqli_query($conn, $query)) {
            throw new Exception("Error inserting group game: " . mysqli_error($conn));
        }

        // Get new group game ID
        $group_game_id = mysqli_insert_id($conn);

        // Insert selected team games into linking table
        if (!empty($selected_games)) {
            foreach ($selected_games as $team_game_id) {
                $team_game_id = mysqli_real_escape_string($conn, $team_game_id);
                $link_query = "INSERT INTO group_games (group_game_id, team_game_id) VALUES ('$group_game_id', '$team_game_id')";
                if (!mysqli_query($conn, $link_query)) {
                    throw new Exception("Error inserting into group_games: " . mysqli_error($conn));
                }
            }
        }

        // Commit transaction
        mysqli_commit($conn);
        header("Location: schedule_team_matches.php");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Transaction failed: " . $e->getMessage();
    }
}
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
        <h2>Add New Group Game</h2>

        <div class="add-game-form">
            <h3>Create a New Event</h3>
            <form action="add_game.php" method="POST">
                <label for="game_name">Event Name:</label>
                <input type="text" id="game_name" name="game_name" required>

                <label for="game_date">Event Date:</label>
                <input type="date" id="game_date" name="game_date" required>

                <label for="game_time">Event Time:</label>
                <input type="time" id="game_time" name="game_time" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>

                <div class="games-selection">
                    <h3>Select Games</h3>
                    <div class="checkbox-grid">
                        <?php while ($game = mysqli_fetch_assoc($games_result)): ?>
                            <label>
                                <input type="checkbox" name="team_games[]" value="<?php echo $game['id']; ?>">
                                <?php echo htmlspecialchars($game['game_name']); ?>
                            </label>
                        <?php endwhile; ?>
                    </div>
                </div>

                <button type="submit">Add Group Game</button>
            </form>
        </div>
    </div>
</body>
</html>
