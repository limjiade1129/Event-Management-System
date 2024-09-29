<?php
$title = "Event List"; 
include 'header.php'; 
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
        }
        .event-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 25px;
        }
        .event-card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        .event-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .event-details {
            padding: 20px;
        }
        .event-name {
            font-size: 1.4em;
            font-weight: bold;
            margin: 0 0 15px 0;
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
            line-height: 1.6;
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
            display: block;
            width: 100%;
            text-align: center;
        }
        .view-more:hover {
            background-color: #2980b9;
        }
        .view-more a {
            text-decoration: none;
            color: white;
        }
        .event-type {
            background-color: #333;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upcoming Events</h1>
        <div class="event-list">
            <!-- Event Card 1 -->
            <div class="event-card">
                <img src="img/background1.jpg" alt="Summer Music Festival" class="event-image">
                <div class="event-details">
                    <span class="event-type">Music</span>
                    <h2 class="event-name">Summer Music Festival</h2>
                    <p class="event-info"><i class="fas fa-map-marker-alt"></i> Central Park, New York</p>
                    <p class="event-info"><i class="far fa-calendar-alt"></i> July 15-17, 2024</p>
                    <p class="event-info"><i class="far fa-clock"></i> 12:00 PM - 11:00 PM</p>
                    <p class="event-description">Experience a three-day music extravaganza featuring top artists from around the world. Get ready for unforgettable performances and electric atmosphere!</p>
                    <p class="event-info"><i class="fas fa-users"></i> 5000 attendees</p>
                    <button class="view-more"><a href="event_details.php">View Details</a></button>
                </div>
            </div>

            <!-- Event Card 2 -->
            <div class="event-card">
                <img src="path_to_image2.jpg" alt="Tech Conference 2024" class="event-image">
                <div class="event-details">
                    <span class="event-type">Technology</span>
                    <h2 class="event-name">Tech Conference 2024</h2>
                    <p class="event-info"><i class="fas fa-users"></i> 2000 attendees</p>
                    <p class="event-info"><i class="fas fa-map-marker-alt"></i> Convention Center, San Francisco</p>
                    <p class="event-info"><i class="far fa-calendar-alt"></i> September 5-7, 2024</p>
                    <p class="event-info"><i class="far fa-clock"></i> 9:00 AM - 6:00 PM</p>
                    <p class="event-description">Dive into the future of technology at our annual conference. Network with industry leaders and discover groundbreaking innovations.</p>
                    <button class="view-more">View Details</button>
                </div>
            </div>

            <!-- Event Card 3 -->
            <div class="event-card">
                <img src="path_to_image3.jpg" alt="Food & Wine Expo" class="event-image">
                <div class="event-details">
                    <span class="event-type">Culinary</span>
                    <h2 class="event-name">Food & Wine Expo</h2>
                    <p class="event-info"><i class="fas fa-users"></i> 3000 attendees</p>
                    <p class="event-info"><i class="fas fa-map-marker-alt"></i> Exhibition Hall, London</p>
                    <p class="event-info"><i class="far fa-calendar-alt"></i> October 10-12, 2024</p>
                    <p class="event-info"><i class="far fa-clock"></i> 11:00 AM - 8:00 PM</p>
                    <p class="event-description">Embark on a culinary journey with exquisite dishes and fine wines from across the globe. Meet renowned chefs and sommeliers in this gastronomic adventure.</p>
                    <button class="view-more">View Details</button>
                </div>
            </div>

            <!-- Event Card 3 -->
            <div class="event-card">
                <img src="path_to_image3.jpg" alt="Food & Wine Expo" class="event-image">
                <div class="event-details">
                    <span class="event-type">Culinary</span>
                    <h2 class="event-name">Food & Wine Expo</h2>
                    <p class="event-info"><i class="fas fa-users"></i> 3000 attendees</p>
                    <p class="event-info"><i class="fas fa-map-marker-alt"></i> Exhibition Hall, London</p>
                    <p class="event-info"><i class="far fa-calendar-alt"></i> October 10-12, 2024</p>
                    <p class="event-info"><i class="far fa-clock"></i> 11:00 AM - 8:00 PM</p>
                    <p class="event-description">Embark on a culinary journey with exquisite dishes and fine wines from across the globe. Meet renowned chefs and sommeliers in this gastronomic adventure.</p>
                    <button class="view-more">View Details</button>
                </div>
            </div>

            <!-- Add more event cards as needed -->

        </div>
    </div>
</body>
</html>