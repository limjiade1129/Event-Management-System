<?php
$title = "Change Password";
require "config.php";

$user_id = $_SESSION['user_id'];
$error_message = '';
$current_password = '';
$new_password = '';
$confirm_password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the hashed password from the database
    $query = "SELECT password FROM user WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user_data = mysqli_fetch_assoc($result);
    $hashed_password_from_db = $user_data['password'];

    // Verify the current password (adjust if you used md5 for hashing)
    if (md5($current_password) === $hashed_password_from_db) {
        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_new_password = md5($new_password);

            // Update the password in the database
            $update_query = "UPDATE user SET password = '$hashed_new_password' WHERE user_id = '$user_id'";
            if (mysqli_query($conn, $update_query)) {
                echo "<script>
                        alert('Password changed successfully!');
                        window.location.href = 'profile.php';
                      </script>";
                exit();
            } else {
                $error_message = "Error updating password. Please try again.";
            }
        } else {
            $error_message = "New password and confirm password do not match.";
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
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

        .form-row input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .edit-profile {
            text-align: center;
            margin-top: 15px;
        }

        .edit-profile button {
            background-color: #3498db;
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
            background-color: #2980b9;
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
        <h2>Change Password</h2>
        <form action="change_password.php" method="POST" onsubmit="return validateForm()">
            <div class="form-row">
                <label for="current_password">Current Password:</label>
                <input type="password" name="current_password" id="current_password" value="<?php echo htmlspecialchars($current_password); ?>" required>
            </div>
            <div class="form-row">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" value="<?php echo htmlspecialchars($new_password); ?>" required>
            </div>
            <div class="form-row">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" value="<?php echo htmlspecialchars($confirm_password); ?>" required>
            </div>
            <div class="edit-profile">
                <button type="submit">Change Password</button>
            </div>
            <div class="edit-profile">
                <a href="profile.php">
                    <button type="button">Back</button>
                </a>
            </div>
        </form>
    </div>

    <?php if ($error_message): ?>
        <script>
            alert("<?php echo $error_message; ?>");
        </script>
    <?php endif; ?>

    <script>
        function validateForm() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

            // Validate new password against regex
            if (!passwordRegex.test(newPassword)) {
                alert('New password must be at least 8 characters long, and include an uppercase letter, a lowercase letter, a number, and a special character.');
                return false;
            }

            // Check if new password and confirm password match
            if (newPassword !== confirmPassword) {
                alert('New password and confirm password do not match.');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
