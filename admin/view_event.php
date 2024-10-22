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
$registration_query = "SELECT * FROM event_registrations WHERE event_id = ?";
$registration_stmt = $conn->prepare($registration_query);
$registration_stmt->bind_param("i", $event_id);
$registration_stmt->execute();
$registration_result = $registration_stmt->get_result();
$registered_users = $registration_result->num_rows;

// Fetch feedback
$feedback_query = "SELECT * FROM feedback WHERE event_id = ?";
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>View Event</h1>
    <h2><?php echo $event['event_name']; ?></h2>
    <p><strong>Type:</strong> <?php echo $event['event_type']; ?></p>
    <p><strong>Date:</strong> <?php echo date("j F Y", strtotime($event['date'])); ?></p>
    <p><strong>Location:</strong> <?php echo $event['location']; ?></p>
    <p><strong>Description:</strong> <?php echo $event['description']; ?></p>
    <p><strong>Slots:</strong> <?php echo $event['slots']; ?></p>
    <p><strong>Status:</strong> <?php echo $event['status']; ?></p>

    <h3>Registered Users: <?php echo $registered_users; ?></h3>
    <h3>Total Feedbacks: <?php echo $total_feedbacks; ?></h3>
    <h3>Average Rating: <?php echo number_format($average_rating, 1); ?> / 5</h3>

    <canvas id="registrationFeedbackChart" width="400" height="200"></canvas>
    <canvas id="feedbackRatingChart" width="400" height="200"></canvas>

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
                }
            }
        });

        // Pie Chart for Feedback Ratings
        var ctx2 = document.getElementById('feedbackRatingChart').getContext('2d');
        var feedbackRatingChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                datasets: [{
                    label: 'Rating Distribution',
                    data: [<?php echo implode(",", $feedback_counts); ?>],
                    backgroundColor: ['#e74c3c', '#f39c12', '#f1c40f', '#2ecc71', '#3498db'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>
</html>
