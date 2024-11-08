<?php
$title = "Event View";
include '../config.php';

// Assuming event_id is passed via URL
$event_id = $_GET['id'];
$organizer_id = $_SESSION['user_id']; 

// Fetch event details including organizer information
$query = "SELECT e.*, u.username AS organizer_name, u.email AS organizer_email 
          FROM events e
          LEFT JOIN user u ON e.created_by = u.user_id
          WHERE e.event_id = $event_id";
$result = mysqli_query($conn, $query);
$event = mysqli_fetch_assoc($result);

// Fetch total number of registrations
$registration_count_query = "SELECT COUNT(*) AS total FROM event_registrations WHERE event_id = $event_id";
$registration_count_result = mysqli_query($conn, $registration_count_query);
$registration_total_rows = mysqli_fetch_assoc($registration_count_result)['total'];

// Fetch total number of feedbacks
$feedback_count_query = "SELECT COUNT(*) AS total FROM feedback WHERE event_id = $event_id";
$feedback_count_result = mysqli_query($conn, $feedback_count_query);
$feedback_total_rows = mysqli_fetch_assoc($feedback_count_result)['total'];

$feedback_query = "SELECT rating FROM feedback WHERE event_id = $event_id";
$feedback_result = mysqli_query($conn, $feedback_query);

// Prepare feedback rating data
$feedback_counts = array_fill(1, 5, 0);
$total_rating = 0;
$total_feedbacks = 0;

while ($feedback = mysqli_fetch_assoc($feedback_result)) {
    $rating = $feedback['rating'];
    $feedback_counts[$rating]++;
    $total_rating += $rating;
    $total_feedbacks++;
}

$average_rating = $total_feedbacks > 0 ? $total_rating / $total_feedbacks : 0;

// Fetch all registrations
$registrations_query = "SELECT event_registrations.registration_id, event_registrations.user_id, user.username, user.email, event_registrations.registration_date 
                        FROM event_registrations 
                        LEFT JOIN user ON event_registrations.user_id = user.user_id 
                        WHERE event_registrations.event_id = $event_id";
$registrations_result = mysqli_query($conn, $registrations_query);

// Fetch all feedback
$feedback_query = "SELECT feedback.feedback_id, feedback.user_id, user.username, user.email, feedback.feedback, feedback.rating, feedback.time_created 
                   FROM feedback 
                   LEFT JOIN user ON feedback.user_id = user.user_id 
                   WHERE feedback.event_id = $event_id";
$feedback_result = mysqli_query($conn, $feedback_query);

