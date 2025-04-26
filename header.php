<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSportHub</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        a img {
            border: none;
        }

        a:focus {
            outline: none;
        }

        a {
            border: none;
        }

        .welcome-text {
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            /* White color for the text */
        }

        .welcome-text a {
            color: white;
            /* White color for the link */
            text-decoration: none;
            border: none;
        }

        .welcome-text a:hover {
            color: white;
            /* Ensure the color stays white on hover */
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="logo">
            <a href="home.php"><img src="logo.jpg" alt="UniSportHub Logo" class="logo-image" style="cursor:pointer"></a>
        </div>
        <ul class="nav-links">
            <li><a href="home.php"><i class="fa-solid fa-house"></i> Home</a></li>
            <li><a href="matches.php"><i class="fa-solid fa-futbol"></i> Matches</a></li>
            <!-- <li><a href="score.html"><i class="fa-solid fa-score"></i>Scoreboard</a></li> -->
            <li><a href="registration.php"><i class="fa-solid fa-clipboard-list"></i> Register</a></li>
            <li><a href="events.php"><i class="fa-solid fa-calendar"></i> Event Calender</a></li>
            <li><a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a></li>
            <li><a href="contact.php"><i class="fa-solid fa-phone"></i> Contact</a></li>

        </ul>
        <div class="profile-section">
            <span class="welcome-text"><a href="profile.php" class="click-to-profile"><i class="fa-solid fa-user"></i> Welcome <?php echo $_SESSION['user']; ?></a></span>
            <!-- <a href="settings.html" class="settings-icon"><i class="fa-solid fa-gear"></i></a> -->
            <a href="logout.php" class="logout-btn"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </nav>
</body>

</html>