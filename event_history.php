<?php
$title = "Event History";
include 'header.php';

// Get the user's user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch events the user has registered for
$query = "SELECT e.* FROM events e
          JOIN event_registrations er ON e.event_id = er.event_id
          WHERE er.user_id = ? 
          ORDER BY e.date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
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
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .event-list {
            display: grid;
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .event-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 20px;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .event-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 12px;
            margin-right: 20px;
        }

        .event-details {
            flex-grow: 1;
        }

        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .event-type {
            background-color: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .event-slots {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .event-name {
            font-size: 1.4em;
            font-weight: bold;
            margin: 0 0 10px 0;
            color: #2c3e50;
        }

        .event-info {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 0.9em;
            color: #555;
        }

        .event-info i {
            margin-right: 6px;
            color: #3498db;
            width: 18px;
            text-align: center;
        }

        .event-description {
            font-size: 0.95em;
            color: #666;
            margin: 10px 0;
            line-height: 1.4;
            max-height: 45px;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-grow: 1;
        }

        .event-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .action-button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .action-button:hover {
            background-color: #2980b9;
        }

        .feedback-button {
            background-color: #e67e22;
        }

        .feedback-button:hover {
            background-color: #d35400;
        }

        .no-events-message {
            text-align: center;
            font-size: 1.2em;
            margin-top: 20px;
            color: #555;
        }

        @media (max-width: 768px) {
            .event-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .event-image {
                margin-bottom: 15px;
            }

            .event-actions {
                align-items: center;
            }

            .action-button {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Event History</h1>
        <div class="event-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($event = $result->fetch_assoc()): ?>
                    <div class="event-card">
                        <img src="uploads/<?php echo $event['image']; ?>" alt="<?php echo $event['event_name']; ?>" class="event-image">
                        <div class="event-details">
                            <div class="event-header">
                                <span class="event-type"><?php echo $event['event_type']; ?></span>
                                <span class="event-slots">Slots left: <?php echo $event['slots']; ?></span>
                            </div>
                            <h2 class="event-name"><?php echo $event['event_name']; ?></h2>
                            <p class="event-info"><i class="fas fa-map-marker-alt"></i> <?php echo $event['location']; ?></p>
                            <p class="event-info"><i class="far fa-calendar-alt"></i> <?php echo date("F j, Y", strtotime($event['date'])); ?></p>
                            <p class="event-info"><i class="far fa-clock"></i> <?php echo date("g:i A", strtotime($event['start_time'])); ?> - <?php echo date("g:i A", strtotime($event['end_time'])); ?></p>
                            <p class="event-description"><?php echo $event['description']; ?></p>
                        </div>
                        <div class="event-actions">
                            <a href="event_details.php?id=<?php echo $event['event_id']; ?>&from=eventhistory" class="action-button">View Details</a>
                            <a href="feedback.php?id=<?php echo $event['event_id']; ?>" class="action-button feedback-button">Feedback</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-events-message">You have not registered for any events yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
