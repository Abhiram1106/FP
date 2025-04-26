<?php
include 'header.php';
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red; text-align: center;'>Error: User not logged in. <a href='login.php'>Login here</a></p>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user details
$sql = "SELECT dob, city, district, state, college, phone, reg_no, college_id, role, profile_pic FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<p style='color: red; text-align: center;'>User not found.</p>";
    exit;
}

$update_success = false;

// If form is submitted, process the update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dob      = $_POST['dob'] ?? null;
    $city     = trim($_POST['city']);
    $district = trim($_POST['district']);
    $state    = trim($_POST['state']);
    $college  = trim($_POST['college']);
    $phone    = trim($_POST['phone']);
    $reg_no   = trim($_POST['reg_no']);
    $college_id = ($user['role'] === 'host' && isset($_POST['college_id'])) ? trim($_POST['college_id']) : "";

    // Handle profile picture upload or removal
    $profile_pic = $user['profile_pic']; // Default to current profile picture

    if (isset($_POST['remove_profile_pic']) && $_POST['remove_profile_pic'] == '1') {
        // Remove the profile picture
        if (!empty($user['profile_pic'])) {
            $file_path = 'profile_pics/' . $user['profile_pic'];
            if (file_exists($file_path)) {
                unlink($file_path); // Delete the file from the server
            }
            $profile_pic = null; // Set profile_pic to NULL in the database
        }
    } elseif (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        // Upload new profile picture
        $filename = $_FILES['profile_pic']['name'];
        $tempname = $_FILES['profile_pic']['tmp_name'];
        $folder = 'profile_pics/' . $filename;

        if (move_uploaded_file($tempname, $folder)) {
            // Delete the old profile picture if it exists
            if (!empty($user['profile_pic'])) {
                $old_file_path = 'profile_pics/' . $user['profile_pic'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
            $profile_pic = $filename;
        } else {
            echo "<p style='color: red; text-align: center;'>Error uploading profile picture.</p>";
            exit;
        }
    }

    $update_sql = "UPDATE users SET dob = ?, city = ?, district = ?, state = ?, college = ?, phone = ?, reg_no = ?, college_id = ?, profile_pic = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    
    if (!$update_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $update_stmt->bind_param("ssssssssss", $dob, $city, $district, $state, $college, $phone, $reg_no, $college_id, $profile_pic, $user_id);
    
    if ($update_stmt->execute()) {
        echo "<script>
                alert('Your details have been updated successfully!');
                window.location.href='profile.php';
              </script>";
        exit();
    } else {
        echo "<p style='color: red; text-align: center;'>Error updating profile.</p>";
    }
    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>UniSportHub</title>
  <link rel="stylesheet" href="profile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="profile-card">
        <h2>Update Profile</h2>
        <form action="update.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile_pic"><i class="fas fa-camera"></i> Profile Picture:</label>
                <input type="file" name="profile_pic" id="profile_pic">
                <?php if ($user['profile_pic']): ?>
                    <div class="form-group">
                        <label for="remove_profile_pic">
                            <button type="button" id="remove_profile_pic_button" class="remove-button">
                                <i class="fas fa-trash"></i> Remove Profile Picture
                            </button>
                        </label>
                        <input type="hidden" name="remove_profile_pic" id="remove_profile_pic" value="0">
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="dob"><i class="fas fa-birthday-cake"></i> Date of Birth:</label>
                <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="phone"><i class="fas fa-phone"></i> Phone Number:</label>
                <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="college"><i class="fas fa-school"></i> College Name:</label>
                <select name="college" id="college">
                    <option value="" disabled>Select College</option>
                    <?php
                    // List of B.Tech colleges in Andhra Pradesh
                    $colleges = [
                        "Andhra University College of Engineering, Visakhapatnam",
                        "JNTU College of Engineering, Anantapur",
                        "Gayatri Vidya Parishad College of Engineering, Visakhapatnam",
                        "Sri Venkateswara University College of Engineering, Tirupati",
                        "Vignan's Foundation for Science, Technology & Research, Guntur",
                        "KL University, Guntur",
                        "SRKR Engineering College, Bhimavaram",
                        "RVR & JC College of Engineering, Guntur",
                        "GMR Institute of Technology, Rajam",
                        "Pragati Engineering College, Surampalem",
                        "Aditya Engineering College, Surampalem",
                        "Anil Neerukonda Institute of Technology & Sciences, Visakhapatnam",
                        "Avanthi Institute of Engineering & Technology, Visakhapatnam",
                        "Bapatla Engineering College, Bapatla",
                        "Chaitanya Bharathi Institute of Technology, Hyderabad",
                        "DVR & Dr. HS MIC College of Technology, Kanchikacherla",
                        "Godavari Institute of Engineering and Technology, Rajahmundry",
                        "Gudlavalleru Engineering College, Gudlavalleru",
                        "Lendi Institute of Engineering and Technology, Vizianagaram",
                        "Malla Reddy College of Engineering & Technology, Hyderabad",
                        "MVGR College of Engineering, Vizianagaram",
                        "NRI Institute of Technology, Agiripalli",
                        "Potti Sriramulu Chalavadi Mallikharjuna Rao College of Engineering & Technology, Vijayawada",
                        "Prasad V. Potluri Siddhartha Institute of Technology, Vijayawada",
                        "QIS College of Engineering & Technology, Ongole",
                        "Raghu Engineering College, Visakhapatnam",
                        "Sasi Institute of Technology & Engineering, Tadepalligudem",
                        "Siddhartha Institute of Science and Technology, Puttur",
                        "Sri Vasavi Engineering College, Tadepalligudem",
                        "Velagapudi Ramakrishna Siddhartha Engineering College, Vijayawada",
                        "Vignan Institute of Technology and Science, Hyderabad",
                        "Vishnu Institute of Technology, Bhimavaram",
                        "Yogananda Institute of Technology and Science, Tirupati",
                    ];

                    foreach ($colleges as $college_name) {
                        $selected = ($user['college'] == $college_name) ? "selected" : "";
                        echo "<option value='$college_name' $selected>$college_name</option>";
                    }
                    ?>
                </select>
            </div>

            <?php if ($user['role'] === 'host'): ?>
            <div class="form-group">
                <label for="college_id"><i class="fas fa-id-badge"></i> College ID:</label>
                <input type="text" name="college_id" id="college_id" value="<?php echo htmlspecialchars($user['college_id'] ?? ''); ?>">
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="reg_no"><i class="fas fa-id-card"></i> Registration Number:</label>
                <input type="text" name="reg_no" id="reg_no" value="<?php echo htmlspecialchars($user['reg_no'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="city"><i class="fas fa-map-marker-alt"></i> Town/City:</label>
                <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="district"><i class="fas fa-map-pin"></i> District:</label>
                <select name="district" id="district">
                    <option value="" disabled>Select District</option>
                    <?php
                    $districts = [
                        "Anakapalli", "Ananthapuramu", "Annamayya", "Bapatla", "Chittoor", "Dr. B.R. Ambedkar Konaseema",
                        "East Godavari", "Eluru", "Guntur", "Kakinada", "Krishna", "Kurnool", "Nandyal", "Nellore",
                        "NTR", "Palnadu", "Parvathipuram Manyam", "Prakasam", "Srikakulam", "Sri Sathya Sai",
                        "Tirupati", "Visakhapatnam", "Vizianagaram", "West Godavari", "YSR Kadapa"
                    ];
                    foreach ($districts as $district) {
                        $selected = ($user['district'] == $district) ? "selected" : "";
                        echo "<option value='$district' $selected>$district</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="state"><i class="fas fa-map"></i> State:</label>
                <select name="state" id="state">
                    <option value="Andhra Pradesh" selected>Andhra Pradesh</option>
                </select>
            </div>

            <button type="submit" class="update-button">Update Profile</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const removeButton = document.getElementById('remove_profile_pic_button');
    const removeCheckbox = document.getElementById('remove_profile_pic');

    if (removeButton && removeCheckbox) {
        removeButton.addEventListener('click', function () {
            if (removeCheckbox.value === '0') {
                removeCheckbox.value = '1';
                removeButton.innerHTML = `<i class="fas fa-check"></i> Remove Profile Picture`;
                removeButton.style.backgroundColor = '#28a745'; // Change to green to indicate active state
            } else {
                removeCheckbox.value = '0';
                removeButton.innerHTML = `<i class="fas fa-trash"></i> Remove Profile Picture`;
                removeButton.style.backgroundColor = '#dc3545'; // Revert to original color
            }
        });
    }
});
</script>
</body>
</html>