<?php
$title = "Edit Profile";
require "config.php";

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$query = "SELECT * FROM user WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $username = $_POST['username'];
    $telno = $_POST['telno'];
    $role = $_POST['role'];

    // Update user information in the database
    $update_query = "UPDATE user SET username='$username', telno='$telno', role='$role' WHERE user_id='$user_id'";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['role'] = $role;
        // Display a success message using JavaScript and redirect to profile page
        echo "<script>
                alert('Profile updated successfully!');
                window.location.href = 'profile.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error updating profile: " . mysqli_error($conn) . "');
                window.location.href = 'edit_profile.php';
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .profile-container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
        }

        .form-row {
            margin-bottom: 15px;
            position: relative;
        }

        .form-row label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-row input, .form-row select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-row input[readonly] {
            background-color: #f5f5f5; /* Light grey background for readonly fields */
            color: #888; /* Grey text color for readonly fields */
            cursor: not-allowed; /* Show 'not-allowed' cursor when hovering */
        }

        .edit-profile {
            text-align: center;
            margin-top: 15px;
        }

        .edit-profile button {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 10px rgba(0, 123, 255, 0.2);
            margin-top: 10px;
        }

        .edit-profile button:hover {
            background-color: #0056b3;
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4);
        }

        /* Responsive */
        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Edit Profile</h2>
        <form action="edit_profile.php" method="POST">
            <div class="form-row">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo $user_data['username']; ?>" required>
            </div>

            <div class="form-row">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $user_data['email']; ?>" readonly>
            </div>

            <div class="form-row">
                <label for="telno">Telephone:</label>
                <input type="text" name="telno" id="telno" value="<?php echo $user_data['telno']; ?>" required>
            </div>

            <div class="form-row">
                <label for="role">Role:</label>
                <select name="role" id="role">
                    <option value="User" <?php if($user_data['role'] == 'User') echo 'selected'; ?>>User</option>
                    <option value="Organizer" <?php if($user_data['role'] == 'Organizer') echo 'selected'; ?>>Organizer</option>
                </select>
            </div>

            <div class="edit-profile">
                <button type="submit">Save Changes</button>
            </div>

            <div class="edit-profile">
                <a href="profile.php">
                    <button type="button">Back</button>
                </a>
            </div>
        </form>
    </div>
</body>
</html>
