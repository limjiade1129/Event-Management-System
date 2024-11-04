<?php
include 'config.php'; // Include your database connection file


$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form inputs
    $username = $_POST['username'];
    $email = $_POST['email'];
    $subject = $_POST['title'];
    $message = $_POST['msg'];
    
    // Define the initial status as 'Unread'
    $status = "Unread";
    
    // Prepare the SQL statement
    $query = "INSERT INTO contact_us (user_id, username, email, subject, message, status, time_created) 
              VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    
    // Bind the parameters to the statement
    $stmt->bind_param("isssss", $user_id, $username, $email, $subject, $message, $status);
    
    // Execute the statement and check for errors
    if ($stmt->execute()) {
        // If the insertion was successful, redirect with a success message
        echo "<script>
                alert('Your message has been sent successfully. We will contact you soon!');
                window.location.href = 'aboutus.php';
              </script>";
    } else {
        // If there's an error, display a message
        echo "<script>
                alert('There was an error sending your message. Please try again.');
                window.location.href = 'aboutus.php';
              </script>";
    }
    
    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
