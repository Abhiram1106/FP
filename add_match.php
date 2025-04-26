<?php
include 'header.php';
include 'db.php'; // Database connection

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user role from the database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$role_result = $stmt->get_result();
$user_data = $role_result->fetch_assoc();

// If role is not found or user is not a host, deny access
if (!$user_data || $user_data['role'] !== 'host') {
    die("<div class='error-message'>Access denied. You do not have permission to add matches.</div>");
}

// Fetch all available games (events) from the database
$games_query = "SELECT * FROM athletics_games"; // Assuming you have a table `athletics_games`
$games_result = mysqli_query($conn, $games_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = mysqli_real_escape_string($conn, $_POST['event_name']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $event_time = mysqli_real_escape_string($conn, $_POST['event_time']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $selected_games = $_POST['games'] ?? []; // Array of selected game IDs

    // Insert the match into the database
    $query = "INSERT INTO athletics_matches (event_name, event_date, event_time, location) 
              VALUES ('$event_name', '$event_date', '$event_time', '$location')";
    if (mysqli_query($conn, $query)) {
        $match_id = mysqli_insert_id($conn); // Get the ID of the newly inserted match

        // Insert selected games into the match_games table
        foreach ($selected_games as $game_id) {
            $game_id = intval($game_id);
            $insert_query = "INSERT INTO solo_games (match_id, game_id) VALUES ($match_id, $game_id)";
            mysqli_query($conn, $insert_query);
        }

        // Redirect to schedule_athletics.php after successful insertion
        header("Location: schedule_athletics.php");
        exit(); // Ensure no further code is executed after redirection
    } else {
        echo "<div class='error-message'>Error: " . mysqli_error($conn) . "</div>";
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
        <h2>Add New Match</h2>

        <!-- Add Match Form -->
        <div class="add-match-form">
            <form action="add_match.php" method="POST">
                <label for="event_name">Event Name:</label>
                <input type="text" id="event_name" name="event_name" required>

                <label for="event_date">Event Date:</label>
                <input type="date" id="event_date" name="event_date" required>

                <label for="event_time">Event Time:</label>
                <input type="time" id="event_time" name="event_time" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>

                <!-- Games Selection -->
                <div class="games-selection">
                    <h3>Select Games to Display:</h3>
                    <div class="checkbox-grid">
                        <?php if (mysqli_num_rows($games_result) > 0): ?>
                            <?php while ($game = mysqli_fetch_assoc($games_result)): ?>
                                <label>
                                    <input type="checkbox" name="games[]" value="<?php echo $game['id']; ?>">
                                    <?php echo htmlspecialchars($game['game_name']); ?>
                                </label>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No games available.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" class="btn">Add Match</button>
            </form>
        </div>
    </div>
</body>
</html>
