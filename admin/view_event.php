<?php
$title = "View Event";
include '../config.php';

$event_id = $_GET['id'];
$query = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

// Fetch registered users
$registration_query = "SELECT r.*, u.username, u.email FROM event_registrations r 
                       JOIN user u ON r.user_id = u.user_id 
                       WHERE r.event_id = ?";
$registration_stmt = $conn->prepare($registration_query);
$registration_stmt->bind_param("i", $event_id);
$registration_stmt->execute();
$registration_result = $registration_stmt->get_result();
$registered_users = $registration_result->num_rows;

// Fetch feedback
$feedback_query = "SELECT f.*, u.username FROM feedback f 
                   JOIN user u ON f.user_id = u.user_id 
                   WHERE f.event_id = ?";
$feedback_stmt = $conn->prepare($feedback_query);
$feedback_stmt->bind_param("i", $event_id);
$feedback_stmt->execute();
$feedback_result = $feedback_stmt->get_result();

// Prepare feedback chart data
$feedback_counts = array_fill(1, 5, 0);
$total_rating = 0;
$total_feedbacks = 0;

while ($feedback = $feedback_result->fetch_assoc()) {
    $rating = $feedback['rating'];
    $feedback_counts[$rating]++;
    $total_rating += $rating;
    $total_feedbacks++;
}

$average_rating = $total_feedbacks > 0 ? $total_rating / $total_feedbacks : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
        }
        .container {
            margin-top: 20px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h1, h3 {
            color: #3498db;
        }
        .table {
            margin-top: 20px;
            background-color: #ffffff;
            border-collapse: collapse;
            width: 100%;
        }
        .table th {
            background-color: #3498db;
            color: white;
        }
        .table, .table th, .table td {
            border: 1px solid #ddd;
            text-align: center;
        }
        .table tr:hover {
            background-color: #f2f2f2;
        }
        .chart-container {
            margin-top: 30px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .piechart {
            max-width: 800px;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Event Details</h1>
        <h2><?php echo $event['event_name']; ?></h2>
        <p><strong>Type:</strong> <?php echo $event['event_type']; ?></p>
        <p><strong>Date:</strong> <?php echo date("j F Y", strtotime($event['date'])); ?></p>
        <p><strong>Location:</strong> <?php echo $event['location']; ?></p>
        <p><strong>Description:</strong> <?php echo $event['description']; ?></p>
        <p><strong>Slots:</strong> <?php echo $event['slots']; ?></p>
        <p><strong>Status:</strong> <?php echo $event['status']; ?></p>
        <p><strong>Created By:</strong> <?php echo $event['created_by']; ?></p>
        <p><strong>Time Created:</strong> <?php echo date("j F Y, g:i A", strtotime($event['time_created'])); ?></p>

        <h3>Registered Users: <?php echo $registered_users; ?></h3>
        <h3>Total Feedbacks: <?php echo $total_feedbacks; ?></h3>
        <h3>Average Rating: <?php echo number_format($average_rating, 1); ?> / 5</h3>

        <!-- Registered Users Table -->
        <h3>Registered Users</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Registration Time</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($registered_users > 0): 
                    $no = 1;
                    while ($registration = $registration_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $registration['username']; ?></td>
                        <td><?php echo $registration['email']; ?></td>
                        <td><?php echo date("j F Y, g:i A", strtotime($registration['registration_date'])); ?></td>
                    </tr>
                <?php endwhile; 
                else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No registered users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Feedback Table -->
        <h3>Feedback</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Rating</th>
                    <th>Feedback</th>
                    <th>Time Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($total_feedbacks > 0): 
                    $no = 1;
                    foreach ($feedback_result as $feedback): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $feedback['username']; ?></td>
                        <td><?php echo $feedback['rating']; ?> / 5</td>
                        <td><?php echo $feedback['feedback']; ?></td>
                        <td><?php echo date("j F Y, g:i A", strtotime($feedback['time_created'])); ?></td>
                    </tr>
                <?php endforeach;
                else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No feedback found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Charts -->
        <div class="chart-container">
            <canvas id="registrationFeedbackChart" class="chart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="feedbackRatingChart" class="piechart"></canvas>
        </div>
    </div>

    <script>
        // Bar Chart for Registration and Feedback
        var ctx1 = document.getElementById('registrationFeedbackChart').getContext('2d');
        var registrationFeedbackChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Registrations', 'Feedbacks'],
                datasets: [{
                    label: 'Count',
                    data: [<?php echo $registered_users; ?>, <?php echo $total_feedbacks; ?>],
                    backgroundColor: ['#3498db', '#e74c3c'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });

        // Prepare the feedback counts array with a default of zero values if there is no data
var feedbackData = [<?php echo implode(",", $feedback_counts); ?>];
var hasFeedbackData = feedbackData.some(value => value > 0);

if (!hasFeedbackData) {
    // If there's no feedback data, add a dummy value to display a slice
    feedbackData = [0, 0, 0, 0, 0,1]; // Setting one slice to indicate no data
    var feedbackChartOptions = {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function() {
                        return "No feedback data available";
                    }
                }
            }
        }
    };
} else {
    var feedbackChartOptions = {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            }
        }
    };
}

// Pie Chart for Feedback Ratings
var ctx2 = document.getElementById('feedbackRatingChart').getContext('2d');
var feedbackRatingChart = new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
        datasets: [{
            label: 'Rating Distribution',
            data: feedbackData,
            backgroundColor: ['#e74c3c', '#f39c12', '#f1c40f', '#2ecc71', '#3498db'],
            hoverOffset: 4
        }]
    },
    options: feedbackChartOptions
});

    </script>
</body>
</html>
