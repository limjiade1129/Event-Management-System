<?php
$title = "Admin Dashboard";
include '../config.php';

$user_id = $_SESSION['user_id'];
// Fetch statistics from the database
$user_count_query = "SELECT COUNT(*) AS total FROM user WHERE role = 'User'";
$organizer_count_query = "SELECT COUNT(*) AS total FROM user WHERE role = 'Organizer'";
$admin_count_query = "SELECT COUNT(*) AS total FROM user WHERE role = 'Admin'";
$event_count_query = "SELECT COUNT(*) AS total FROM events";
$upcoming_event_query = "SELECT COUNT(*) AS total FROM events WHERE date >= CURDATE()";
$past_event_query = "SELECT COUNT(*) AS total FROM events WHERE date < CURDATE()";
$pending_event_query = "SELECT COUNT(*) AS total FROM events WHERE status = 'Pending'";
$approved_event_query = "SELECT COUNT(*) AS total FROM events WHERE status = 'Approved'";
$feedback_count_query = "SELECT COUNT(*) AS total FROM feedback";
$contact_total_query = "SELECT COUNT(*) AS total FROM contact_us";
$contact_unread_query = "SELECT COUNT(*) AS unread FROM contact_us WHERE status = 'Unread'";

$user_count = mysqli_fetch_assoc(mysqli_query($conn, $user_count_query))['total'];
$organizer_count = mysqli_fetch_assoc(mysqli_query($conn, $organizer_count_query))['total'];
$admin_count = mysqli_fetch_assoc(mysqli_query($conn, $admin_count_query))['total'];
$event_count = mysqli_fetch_assoc(mysqli_query($conn, $event_count_query))['total'];
$upcoming_event_count = mysqli_fetch_assoc(mysqli_query($conn, $upcoming_event_query))['total'];
$past_event_count = mysqli_fetch_assoc(mysqli_query($conn, $past_event_query))['total'];
$pending_event_count = mysqli_fetch_assoc(mysqli_query($conn, $pending_event_query))['total'];
$approved_event_count = mysqli_fetch_assoc(mysqli_query($conn, $approved_event_query))['total'];
$feedback_count = mysqli_fetch_assoc(mysqli_query($conn, $feedback_count_query))['total'];
$contact_total = mysqli_fetch_assoc(mysqli_query($conn, $contact_total_query))['total'];
$contact_unread = mysqli_fetch_assoc(mysqli_query($conn, $contact_unread_query))['unread'];

// Fetch total feedback by months
$total_feedback_by_month_query = "
    SELECT DATE_FORMAT(time_created, '%Y-%m') AS month, COUNT(*) AS total
    FROM feedback
    GROUP BY month
    ORDER BY month ASC";
$total_feedback_by_month_result = mysqli_query($conn, $total_feedback_by_month_query);
$total_feedback_by_month = [];
while ($row = mysqli_fetch_assoc($total_feedback_by_month_result)) {
    $total_feedback_by_month[$row['month']] = $row['total'];
}

// Fetch number of users registered for events by months
$users_registered_by_month_query = "
    SELECT DATE_FORMAT(registration_date, '%Y-%m') AS month, COUNT(*) AS total
    FROM event_registrations
    GROUP BY month
    ORDER BY month ASC";
$users_registered_by_month_result = mysqli_query($conn, $users_registered_by_month_query);
$users_registered_by_month = [];
while ($row = mysqli_fetch_assoc($users_registered_by_month_result)) {
    $users_registered_by_month[$row['month']] = $row['total'];
}

// Calculate engagement rate
$engagement_rate = [];
foreach ($total_feedback_by_month as $month => $feedback_count) {
    $user_count = isset($users_registered_by_month[$month]) ? $users_registered_by_month[$month] : 0;
    $rate = $user_count > 0 ? ($feedback_count / $user_count) * 100 : 0;
    $engagement_rate[$month] = $rate;
}

// Fetch average rating by months
$average_rating_by_month_query = "
    SELECT DATE_FORMAT(time_created, '%Y-%m') AS month, AVG(rating) AS average_rating
    FROM feedback
    GROUP BY month
    ORDER BY month ASC";
