<?php
require 'config.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $result = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        // Generate a random strong password
        $newPassword = bin2hex(random_bytes(4)); 

        // Hash the new password
        $hashedPassword = md5($newPassword);

        // Update the hashed password in the database
        mysqli_query($conn, "UPDATE user SET password='$hashedPassword' WHERE email='$email'");

        // Send the new password to the user's email
        $subject = "Your New Password";
        $message = "Your new password is: $newPassword\nPlease change it after logging in.";
        $headers = "From: no-reply@yourdomain.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('A new password has been sent to your email.'); window.location.href='login.php';</script>";
        } else {
            // if fail it will show the new password in the alert box.
            echo "<script>alert('Failed to send the email. Your new password is: $newPassword. Please log in and change it immediately.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please try again.'); window.location.href='forgot_password.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .forgot-password-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .forgot-password-container h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .forgot-password-container input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 1.2rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            outline: none;
            font-size: 16px;
        }

        .submit-button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 15px;
            cursor: pointer;
            margin-bottom: 1rem;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }

        .back-button {
            display: inline-block;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
            margin-top: 1rem;
        }

        .back-button:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>
    <div class="forgot-password-container">
        <h1>Forgot Password</h1>
        <form action="" method="post">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" name="submit" class="submit-button">Send New Password</button>
            <a href="login.php" class="back-button">Back to Login</a>
        </form>
    </div>
</body>
</html>
