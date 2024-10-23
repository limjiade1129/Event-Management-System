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
$contact_us_count_query = "SELECT COUNT(*) AS total FROM contact_us";

$user_count = mysqli_fetch_assoc(mysqli_query($conn, $user_count_query))['total'];
$organizer_count = mysqli_fetch_assoc(mysqli_query($conn, $organizer_count_query))['total'];
$admin_count = mysqli_fetch_assoc(mysqli_query($conn, $admin_count_query))['total'];
$event_count = mysqli_fetch_assoc(mysqli_query($conn, $event_count_query))['total'];
$upcoming_event_count = mysqli_fetch_assoc(mysqli_query($conn, $upcoming_event_query))['total'];
$past_event_count = mysqli_fetch_assoc(mysqli_query($conn, $past_event_query))['total'];
$pending_event_count = mysqli_fetch_assoc(mysqli_query($conn, $pending_event_query))['total'];
$approved_event_count = mysqli_fetch_assoc(mysqli_query($conn, $approved_event_query))['total'];
$feedback_count = mysqli_fetch_assoc(mysqli_query($conn, $feedback_count_query))['total'];
$contact_us_count = mysqli_fetch_assoc(mysqli_query($conn, $contact_us_count_query))['total'];

// Fetch average rating
$average_rating_query = "SELECT AVG(rating) AS average_rating FROM feedback";
$average_rating = mysqli_fetch_assoc(mysqli_query($conn, $average_rating_query))['average_rating'];

// Fetch feedback distribution
$feedback_distribution_query = "SELECT rating, COUNT(*) AS total FROM feedback GROUP BY rating";
$feedback_distribution_result = mysqli_query($conn, $feedback_distribution_query);
$feedback_distribution = [];
while ($row = mysqli_fetch_assoc($feedback_distribution_result)) {
    $feedback_distribution[$row['rating']] = $row['total'];
}

// Fetch most active events (by feedback count)
$most_active_events_query = "SELECT e.event_name, COUNT(f.feedback_id) AS feedback_count 
                             FROM events e 
                             JOIN feedback f ON e.event_id = f.event_id 
                             GROUP BY e.event_name 
                             ORDER BY feedback_count DESC 
                             LIMIT 5";
$most_active_events_result = mysqli_query($conn, $most_active_events_query);
$most_active_events = [];
while ($row = mysqli_fetch_assoc($most_active_events_result)) {
    $most_active_events[] = ['event_name' => $row['event_name'], 'feedback_count' => $row['feedback_count']];
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

        .sidebar.collapsed + .main-content {
            margin-left: 60px;
            width: calc(100% - 60px);
        }

        .main-content h1 {
            font-size: 2.5em;
            margin-bottom: 30px;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .stat-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 2.5em;
            color: #007bff;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            margin: 10px 0;
            font-size: 1.4em;
            color: #333;
        }

        .stat-card p {
            font-size: 2em;
            color: #007bff;
            margin: 0;
        }

        .stat-card .sub-details {
            font-size: 0.9em;
            color: #666;
            margin-top: 10px;
        }

    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <h1>Welcome, Admin</h1>
    <div class="stats-container">
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
        <div class="stat-card">
            <i class="fas fa-calendar-check"></i>
            <h3>Total Events</h3>
            <p><?php echo $event_count; ?></p>
            <div class="sub-details">
                <span>Upcoming: <?php echo $upcoming_event_count; ?></span> | 
                <span>Past: <?php echo $past_event_count; ?></span>
            </div>
        </div>
        <div class="stat-card">
            <i class="fas fa-tasks"></i>
            <h3>Event Status</h3>
            <div class="sub-details">
                <span>Pending: <?php echo $pending_event_count; ?></span> | 
                <span>Approved: <?php echo $approved_event_count; ?></span>
            </div>
        </div>
        <div class="stat-card">
            <i class="fas fa-comments"></i>
            <h3>Total Feedback</h3>
            <p><?php echo $feedback_count; ?></p>
        </div>

        <!-- Average Rating Card -->
        <div class="stat-card">
            <i class="fas fa-star"></i>
            <h3>Average Rating</h3>
            <p><?php echo number_format($average_rating, 1); ?> / 5</p>
        </div>

        <!-- Contact Us Card -->
        <div class="stat-card">
            <i class="fas fa-envelope"></i>
            <h3>Total Contact Us</h3>
            <p><?php echo $contact_us_count; ?></p>
        </div>
        <!-- Feedback Distribution Chart -->
        <div class="stat-card">
            <canvas id="feedbackDistributionChart"></canvas>
        </div>
        <!-- Most Active Events Chart -->
        <div class="stat-card">
            <canvas id="mostActiveEventsChart"></canvas>
        </div>

    </div>
</div>

<script>
    const allRatings = [1, 2, 3, 4, 5];
    const feedbackDistribution = <?php echo json_encode($feedback_distribution); ?>;

    const feedbackData = allRatings.map(rating => feedbackDistribution[rating] || 0);

    // Feedback Distribution Data
    const feedbackDistributionData = {
        labels: allRatings.map(rating => `${rating}`), // Display as "Rating 1", "Rating 2", etc.
        datasets: [{
            label: 'Number of Feedbacks',
            data: feedbackData,
            backgroundColor: ['#3498db'],
        }]
    };

    // Render Feedback Distribution Chart
    const feedbackCtx = document.getElementById('feedbackDistributionChart').getContext('2d');
    new Chart(feedbackCtx, {
        type: 'bar',
        data: feedbackDistributionData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' },
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Ratings'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Number of Feedbacks'
                    },
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1 
                    }
                }
            }
        }
    });

     // Most Active Events Data
     const mostActiveEventsData = {
        labels: <?php echo json_encode(array_column($most_active_events, 'event_name')); ?>,
        datasets: [{
            label: 'Feedback Count',
            data: <?php echo json_encode(array_column($most_active_events, 'feedback_count')); ?>,
            backgroundColor: '#3498db',
        }]
    };
    // Render Most Active Events Chart
    const eventsCtx = document.getElementById('mostActiveEventsChart').getContext('2d');
    new Chart(eventsCtx, {
        type: 'bar',
        data: mostActiveEventsData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' },
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Event Name'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Number of Feedback'
                    },
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1 
                    }
                }
            }
        }
    });
</script>

</body>
</html>