$average_rating_by_month_result = mysqli_query($conn, $average_rating_by_month_query);
$average_rating_by_month = [];
while ($row = mysqli_fetch_assoc($average_rating_by_month_result)) {
    $average_rating_by_month[$row['month']] = $row['average_rating'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
        }
        /* Main Content Styles */
        .main-content {
            margin-left: 260px;
            padding: 20px;
            width: calc(100% - 250px);
            transition: margin-left 0.3s, width 0.3s;
        }

        .main-content h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .stat-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .stat-card i {
            font-size: 2em;
            color: #007bff;
            margin-bottom: 8px;
        }

        .stat-card h3 {
            margin: 8px 0;
            font-size: 1.2em;
            color: #333;
        }

        .stat-card p {
            font-size: 1.8em;
            color: #007bff;
            margin: 0;
        }

        .stat-card .sub-details {
            font-size: 0.8em;
            color: #666;
            margin-top: 8px;
        }

        /* Chart Layout */
        .chart-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        .chart-left,
        .chart-right{
            height: 200px;  
        }

        
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <h1>Welcome, Admin</h1>
    <!-- Statistics Card -->
    <div class="stats-container">
        <!-- Total User Card -->
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3>Total Users</h3>
            <p><?php echo $user_count + $organizer_count + $admin_count; ?></p>
            <div class="sub-details">
                <span>Users: <?php echo $user_count; ?></span> | 
                <span>Organizers: <?php echo $organizer_count; ?></span> |
                <span>Admin: <?php echo $admin_count; ?></span>
            </div>
        </div>
        <!-- Event Card -->
        <div class="stat-card">
            <i class="fas fa-calendar-check"></i>
            <h3>Total Events</h3>
            <p><?php echo $event_count; ?></p>
            <div class="sub-details">
                <span>Upcoming: <?php echo $upcoming_event_count; ?></span> | 
                <span>Past: <?php echo $past_event_count; ?></span>
            </div>
        </div>
        <!-- Event Status Card -->
        <div class="stat-card">
            <i class="fas fa-tasks"></i>
            <h3>Event Status</h3>
            <div class="sub-details">
                <span>Pending: <?php echo $pending_event_count; ?></span> | 
                <span>Approved: <?php echo $approved_event_count; ?></span>
            </div>
        </div>
        <!-- Contact Us Card -->
        <div class="stat-card">
            <i class="fas fa-envelope"></i>
            <h3>Contact Us</h3>
            <p><?php echo $contact_total; ?></p>
            <div class="sub-details">
                <span>Unread: <?php echo $contact_unread; ?></span>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="chart-container">
        <!-- User registered Chart -->
        <div class="stat-card chart-left">
            <canvas id="usersRegisteredChart"></canvas>
        </div>
        <!-- Total Feedback Chart -->
        <div class="stat-card chart-right">
            <canvas id="totalFeedbackChart"></canvas>
        </div>
        <!-- Engagement Rate Chart -->
        <div class="stat-card chart-left">
            <canvas id="engagementRateChart"></canvas>
        </div>
        <!-- Average Rating Chart -->
        <div class="stat-card chart-right">
            <canvas id="averageRatingChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Graph 1: Users Registered for Events by Month
    const usersRegisteredData = {
        labels: <?php echo json_encode(array_keys($users_registered_by_month)); ?>,
        datasets: [{
            label: 'Users Registered',
            data: <?php echo json_encode(array_values($users_registered_by_month)); ?>,
            backgroundColor: '#2ecc71'
        }]
    };
    const usersRegisteredCtx = document.getElementById('usersRegisteredChart').getContext('2d');
    new Chart(usersRegisteredCtx, {
        type: 'bar',
        data: usersRegisteredData,
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Month' } },
                y: { title: { display: true, text: 'Users Registered' }, beginAtZero: true }
            }
        }
    });

    // Graph 2: Total Feedback by Month
    const totalFeedbackData = {
        labels: <?php echo json_encode(array_keys($total_feedback_by_month)); ?>,
        datasets: [{
            label: 'Total Feedback',
            data: <?php echo json_encode(array_values($total_feedback_by_month)); ?>,
            backgroundColor: '#3498db'
        }]
    };
    const totalFeedbackCtx = document.getElementById('totalFeedbackChart').getContext('2d');
    new Chart(totalFeedbackCtx, {
        type: 'bar',
        data: totalFeedbackData,
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Month' } },
                y: { title: { display: true, text: 'Total Feedback' }, beginAtZero: true }
            }
        }
    });

    // Graph 3: Engagement Rate by Month
    const engagementRateData = {
        labels: <?php echo json_encode(array_keys($engagement_rate)); ?>,
        datasets: [{
            label: 'Engagement Rate (%)',
            data: <?php echo json_encode(array_values($engagement_rate)); ?>,
            borderColor: '#e74c3c',
            fill: false
        }]
    };
    const engagementRateCtx = document.getElementById('engagementRateChart').getContext('2d');
    new Chart(engagementRateCtx, {
        type: 'line',
        data: engagementRateData,
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Month' } },
                y: { title: { display: true, text: 'Engagement Rate (%)' }, beginAtZero: true }
            }
        }
    });

    // Graph 4: Average Rating by Month
    const averageRatingData = {
        labels: <?php echo json_encode(array_keys($average_rating_by_month)); ?>,
        datasets: [{
            label: 'Average Rating',
            data: <?php echo json_encode(array_values($average_rating_by_month)); ?>,
            borderColor: '#f39c12',
            backgroundColor: '#f39c12',
            fill: false
        }]
    };
    const averageRatingCtx = document.getElementById('averageRatingChart').getContext('2d');
    new Chart(averageRatingCtx, {
        type: 'line',
        data: averageRatingData,
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Month' } },
                y: { title: { display: true, text: 'Average Rating' }, beginAtZero: true, max: 5 }
            }
        }
    });
</script>

</body>
</html>
