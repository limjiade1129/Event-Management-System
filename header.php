<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

// Get the user role from the session
$role = $_SESSION["role"];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Homepage'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5; /* Light grey background for the website */
        }

        /* Header Styles */
        .header {
            background-color: #fff; /* White background for the header */
            padding: 15px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff; /* Blue color for the logo */
            text-decoration: none;
        }

        /* Navigation Styles */
        .navbar {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .navbar a {
            text-decoration: none;
            color: #333; /* Dark grey text color */
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #007bff; /* Blue on hover */
        }

        /* User Dropdown Styles */
        .user-menu {
            position: relative;
            display: inline-block;
        }

        .user-icon {
            cursor: pointer;
            font-size: 20px;
            color: #333;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 150px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            z-index: 1000;
        }

        .dropdown-content a {
            color: #333;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .user-menu:hover .dropdown-content {
            display: block; /* Show dropdown on hover */
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .navbar {
                display: none; /* Hide navbar on smaller screens */
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <a href="homepage.php" class="logo">EventGo</a> <!-- Logo -->

        <!-- Navbar -->
        <div class="navbar">
            <a href="homepage.php">Home</a>

            <?php if ($role === 'organizer') : ?>
                <a href="userlist.php">Event List</a>
            <?php endif; ?>
        </div>

        <!-- User Dropdown Menu -->
        <div class="user-menu">
            <i class="fas fa-user-circle user-icon"></i> <!-- User Icon -->
            <div class="dropdown-content">
                <a href="profile.php"><i class="fas fa-user"></i> My Profile</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
