<?php
require 'config.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $result = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        // Generate a random strong password
        $newPassword = bin2hex(random_bytes(8)); 

        // Hash the new password
        $hashedPassword = md5($newPassword);

        // Update the password in the database
        mysqli_query($conn, "UPDATE user SET password='$hashedPassword' WHERE email='$email'");

        // Send the new password to the user's email
        $subject = "Your New Password";
        $message = "Your new password is: $newPassword\nPlease change it after logging in.";
        $headers = "From: no-reply@yourdomain.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('A new password has been sent to your email.');</script>";
        } else {
            echo "<script>alert('Failed to send the email. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css"> <!-- Add your custom CSS file -->
</head>
<body>
    <div class="forgot-password-form">
        <h1>Forgot Password</h1>
        <form action="" method="post">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" name="submit">Send New Password</button>
        </form>
    </div>
</body>
</html>
