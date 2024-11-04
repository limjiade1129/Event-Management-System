<!-- sidebar.php -->
<?php

// Redirect to login if not logged in
if (!isset($_SESSION["login"])) {
    header("Location: ../login.php");
    exit();
}

// Get the current file name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar" id="sidebar">
    <h2>Admin Dashboard</h2>
    <a href="admin_dashboard.php" class="<?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>">
        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
    </a>
    <a href="admin_manage_user.php" class="<?php echo ($current_page == 'admin_manage_user.php') ? 'active' : ''; ?>">
        <i class="fas fa-users"></i> <span>Manage Users</span>
    </a>
    <a href="admin_manage_event.php" class="<?php echo ($current_page == 'admin_manage_event.php') ? 'active' : ''; ?>">
        <i class="fas fa-calendar-alt"></i> <span>Manage Events</span>
    </a>
    <a href="admin_feedback.php" class="<?php echo ($current_page == 'admin_feedback.php') ? 'active' : ''; ?>">
        <i class="fas fa-comments"></i> <span>Feedback</span>
    </a>
    <a href="admin_contactus.php" class="<?php echo ($current_page == 'admin_contactus.php') ? 'active' : ''; ?>">
        <i class="fas fa-envelope"></i> <span>Contact Us</span>
    </a>
    <a href="../logout.php" onclick="return confirmLogout();" class="<?php echo ($current_page == 'logout.php') ? 'active' : ''; ?>">
        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
    </a>
</div>

<script>
    function confirmLogout() {
        return confirm("Are you sure you want to log out?");
    }
</script>

<style>
    /* Sidebar Styles */
    .sidebar {
        width: 260px;
        background-color: #343a40;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        z-index: 1;
        transition: none; /* Remove animation */
    }

    .sidebar h2 {
        color: #fff;
        text-align: center;
        margin-bottom: 30px;
        font-size: 1.5em;
    }

    .sidebar a {
        text-decoration: none;
        color: #ddd;
        display: flex;
        align-items: center;
        padding: 10px 20px;
        margin-bottom: 10px;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .sidebar a i {
        margin-right: 10px;
    }

    .sidebar a:hover {
        background-color: #495057;
    }

    .sidebar a.active {
        background-color: #007bff;
        color: #fff;
    }

    /* Adjust main content to account for sidebar */
    .main-content {
        margin-left: 260px;
        padding: 20px;
    }

    /* Media Query for Mobile Screens */
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            width: 100%;
            height: auto;
            box-shadow: none;
        }

        .main-content {
            margin-left: 0;
        }
    }
</style>
