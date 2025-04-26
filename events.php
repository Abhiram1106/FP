<?php
// Database connection
include 'db.php';

// Fetch events from athletics_matches
$sql_athletics = "SELECT event_name, event_date, event_time, location FROM athletics_matches WHERE display_in_schedule = 1 ORDER BY event_date, event_time";
$result_athletics = $conn->query($sql_athletics);

// Fetch events from team_matches
$sql_team = "SELECT event_name, event_date, event_time, location FROM team_matches ORDER BY event_date, event_time";
$result_team = $conn->query($sql_team);

// Combine results into a single array with match type
$events = [];

if ($result_athletics->num_rows > 0) {
    while ($row = $result_athletics->fetch_assoc()) {
        $row['match_type'] = 'Athletics Match'; // Add match type
        $events[] = $row;
    }
}

if ($result_team->num_rows > 0) {
    while ($row = $result_team->fetch_assoc()) {
        $row['match_type'] = 'Team Match'; // Add match type
        $events[] = $row;
    }
}

// Sort events by date and time
usort($events, function ($a, $b) {
    $dateA = strtotime($a['event_date'] . ' ' . $a['event_time']);
    $dateB = strtotime($b['event_date'] . ' ' . $b['event_time']);
    return $dateA - $dateB;
});

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Schedule</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- FontAwesome for icons -->
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            background-image: url('events.jpg');
            /* Updated background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            animation: fadeIn 0.8s ease-in-out;
            /* Reduced from 1.5s to 0.8s */
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            font-size: 3rem;
            color: #fff;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
            animation: slideDown 0.6s ease-in-out;
            /* Reduced from 1s to 0.6s */
        }

        /* Back Button */
        .back-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: rgba(106, 17, 203, 0.8);
            /* Semi-transparent */
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .back-button:hover {
            background-color: rgba(37, 117, 252, 0.8);
            /* Semi-transparent */
            transform: translateY(-2px);
        }

        /* Table Styles */
        table {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.9);
            /* Semi-transparent white background */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-in-out;
            /* Reduced from 1s to 0.8s */
        }

        th,
        td {
            padding: 18px;
            text-align: left;
        }

        th {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 1rem;
        }

        tr:nth-child(even) {
            background-color: rgba(241, 243, 247, 0.8);
            /* Alternate row color */
        }

        tr:hover {
            background-color: rgba(200, 220, 240, 0.8);
            /* Hover effect */
            transform: scale(1.02);
            transition: all 0.2s ease;
            /* Reduced from 0.3s to 0.2s */
        }

        td {
            border-bottom: 1px solid rgba(224, 224, 224, 0.5);
        }

        .event-name {
            font-weight: bold;
            color: #2c3e50;
        }

        .icon {
            margin-right: 10px;
            color: #6a11cb;
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            table {
                width: 100%;
                margin: 10px 0;
            }

            th,
            td {
                padding: 12px;
            }

            h1 {
                font-size: 2.2rem;
            }

            .back-button {
                top: 10px;
                right: 10px;
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <!-- Back Button -->
    <a href="home.php" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to Home
    </a>

    <h1>Event Schedule</h1>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Match Type</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td class="event-name"><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><i class="fas fa-calendar-alt icon"></i><?php echo htmlspecialchars($event['event_date']); ?></td>
                        <td><i class="fas fa-clock icon"></i><?php echo htmlspecialchars($event['event_time']); ?></td>
                        <td><i class="fas fa-map-marker-alt icon"></i><?php echo htmlspecialchars($event['location']); ?></td>
                        <td><?php echo htmlspecialchars($event['match_type']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #777;">No events found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>