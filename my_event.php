<?php
$title = "My Events";
include 'header.php';

// Get the organizer's user ID from the session
$organizer_id = $_SESSION['user_id'];

// Fetch events created by this organizer
$query = "SELECT * FROM events 
          WHERE created_by = ? 
          ORDER BY date ASC, start_time ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Events</title>
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

        .create-event-button {
            display: block;
            text-align: center;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            margin-bottom: 30px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .create-event-button:hover {
            background-color: #2980b9;
        }

        .event-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .event-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .event-image {
            width: 100%;
            height: 200px;
            object-fit: cover no-repeat;
        }

        .event-details {
            padding: 20px;
            flex-grow: 1;
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
        }

        .event-actions {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 15px;
        }

        .edit-button, .delete-button {
            flex: 1;
            text-align: center;
            background-color: #3498db;
            color: white;
            padding: 10px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .edit-button:hover {
            background-color: #2980b9;
        }

        .delete-button {
            background-color: #e74c3c;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

        .no-events-message {
            text-align: center;
            font-size: 1.2em;
            margin-top: 20px;
            color: #555;
        }

        @media (max-width: 600px) {
            .event-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Events</h1>
        <a href="create_event.php" class="create-event-button">Create New Event</a>
        <div class="event-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($event = $result->fetch_assoc()): ?>
                    <div class="event-card">
                        <img src="uploads/<?php echo $event['image']; ?>" alt="<?php echo $event['event_name']; ?>" class="event-image">
                        <div class="event-details">
                            <span class="event-type"><?php echo $event['event_type']; ?></span>
                            <h2 class="event-name"><?php echo $event['event_name']; ?></h2>
                            <p class="event-info"><i class="fas fa-map-marker-alt"></i> <?php echo $event['location']; ?></p>
                            <p class="event-info"><i class="far fa-calendar-alt"></i> <?php echo date("F j, Y", strtotime($event['date'])); ?></p>
                            <p class="event-info"><i class="far fa-clock"></i> <?php echo date("g:i A", strtotime($event['start_time'])); ?> - <?php echo date("g:i A", strtotime($event['end_time'])); ?></p>
                            <p class="event-description"><?php echo $event['description']; ?></p>
                            <div class="event-actions">
                                <a href="edit_event.php?id=<?php echo $event['event_id']; ?>" class="edit-button">Edit</a>
                                <a href="delete_event.php?id=<?php echo $event['event_id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-events-message">No events found. <a href="create_event.php">Create a new event</a>.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
