<footer style="background-color: #3498db; color: white; text-align: center; margin-top: 20px; font-size: 0.9em;">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-md-4 mb-3">
                <h5>About Us</h5>
                <p>We are a platform dedicated to managing and organizing events with ease. Connect, share, and enjoy events tailored to your interests.</p>
                <a href="aboutus.php" class="btn btn-light footer-btn">Learn More</a>
            </div>

            <!-- Quick Links Section -->
            <div class="col-md-4 mb-3">
                <h5>Quick Links</h5>
                <ul style="list-style: none; padding: 0;">
                    <li><a href="homepage.php" class="footer-link">Home</a></li>
                    <li><a href="eventlist.php" class="footer-link">Event List</a></li>
                    <li><a href="event_history.php" class="footer-link">Event History</a></li>
                    <li><a href="aboutus.php" class="footer-link">About Us</a></li>
                    <li><a href="profile.php" class="footer-link">My Profile</a></li>
                </ul>
            </div>

            <!-- Contact Us Section -->
            <div class="col-md-4 mb-3">
                <h5>Contact Us</h5>
                <p>Email: eventgo@gmail.com</p>
                <p>Phone: 016-1234567</p>
                <a href="aboutus.php#contact" class="btn btn-light footer-btn">Contact Us</a>
            </div>
        </div>
        <hr style="border-color: rgba(255, 255, 255, 0.3); margin: 5px 0;">
        <p>&copy; <?php echo date("Y"); ?> Event Management System. All Rights Reserved.</p>
    </div>
</footer>

<!-- Additional CSS -->
<style>
    /* Button styling */
    .footer-btn {
        background-color: white;
        color: #3498db;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    }
    
    /* Button hover effect */
    .footer-btn:hover {
        background-color: #2980b9;
        color: white;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Link styling */
    .footer-link {
        color: white;
        text-decoration: none;
        display: block;
        margin: 5px 0;
        transition: color 0.3s ease;
    }

    /* Link hover effect */
    .footer-link:hover {
        color: #dcdcdc;
        text-decoration: underline;
    }
</style>
