<?php
$title = "My Profile"; 
include 'header.php';

// Assuming user_id is stored in the session
$user_id = $_SESSION['user_id'];

// Fetch updated user data from the database
$query = "SELECT * FROM user WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);

// Display user data
$username = $user_data['username'];
$email = $user_data['email'];
$password = $user_data['password'];
$telno = $user_data['telno'];
$role = $user_data['role'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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

        .profile-row {
            display: flex;
            justify-content: space-between;
            padding: 16px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-label {
            font-weight: bold;
            font-size: 16px;
        }

        .profile-value {
            font-weight: 400;
            font-size: 16px;
            color: #333;
            text-align: right;
        }

        .profile-row i {
            margin-right: 10px;
        }

        .edit-profile {
            text-align: center;
            margin-top: 30px;
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
        }

        .edit-profile button:hover {
            background-color: #0056b3;
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4);
        }

        /* Responsive */
        @media (max-width: 600px) {
            .profile-row {
                flex-direction: column;
                text-align: left;
            }

            .profile-value {
                margin-top: 5px;
                text-align: left;
            }
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <h2>My Profile</h2>

        <div class="profile-row">
            <div class="profile-label"><i class="fa fa-id-badge"></i>User ID:</div>
            <div class="profile-value"><?php echo $user_id; ?></div>
        </div>

        <div class="profile-row">
            <div class="profile-label"><i class="fa fa-user"></i>Username:</div>
            <div class="profile-value"><?php echo $username; ?></div>
        </div>

        <div class="profile-row">
            <div class="profile-label"><i class="fa fa-envelope"></i>Email:</div>
            <div class="profile-value"><?php echo $email; ?></div>
        </div>

        <div class="profile-row">
            <div class="profile-label"><i class="fa fa-key"></i>Password:</div>
            <div class="profile-value"><?php echo $password; ?></div>
        </div>

        <div class="profile-row">
            <div class="profile-label"><i class="fa fa-phone"></i>Telephone:</div>
            <div class="profile-value"><?php echo $telno; ?></div>
        </div>

        <div class="profile-row">
            <div class="profile-label"><i class="fa fa-user-tag"></i>Role:</div>
            <div class="profile-value"><?php echo $role; ?></div>
        </div>

        <div class="edit-profile">
            <a href="edit_profile.php"><button>Edit Profile</button></a>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
