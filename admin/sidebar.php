<!-- sidebar.php -->
<?php
// Get the current file name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar" id="sidebar">
    <button id="sidebarToggle" class="sidebar-toggle"><i class="fas fa-bars"></i></button>
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
    <a href="logout.php" class="<?php echo ($current_page == 'logout.php') ? 'active' : ''; ?>">
        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
    </a>
</div>

<!-- Sidebar Toggle Button for Small Screens -->
<button id="sidebarOpen" class="sidebar-open hidden"><i class="fas fa-bars"></i></button>

<script>
    // Sidebar Toggle Functionality
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOpen = document.getElementById('sidebarOpen');
    const mainContent = document.querySelector('.main-content');

    sidebarToggle.addEventListener('click', function () {
        sidebar.classList.toggle('hidden');
        sidebarOpen.classList.toggle('hidden');
        adjustMainContent();
    });

    sidebarOpen.addEventListener('click', function () {
        sidebar.classList.remove('hidden');
        sidebarOpen.classList.add('hidden');
        adjustMainContent();
    });

    // Adjust the main content width based on the sidebar visibility
    function adjustMainContent() {
        if (sidebar.classList.contains('hidden')) {
            mainContent.style.marginLeft = '60px';
            mainContent.style.width = 'calc(100% - 60px)';
        } else {
            mainContent.style.marginLeft = '250px';
            mainContent.style.width = 'calc(100% - 250px)';
        }
    }

    // For larger screens, make sure the sidebar is always visible
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('hidden');
            sidebarOpen.classList.add('hidden');
            mainContent.style.marginLeft = '250px';
            mainContent.style.width = 'calc(100% - 250px)';
        } else {
            adjustMainContent();
        }
    });
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
        transition: transform 0.3s ease-in-out;
    }

    .sidebar.hidden {
        transform: translateX(-100%);
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

    .sidebar-toggle {
        background-color: transparent;
        border: none;
        color: #fff;
        font-size: 1.5em;
        cursor: pointer;
        margin-bottom: 20px;
    }

    /* Sidebar Open Button Styles */
    .sidebar-open {
        position: fixed;
        top: 20px;
        left: 20px;
        background-color: #007bff;
        border: none;
        color: #fff;
        font-size: 1.5em;
        cursor: pointer;
        border-radius: 4px;
        padding: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        z-index: 2;
    }

    .sidebar-open.hidden {
        display: none;
    }

    /* Media Query for Mobile Screens */
    @media (max-width: 768px) {
        .sidebar {
            position: absolute;
            width: 250px;
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }
    }
</style>
