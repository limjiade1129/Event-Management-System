<?php
$title = "My Events";
include 'header.php';

// Get the organizer's user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch events created by this organizer
$query = "SELECT * FROM events 
          WHERE created_by = ? 
          ORDER BY time_created DESC";
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
        }
        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 30px;
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
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        .event-image {
            width: 100%;
            height: 200px;
            object-fit: cover no-repeat;
            border-radius: 12px 12px 0 0; 
        }
        .event-details {
            padding: 20px;
            display: flex;
            flex-direction: column;
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
            color: #34495e;
        }
        .event-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 0.9em;
            color: #555;
        }
        .event-info i {
            margin-right: 8px;
            color: #3498db;
            width: 20px;
            text-align: center;
        }
        .event-description {
            font-size: 0.95em;
            color: #666;
            margin: 15px 0;
            line-height: 1.4;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .view-more {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-top: auto;
            text-align: center;
        }
        .view-more:hover {
            background-color: #2980b9;
        }
        .view-more a {
            text-decoration: none;
            color: white;
        }
        .event-status {
            display: inline-block;
            padding: 5px 15px;
            font-size: 0.9em;
            font-weight: bold;
            text-align: center;
            border-radius: 20px;
            margin-bottom: 10px;
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
        .event-actions {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding-top: 10px;
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
                            <div class="event-header">
                                <span class="event-type"><?php echo $event['event_type']; ?></span>
                                <span class="event-slots">Slots left: <?php echo $event['slots']; ?></span>
                            </div>
                            <h2 class="event-name"><?php echo $event['event_name']; ?></h2>

                            <div class="event-status <?php echo 'status-' . strtolower($event['status']); ?>">
                                Status: <?php echo ($event['status']); ?>
                            </div>

                            <p class="event-info"><i class="fas fa-map-marker-alt"></i> <?php echo $event['location']; ?></p>
                            <p class="event-info"><i class="far fa-calendar-alt"></i> <?php echo date("j F Y", strtotime($event['date'])); ?></p>
                            <p class="event-info"><i class="far fa-clock "></i> <?php echo date("g:i A", strtotime($event['start_time'])); ?> - <?php echo date("g:i A", strtotime($event['end_time'])); ?></p>
                            <p class="event-description"><?php echo $event['description']; ?></p>

                            <button class="view-more">
                                <a href="my_event_view.php?id=<?php echo $event['event_id']; ?>">View Event</a>
                            </button>
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
