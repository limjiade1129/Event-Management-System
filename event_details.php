<?php
$title = "Event Details";
include 'header.php';

// Assuming event_id is passed via URL
$event_id = $_GET['id'];
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session when the user logs in

// Determine where the user came from (either event list or event history)
$from = isset($_GET['from']) ? $_GET['from'] : 'eventlist'; // Default to event list if not provided

// Fetch event details based on event_id
$query = "SELECT * FROM events WHERE event_id = $event_id";
$result = mysqli_query($conn, $query);
$event = mysqli_fetch_assoc($result);

// Check if the user is already registered for the event
$check_query = "SELECT * FROM event_registrations WHERE event_id = $event_id AND user_id = $user_id";
$check_result = mysqli_query($conn, $check_query);
$is_registered = mysqli_num_rows($check_result) > 0;

// If the user confirms registration and there are slots available
if (isset($_GET['register']) && !$is_registered && $event['slots'] > 0) {
    // Register the user for the event
    $insert_query = "INSERT INTO event_registrations (event_id, user_id) VALUES ('$event_id', '$user_id')";
    
    if (mysqli_query($conn, $insert_query)) {
        // Decrease the slots by 1
        $update_slots_query = "UPDATE events SET slots = slots - 1 WHERE event_id = $event_id AND slots > 0";
        mysqli_query($conn, $update_slots_query);

        // Redirect and alert success
        echo "<script>alert('Successfully registered for the event!'); window.location.href = 'eventlist.php';</script>";
    } else {
        echo "<script>alert('Error registering for the event. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            background-image: url('uploads/<?php echo $event['image']; ?>');
            background-size: cover;
            background-position: center;
            height: 400px;
            border-radius: 20px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .event-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 10px;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 40px;
            color: white;
        }

        .event-title {
            font-size: 3em;
            margin: 0;
        }

        .event-info {
            background-color: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-top: -50px;
            position: relative;
        }

        .event-type {
            background-color: #2ecc71;
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9em;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 15px;
            letter-spacing: 1px;
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

        .back-button, .register-button, .disabled-button {
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

        .register-button {
            background-color: #e74c3c;
            color: white;
        }

        .register-button:hover {
            background-color: #c0392b;
        }

        .disabled-button {
            background-color: #95a5a6;
            color: white;
            cursor: not-allowed;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .button-group {
                flex-direction: column;
                align-items: center;
            }

            .back-button, .register-button, .disabled-button {
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
            }
        }
    </style>


</head>
<body>
    <div class="container">
        <div class="event-header">
            <div class="event-overlay">
                <h1 class="event-title"><?php echo $event['event_name']; ?></h1>
            </div>
        </div>

        <div class="event-info">
            <span class="event-type"><?php echo $event['event_type']; ?></span>
            <div class="info-grid">
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

        </div>
        <div class="button-group">
                <a href="<?php echo ($from === 'eventhistory') ? 'event_history.php' : 'eventlist.php'; ?>" class="back-button">Back to <?php echo ($from === 'eventhistory') ? 'History' : 'Events'; ?></a>
                
                <!-- If the user is already registered, show a disabled button with same style -->
                <?php if ($is_registered): ?>
                    <span class="register-button disabled-button">You have Already Registered!</span>
                <!-- If slots are 0, show a disabled button -->
                <?php elseif ($event['slots'] <= 0): ?>
                    <span class="register-button disabled-button">No More Slots Available</span>
                <?php else: ?>
                    <a href="javascript:void(0);" onclick="confirmRegistration()" class="register-button">Register Now</a>
                <?php endif; ?>
            </div>
    </div>
</body>
</html>

<script>
    function confirmRegistration() {
        if (confirm("Are you sure you want to register for this event?")) {
            window.location.href = 'event_details.php?id=<?php echo $event_id; ?>&register=true&from=<?php echo $from; ?>';
        }
    }
</script>

<?php
mysqli_close($conn);
?>
