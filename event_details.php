<?php
$title = "Event Details";
include 'header.php';
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
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .event-header {
            position: relative;
            height: 500px;
            background-image: url('img/background1.jpg');
            background-size: cover;
            background-position: center;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .event-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.7));
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 40px;
            color: white;
        }

        .event-title {
            font-size: 3em;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .event-type {
            background-color: #ff6b6b;
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9em;
            display: inline-block;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .event-info {
            background-color: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-top: -50px;
            position: relative;
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
            color: #4a4e69;
        }

        .event-description {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 30px;
        }

        .event-description h2 {
            color: #4a4e69;
            margin-bottom: 20px;
        }

        .back-button {
            display: inline-block;
            background-color: #4a4e69;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 50px;
            margin-top: 30px;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .back-button:hover {
            background-color: #22223b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="event-header">
            <div class="event-overlay">
                <h1 class="event-title">Summer Music Festival</h1>
                <span class="event-type">Music</span>
            </div>
        </div>

        <div class="event-info">
            <div class="info-grid">
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Central Park, New York</span>
                </div>
                
                <div class="info-item">
                    <i class="far fa-calendar-alt"></i>
                    <span>July 15-17, 2024</span>
                </div>

                <div class="info-item">
                    <i class="far fa-clock"></i>
                    <span>12:00 PM - 11:00 PM</span>
                </div>

                <div class="info-item">
                    <i class="fas fa-users"></i>
                    <span>5000 attendees</span>
                </div>
            </div>

            <div class="event-description">
                <h2>About the Event</h2>
                <p>Experience a three-day music extravaganza featuring top artists from around the world. Get ready for unforgettable performances and an electric atmosphere!</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula. Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor.</p>
            </div>
        </div>

        <a href="eventlist.php" class="back-button">Back to Events</a>
    </div>
</body>
</html>