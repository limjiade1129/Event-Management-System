<?php
require "config.php";

// Redirect to login if not logged in
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit();
}

// Get the user role from the session
$role = $_SESSION["role"];
$user_id = $_SESSION["user_id"];

// Get the current page's filename
$current_page = basename($_SERVER['PHP_SELF']);
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
            background-color: #f5f5f5;
        }

        .header {
            background-color: #fff;
            padding: 15px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            color: #007bff;
            text-decoration: none;
        }

        .navbar {
            display: flex;
            gap: 40px;
            align-items: center;
        }

        .navbar a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar a:hover,
        .navbar a.active {
            color: #007bff;

        }

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
            background-color: #f1f1f1;
            min-width: 170px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            z-index: 1000;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .dropdown-content a {
            color: #333;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown-content.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <a href="homepage.php" class="logo">EventGo</a>

        <!-- Navbar -->
        <div class="navbar">
            <a href="homepage.php" class="<?php echo ($current_page == 'homepage.php') ? 'active' : ''; ?>">Home</a>
            <a href="eventlist.php" class="<?php echo ($current_page == 'eventlist.php') ? 'active' : ''; ?>">Event List</a>
            <a href="event_history.php" class="<?php echo ($current_page == 'event_history.php') ? 'active' : ''; ?>">Event History</a>

            <?php if ($role === 'Organizer') : ?>
                <a href="my_event.php" class="<?php echo ($current_page == 'my_event.php') ? 'active' : ''; ?>">My Event</a>
            <?php endif; ?>

            <a href="aboutus.php" class="<?php echo ($current_page == 'aboutus.php') ? 'active' : ''; ?>">About Us</a>
        </div>

        <!-- Dropdown Menu -->
        <div class="user-menu">
            <i class="fas fa-user-circle user-icon" onclick="toggleDropdown()"></i>
            <div class="dropdown-content" id="userDropdown">
                <a href="profile.php"><i class="fas fa-user"></i> My Profile</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            var dropdown = document.getElementById("userDropdown");
            dropdown.classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.user-icon')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>

</body>
</html>
