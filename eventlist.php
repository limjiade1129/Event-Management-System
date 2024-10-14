<?php
$title = "Event List"; 
include 'header.php';

// Fetch upcoming events sorted by date in ascending order
$query = "SELECT * FROM events 
          WHERE date >= CURDATE() 
          AND status = 'approved' 
          ORDER BY date ASC, start_time ASC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event List</title>
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
            justify-content: space-between; /* Make sure the button is always at the bottom */
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
        }
        .event-details {
            padding: 20px;
            flex-grow: 1; /* Ensure event details take up the remaining space */
            display: flex;
            flex-direction: column;
        }
        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
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
            flex-grow: 1; /* Make the description expand to take the available space */
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Limit the text to 3 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
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
        .view-more {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: auto; /* Push the button to the bottom */
            text-align: center;
        }
        .view-more:hover {
            background-color: #2980b9;
        }
        .view-more a {
            text-decoration: none;
            color: white;
        }
        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .search-input {
            width: 100%;
            max-width: 500px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
            font-size: 1rem;
            outline: none;
        }
        .search-button {
            padding: 10px 20px;
            border: none;
            background-color: #3498db;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 0 4px 4px 0;
            transition: background-color 0.3s ease;
        }
        .search-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upcoming Events</h1>
        <div class="search-bar">
            <input type="text" class="search-input" placeholder="Search for events...">
            <button class="search-button">Search</button>
        </div>
        <div class="event-list">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($event = mysqli_fetch_assoc($result)): ?>
                    <div class="event-card">
                        <img src="uploads/<?php echo $event['image']; ?>" alt="<?php echo $event['event_name']; ?>" class="event-image">
                        <div class="event-details">
                            <div class="event-header">
                                <span class="event-type"><?php echo $event['event_type']; ?></span>
                                <span class="event-slots">Slots left: <?php echo $event['slots']; ?></span> <!-- Slots section aligned right -->
                            </div>
                            <h2 class="event-name"><?php echo $event['event_name']; ?></h2>
                            <p class="event-info"><i class="fas fa-map-marker-alt"></i> <?php echo $event['location']; ?></p>
                            <p class="event-info"><i class="far fa-calendar-alt"></i> <?php echo date("j F, Y", strtotime($event['date'])); ?></p>
                            <p class="event-info"><i class="far fa-clock"></i> <?php echo date("g:i A", strtotime($event['start_time'])); ?> - <?php echo date("g:i A", strtotime($event['end_time'])); ?></p>
                            <p class="event-description"><?php echo $event['description']; ?></p>
                            <button class="view-more">
                                 <a href="event_details.php?id=<?php echo $event['event_id']; ?>&from=eventlist">View Details</a>
                            </button>

                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No upcoming events found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