// Set feedback preview length
$feedback_preview_length = 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event View</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .event-header {
            position: relative;
            height: 400px;
            border-radius: 20px;
            overflow: hidden; 
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .event-image {
            width: 100%;
            height: 100%;
            object-fit: cover no-repeat; 
        }

        .event-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 40px;
            color: white;
        }

        .event-title {
            font-size: 3em;
            font-weight: bold;
        }

        .event-info {
            background-color: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-top: -50px;
            position: relative;
        }

        .event-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .event-type {
            background-color: #2ecc71;
            color: white;
            padding: 5px 16px;
            border-radius: 50px;
            text-align: center;
            font-size: 0.9em;
            font-weight: bold;
        }

        .event-status {
            display: inline-block;
            padding: 5px 15px;
            font-size: 0.9em;
            font-weight: bold;
            text-align: center;
            border-radius: 20px;
            margin-left: auto; 
            color: white;
        }

        .status-approved {
            background: linear-gradient(45deg, #2ecc71, #27ae60);
        }

        .status-pending {
            background: linear-gradient(45deg, #f39c12, #e67e22);
        }

        .status-rejected {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-item {
            display: flex;
            align-items: center;
        }

        .info-item i {
            font-size: 1.5em;
            margin-right: 15px;
            color: #3498db;
        }

        .event-description {
            margin-top: 30px;
            color: #666;
        }

        .event-description h2 {
            margin-bottom: 20px;
            color: #4a4e69;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .back-button, .disabled-button {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
            transition: background-color 0.3s ease;
        }

        .back-button {
            background-color: #3498db;
            color: white;
        }

        .back-button:hover {
            background-color: #2980b9;
        }

        .disabled-button {
            background-color: #95a5a6;
            color: white;
            cursor: not-allowed;
        }

        .registrations, .feedback-section {
            background-color: #fff;
            border-radius: 12px;
            padding: 15px;
            margin-top: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 1.8em;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .registrations table, .feedback-section table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .registrations th, .feedback-section th, .registrations td, .feedback-section td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 0.9em;
            text-align: center;
        }

        .registrations th, .feedback-section th {
            background-color: #3498db;
            color: white;
            font-size: 1em;
        }

        .registrations tr:hover, .feedback-section tr:hover {
            background-color: #ddd;
        }

        .full-feedback {
            display: none;
        }
        .delete-button {
            background-color: #e74c3c;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }
        .chart-container {
            margin-top: 30px;
            display: flex;
            justify-content: space-around;
            gap: 20px;
        }

        .chart-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 30%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .average-rating-card {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .average-rating-card h3 {
            font-size: 1.4em;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .average-rating-card p {
            font-size: 1.8em;
            font-weight: bold;
            color: #3498db;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="event-header">
            <img src="../uploads/<?php echo $event['image']; ?>" alt="<?php echo $event['event_name']; ?>" class="event-image">
            <div class="event-overlay">
                <h1 class="event-title"><?php echo $event['event_name']; ?></h1>
            </div>
        </div>

        <div class="event-info">
            <div class="event-header-row">
                <span class="event-type"><?php echo $event['event_type']; ?></span>
                <span class="event-status <?php echo 'status-' . strtolower($event['status']); ?>">
                    Status : <?php echo $event['status']; ?>
                </span>
            </div>

            <div class="info-grid">
                <!-- Event details like location, date, time, slots -->
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo $event['location']; ?></span>
                </div>

                <div class="info-item">
                    <i class="far fa-calendar-alt"></i>
                    <span><?php echo date("F j, Y", strtotime($event['date'])); ?></span>
                </div>

                <div class="info-item">
                    <i class="far fa-clock"></i>
                    <span><?php echo date("g:i A", strtotime($event['start_time'])); ?> - <?php echo date("g:i A", strtotime($event['end_time'])); ?></span>
                </div>

                <div class="info-item">
                    <i class="fas fa-users"></i>
                    <span>Slots left: <?php echo $event['slots']; ?></span>
                </div>
            </div>

            <div class="event-description">
                <h2>About the Event</h2>
                <p><?php echo $event['description']; ?></p>
            </div>

            <!-- Organizer Information Section -->
            <div class="organizer-info">
                <h2>Organizer Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <i class="fas fa-user"></i>
                        <span>Name : <?php echo $event['organizer_name']? $event['organizer_name'] : '<span style="color: red;">Deleted User</span>'; ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <span>Email : <?php echo $event['organizer_email']? $event['organizer_email'] : '<span style="color: red;">Deleted User</span>'; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="chart-container">
            <div class="chart-card">
                <canvas id="registrationFeedbackChart"></canvas>
            </div>
            <div class="chart-card">
                <canvas id="feedbackRatingChart"></canvas>
            </div>
            <div class="chart-card average-rating-card">
                <h3>Average Rating</h3>
                <p><?php echo number_format($average_rating, 1); ?> / 5</p>
            </div>
        </div>

        <!-- Registered Users Section -->
        <div class="registrations" id="registrations">
            <h2>Registered Users (Total: <?php echo $registration_total_rows; ?>)</h2>
            <?php if (mysqli_num_rows($registrations_result) > 0): ?>
            <table>
                <tr>
                    <th>No.</th>
                    <th>Registration ID</th>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Registration Date</th>
                    <th>Actions</th>
                </tr>
                <?php $num = 1; ?>
                <?php while ($registration = mysqli_fetch_assoc($registrations_result)): ?>
                <tr>
                    <td><?php echo $num++; ?></td>
                    <td><?php echo $registration['registration_id']; ?></td>
                    <td><?php echo $registration['user_id'] ? $registration['user_id'] : '<span style="color: red;">Deleted User</span>'; ?></td>
                    <td><?php echo $registration['username'] ? $registration['username'] : '<span style="color: red;">Deleted User</span>'; ?></td>
                    <td><?php echo $registration['email'] ? $registration['email'] : 'N/A'; ?></td>
                    <td><?php echo $registration['registration_date']; ?></td>
                    <td>
                        <a href="../delete_action.php?action=delete_registration&event_id=<?php echo $event_id; ?>&user_id=<?php echo $registration['user_id']; ?>" 
                        onclick="return confirm('Are you sure you want to delete this registration?');" class="delete-button">
                        Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

            <?php else: ?>
            <p>No users have registered for this event yet.</p>
            <?php endif; ?>
        </div>

        <!-- Feedback Section -->
        <div class="feedback-section" id="feedback">
            <h2>Feedback (Total: <?php echo $feedback_total_rows; ?>)</h2>
            <?php if (mysqli_num_rows($feedback_result) > 0): ?>
            <table>
                <tr>
                    <th>No.</th>
                    <th>Feedback ID</th>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Feedback</th>
                    <th>Rating</th>
                    <th>Time Created</th>
                    <th>Actions</th>
                </tr>
                <?php $num = 1; ?>
                <?php while ($feedback = mysqli_fetch_assoc($feedback_result)): ?>
                <tr>
                    <td><?php echo $num++; ?></td>
                    <td><?php echo $feedback['feedback_id']; ?></td>
                    <td><?php echo $feedback['user_id']; ?></td>
                    <td><?php echo $feedback['username'] ? $feedback['username'] : '<span style="color: red;">Deleted User</span>'; ?></td>
                    <td><?php echo $feedback['email'] ? $feedback['email'] : '<span style="color: red;">Deleted User</span>'; ?></td>
                    <td>
                        <?php
                        if (strlen($feedback['feedback']) > $feedback_preview_length) {
                            $short_feedback = substr($feedback['feedback'], 0, $feedback_preview_length);
                            $full_feedback = $feedback['feedback'];
                            echo '<span class="short-feedback">' . $short_feedback . '...</span>';
                            echo '<span class="full-feedback" style="display:none;">' . $full_feedback . '</span>';
                            echo '<a href="javascript:void(0)" class="toggle-feedback" onclick="toggleFeedback(this)">Read More</a>';
                        } else {
                            echo $feedback['feedback'];
                        }
                        ?>
                    </td>
                    <td><?php echo $feedback['rating']; ?>/5</td>
                    <td><?php echo $feedback['time_created']; ?></td>
                    <td>
                        <a href="../delete_action.php?action=delete_feedback&event_id=<?php echo $event_id; ?>&user_id=<?php echo $feedback['user_id']; ?>" 
                        onclick="return confirm('Are you sure you want to delete this feedback?');" class="delete-button">
                        Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

            <?php else: ?>
            <p>No feedback has been given for this event yet.</p>
            <?php endif; ?>
        </div>

        <div class="button-group">
            <a href="admin_manage_event.php" class="back-button">Back to My Events</a>
        </div>
    </div>

    <script>
        // Chart for Registration and Feedback Counts
        var ctx1 = document.getElementById('registrationFeedbackChart').getContext('2d');
        var registrationFeedbackChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Registrations', 'Feedbacks'],
                datasets: [{
                    label: 'Count',
                    data: [<?php echo $registration_total_rows; ?>, <?php echo $feedback_total_rows; ?>],
                    backgroundColor: ['#3498db', '#e74c3c']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Event Engagement Metrics' }
                },
                scales: { y: { beginAtZero: true, stepSize: 1 } }
            }
        });

        // Chart for Feedback Rating Distribution
        var ctx2 = document.getElementById('feedbackRatingChart').getContext('2d');
        var feedbackRatingChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Rating 1', 'Rating 2', 'Rating 3', 'Rating 4', 'Rating 5'],
                datasets: [{
                    data: [<?php echo implode(",", $feedback_counts); ?>],
                    backgroundColor: ['#e74c3c', '#f39c12', '#f1c40f', '#2ecc71', '#3498db'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' },
                    title: { display: true, text: 'Feedback Rating Distribution' }
                }
            }
        });

        // Function to delete a registration
        function deleteRegistration(userId, eventId) {
            if (confirm("Are you sure you want to delete this registration?")) {
                // Send a request to delete_action.php to delete the registration
                window.location.href = 'delete_action.php?action=delete_registration&event_id=' + eventId + '&user_id=' + userId;
            }
        }

        // Function to delete feedback
        function deleteFeedback(userId, eventId) {
            if (confirm("Are you sure you want to delete this feedback?")) {
                // Send a request to delete_action.php to delete the feedback
                window.location.href = 'delete_action.php?action=delete_feedback&event_id=' + eventId + '&user_id=' + userId;
            }
        }
        
        function toggleFeedback(element) {
            const shortFeedback = element.previousElementSibling.previousElementSibling;
            const fullFeedback = element.previousElementSibling;
            
            if (fullFeedback.style.display === "none" || fullFeedback.style.display === "") {
                fullFeedback.style.display = "inline";
                shortFeedback.style.display = "none";
                element.innerText = " Read Less";
            } else {
                fullFeedback.style.display = "none";
                shortFeedback.style.display = "inline";
                element.innerText = " Read More";
            }
        }

        document.querySelectorAll('.full-feedback').forEach(item => item.style.display = 'none');
    </script>
</body>
</html>

<?php
$conn->close();
?>
