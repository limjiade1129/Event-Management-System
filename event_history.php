<?php
$title = "Event History";
include 'header.php';

// Get the user's user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch events the user has registered for along with feedback status and registration date
$query = "SELECT e.*, er.registration_date,
          (SELECT COUNT(*) FROM feedback f WHERE f.event_id = e.event_id AND f.user_id = ?) as feedback_submitted 
          FROM events e
          JOIN event_registrations er ON e.event_id = er.event_id
          WHERE er.user_id = ? 
          ORDER BY er.registration_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body ,html{
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;

        }

        .container {
            flex: 1; 
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 0.9em;
            text-align: center;
        }

        table th {
            background-color: #3498db;
            color: white;
            font-size: 1em;
        }

        table tr:hover {
            background-color: #f2f2f2;
        }

        .action-button {
            background-color: #3498db !important;
            color: white !important;
            padding: 10px 11px;
            border-radius: 15px;
            font-size: 0.9em;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 5px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .action-button:hover {
            background-color: #2980b9 !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .feedback-button {
            background-color: #e67e22 !important;
            color: white !important;
        }

        .feedback-button:hover {
            background-color: #d35400 !important;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .delete-button {
            background-color: #e74c3c !important;
            color: white !important;
        }

        .delete-button:hover {
            background-color: #c0392b !important;
        }

        .feedback-disabled {
            background-color: #bdc3c7 !important;
            cursor: not-allowed;
            box-shadow: none;
        }

        .feedback-disabled:hover {
            background-color: #bdc3c7 !important;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .no-events-message {
            text-align: center;
            font-size: 1.2em;
            margin-top: 20px;
            color: #555;
        }

        .table-actions {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Event History</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Event Name</th>
                        <th>Event Type</th>
                        <th>Event Date</th>
                        <th>Event Time</th>
                        <th>Location</th>
                        <th>Status</th> 
                        <th>Registration Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1;
                    $today = date("Y-m-d"); // Get today's date in YYYY-MM-DD format

                    while ($event = $result->fetch_assoc()): 
                        $event_date = $event['date'];
                        $feedback_submitted = $event['feedback_submitted']; // 0 if not submitted, 1 if submitted

                        // Determine if the event is upcoming or passed
                        $event_status = ($event_date > $today) ? "Upcoming" : "Passed";
                    ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo $event['event_name']; ?></td>
                            <td><?php echo $event['event_type']; ?></td>
                            <td><?php echo date("j F Y", strtotime($event['date'])); ?></td>
                            <td><?php echo date("g:i A", strtotime($event['start_time'])); ?> - <?php echo date("g:i A", strtotime($event['end_time'])); ?></td>
                            <td><?php echo $event['location']; ?></td>
                            <td><?php echo $event_status; ?></td>
                            <td><?php echo $event['time_created']; ?></td>
                            <td class="table-actions">
                                <div class="action-buttons">
                                    <a href="event_details.php?id=<?php echo $event['event_id']; ?>" class="action-button">View Details</a>

                                    <?php if ($feedback_submitted > 0): ?>
                                        <button class="action-button feedback-button feedback-disabled" disabled>Feedback Submitted</button>
                                    <?php elseif ($event_date > $today): ?>
                                        <button class="action-button feedback-button feedback-disabled" disabled>Feedback Unavailable</button>
                                    <?php else: ?>
                                        <a href="feedback.php?id=<?php echo $event['event_id']; ?>" class="action-button feedback-button">Give Feedback</a>
                                    <?php endif; ?>

                                    <?php if ($event_date >= $today): ?>
                                        <!-- Delete button for future events -->
                                        <a href="delete_registration.php?id=<?php echo $event['event_id']; ?>" 
                                        class="action-button delete-button"
                                        onclick="return confirmDelete()">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-events-message">You have not registered for any events yet.</p>
        <?php endif; ?>
    </div>

<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this registration?");
    }
</script>

</body>
</html>

<?php
include 'footer.php';
$stmt->close();
$conn->close();
?>