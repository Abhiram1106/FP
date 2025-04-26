<?php
include 'header.php'; // Include the header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSportHub</title>
    <link rel="stylesheet" href="match.css"> <!-- Link to your CSS file -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Main Content -->
    <div class="main-content">
        <h2>Sports Categories</h2>
        <div class="sports-cards">
            <!-- Athletics Card -->
            <div class="card">
                <div class="card-inner">
                    <div class="card-front">
                        <h3>Athletics</h3>
                        <p>Track and field events</p>
                        <div class="icon">🏃‍♂️</div>
                    </div>
                    <div class="card-back">
                        <h3>Athletics Schedule</h3>
                        <p>Upcoming events and timings will be displayed here.</p>
                        <a href="schedule_athletics.php" class="btn">View Schedule</a>
                    </div>
                </div>
            </div>

            <!-- Group Games Card -->
            <div class="card">
                <div class="card-inner">
                    <div class="card-front">
                        <h3>Group Games</h3>
                        <p>Includes Cricket, Kabaddi, Kho-Kho, etc.</p>
                        <div class="icon">🏏</div>
                    </div>
                    <div class="card-back">
                        <h3>Group Games Schedule</h3>
                        <p>Upcoming events and timings will be displayed here.</p>
                        <a href="schedule_team_matches.php" class="btn">View Schedule</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>