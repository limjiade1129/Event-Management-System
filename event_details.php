<?php
$title = "Event Details";
include 'header.php';

// Assuming event_id is passed via URL
$event_id = $_GET['id'];
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session when the user logs in

// Determine where the user came from (either event list or event history)
$from = isset($_GET['from']) ? $_GET['from'] : 'eventlist'; // Default to event list if not provided

// Fetch event details and organizer information based on event_id
$query = "SELECT e.*, u.username AS organizer_name, u.email AS organizer_email 
          FROM events e
          LEFT JOIN user u ON e.created_by = u.user_id
          WHERE e.event_id = $event_id";
$result = mysqli_query($conn, $query);
$event = mysqli_fetch_assoc($result);

// Check if the user is already registered for the event
$check_query = "SELECT * FROM event_registrations WHERE event_id = $event_id AND user_id = $user_id";
$check_result = mysqli_query($conn, $check_query);
$is_registered = mysqli_num_rows($check_result) > 0;

// If the user confirms registration and there are slots available
if (isset($_GET['register'])) {
    // Register the user for the event
    $insert_query = "INSERT INTO event_registrations (event_id, user_id) VALUES ('$event_id', '$user_id')";
    
    if (mysqli_query($conn, $insert_query)) {
        // Decrease the slots by 1
        $update_slots_query = "UPDATE events SET slots = slots - 1 WHERE event_id = $event_id AND slots > 0";
        mysqli_query($conn, $update_slots_query);

        // Redirect and alert success
        echo "<script>alert('Successfully registered for the event !'); window.location.href = 'eventlist.php';</script>";
    } else {
        echo "<script>alert('Error registering for the event. Please try again.'); window.location.href = 'eventlist.php';</script>";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body,html {
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

        .organizer-info {
            margin-top: 40px;
            color: #666;
        }

        .organizer-info h2 {
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
            background-color: #3498db !important;
            color: white !important;
        }

        .back-button:hover {
            background-color: #2980b9 !important;
        }

        .register-button {
            background-color: #e74c3c !important;
            color: white !important;
        }

        .register-button:hover {
            background-color: #c0392b !important;
        }

        .disabled-button {
            background-color: #95a5a6 !important;
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
            <img src="uploads/<?php echo $event['image']; ?>" alt="<?php echo $event['event_name']; ?>" class="event-image">
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

        <div class="button-group">
            <a href="javascript:void(0);" onclick="goBack();" class="back-button">Back</a>
   
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
            window.location.href = 'event_details.php?id=<?php echo $event_id; ?>&register=true; ?>';
        }
    }
    function goBack() {
        window.history.back(); 
    }
</script>

<?php
include "footer.php";
mysqli_close($conn);
?>
