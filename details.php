<?php
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h2>Users List</h2>
    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Date of Birth</th>
            <th>City</th>
            <th>District</th>
            <th>State</th>
            <th>College</th>
            <th>Role</th>
            <th>Phone</th>
            <th>Registration No</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["user_id"] . "</td>
                        <td>" . $row["username"] . "</td>
                        <td>" . $row["email"] . "</td>
                        <td>" . $row["dob"] . "</td>
                        <td>" . $row["city"] . "</td>
                        <td>" . $row["district"] . "</td>
                        <td>" . $row["state"] . "</td>
                        <td>" . $row["college"] . "</td>
                        <td>" . $row["role"] . "</td>
                        <td>" . $row["phone"] . "</td>
                        <td>" . $row["reg_no"] . "</td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='11'>No users found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
