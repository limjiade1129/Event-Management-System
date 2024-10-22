<?php
$title = "Homepage"; 
include 'header.php'; 

// Fetch the total number of events and upcoming events from the database
$event_count_query = "SELECT COUNT(*) AS total FROM events";
$upcoming_event_count_query = "SELECT COUNT(*) AS total FROM events WHERE date >= CURDATE() AND status != 'Pending'";

$event_count = mysqli_fetch_assoc(mysqli_query($conn, $event_count_query))['total'];
$upcoming_event_count = mysqli_fetch_assoc(mysqli_query($conn, $upcoming_event_count_query))['total'];

// Fetch upcoming events (example query to get top 3 upcoming events)
$upcoming_events_query = "SELECT event_id, event_name, date, location FROM events WHERE date >= CURDATE() AND status != 'Pending' ORDER BY date ASC LIMIT 3";
$upcoming_events_result = mysqli_query($conn, $upcoming_events_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System - Homepage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }
        .carousel-container {
            position: relative;
        }

        .carousel-inner img {
            height: 80vh;
            object-fit: cover;
        }

        .carousel-caption {
            bottom: 20%;
        }

        .hero-content h1 {
            font-size: 3em;
            margin-bottom: 0.5em;
        }

        .hero-content p {
            font-size: 1.2em;
            margin-bottom: 1.5em;
        }

        .hero-content .cta-button {
            background-color: #3498db;
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 1em;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .hero-content .cta-button:hover {
            background-color: #2980b9;
        }

        /* Features Section */
        .features {
            padding: 40px 20px;
            text-align: center;
        }

        .features h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .features .feature-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .feature-card {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-card i {
            font-size: 2em;
            color: #3498db;
            margin-bottom: 10px;
        }

        .feature-card h3 {
            font-size: 1.2em;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #666;
        }

        /* Statistics Section */
        .statistics {
            padding: 40px 20px;
            background-color: #fff;
            text-align: center;
        }

        .statistics h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .statistics .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stats-card {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stats-card h3 {
            font-size: 1.2em;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .stats-card p {
            font-size: 2em;
            color: #3498db;
            margin: 0;
        }

        /* Upcoming Section */
        .upcoming {
            padding: 40px 20px;
            background-color: #fff;
            text-align: center;
        }

        .upcoming h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .upcoming .up-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .up-card {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .up-card h3 {
            font-size: 1.2em;
            color: #2c3e50;
            margin-bottom: 10px;
            
        }

        .up-card p {
            font-size: 1.0em;
            color: #3498db;
            margin: 0;
        }
        .up-card .btn {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .up-card .btn:hover {
            background-color: #2980b9;
        }
        

        /* Testimonials Section */
        .testimonials {
            padding: 40px 20px;
            background-color: #f8f8f8;
            text-align: center;
        }

        .testimonials h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .testimonial-card {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }

        .about-us-section {
            padding: 60px 20px;
            background-color: #3498db;
            color: white;
            text-align: center;
        }

        .about-us-section h2 {
            font-size: 2.2em;
            margin-bottom: 20px;
        }

        .about-us-section p {
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        .about-us-section a {
            background-color: white;
            color: #3498db;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 1em;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .about-us-section a:hover {
            background-color: #2980b9;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Bootstrap Carousel -->
    <div class="carousel-container">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/slider1.jpg" class="d-block w-100" alt="Slide 1">
                    <div class="carousel-caption d-none d-md-block">
                        <div class="hero-content">
                            <h1>Unlock Your Potential</h1>
                            <p>Discover the path to success with our event management system.</p>
                            <a href="eventlist.php" class="cta-button">Explore Events</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/slider2.jpg" class="d-block w-100" alt="Slide 2">
                    <div class="carousel-caption d-none d-md-block">
                        <div class="hero-content">
                            <h1>Join the Community</h1>
                            <p>Connect, share, and grow with our event management platform.</p>
                            <a href="eventlist.php" class="cta-button">Join Now</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/slider3.jpg" class="d-block w-100" alt="Slide 3">
                    <div class="carousel-caption d-none d-md-block">
                        <div class="hero-content">
                            <h1>Inspiring Journeys</h1>
                            <p>Attend, organize, and enjoy events tailored to your interests.</p>
                            <a href="eventlist.php" class="cta-button">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features">
        <h2>Features</h2>
        <div class="feature-cards">
            <div class="feature-card">
                <i class="fas fa-calendar-alt"></i>
                <h3>Event Scheduling</h3>
                <p>Plan and schedule events with ease, managing every detail.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-users"></i>
                <h3>Registration Management</h3>
                <p>Keep track of event registrations and manage attendee lists effortlessly.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-chart-line"></i>
                <h3>Event Analytics</h3>
                <p>Monitor event performance with detailed analytics and reports.</p>
            </div>
        </div>
    </div>

    <!-- Event Statistics Section -->
    <div class="statistics">
        <h2>Our Events</h2>
        <div class="stats-cards">
            <div class="stats-card">
                <h3>Total Events Created</h3>
                <p><?php echo $event_count; ?></p>
            </div>
            <div class="stats-card">
                <h3>Upcoming Events</h3>
                <p><?php echo $upcoming_event_count; ?></p>
            </div>
        </div>
    </div>

    <!-- Upcoming Events Section -->
    <div class="upcoming">
        <h2>Upcoming Events</h2>
        <div class="up-cards">
            <?php while ($event = mysqli_fetch_assoc($upcoming_events_result)): ?>
                <div class="up-card">
                    <h3><?php echo $event['event_name']; ?></h3>
                    <p><?php echo date("F j, Y", strtotime($event['date'])); ?></p>
                    <p><?php echo $event['location']; ?></p>
                    <a href="event_details.php?id=<?php echo $event['event_id']; ?>" class="btn btn-primary mt-3">View Details</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>



    <!-- Testimonials Section -->
    <div class="testimonials">
        <h2>What Our Users Say</h2>
        <div class="testimonial-card">
            <p>"This platform has made organizing events so much easier! Highly recommend."</p>
            <small>- Sarah J.</small>
        </div>
        <div class="testimonial-card">
            <p>"I love how easy it is to find and register for events that match my interests."</p>
            <small>- Michael T.</small>
        </div>
    </div>

    <!-- About Us Section -->
    <div class="about-us-section">
        <h2>Learn More About Us</h2>
        <p>Find out more about our mission, values, and the team behind this platform.</p>
        <a href="aboutus.php">About Us</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
