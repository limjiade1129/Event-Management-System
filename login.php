<?php
require 'config.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to select user details
    $result = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        if ($password == $row["password"]) { // Compare the passwords
            $_SESSION["login"] = true;
            $_SESSION["userid"] = $row["userid"];
            $_SESSION["role"] = $row["role"]; 

            // Redirect based on role
            if ($row["role"] == 'user') {
                header('Location: homepage.php'); // Redirect to user homepage
            } elseif ($row["role"] == 'organizer') {
                header('Location: homepage.php'); // Redirect to organizer homepage
            }

            exit();
        } else {
            echo "<script> alert('Invalid Password! Please Try Again!'); </script>";
        }
    } else {
        echo "<script> alert('Invalid Email! Please Try Again!'); </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('img/background1.jpg') no-repeat center center fixed;
            background-size: cover; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
            margin: 0;
            flex-direction: column; /* Align items in column to show the title above */
        }
        .title {
            margin-bottom: 30px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-decoration: none;
        }
        .login-form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .login-form h1 {
            margin-bottom: 10px;
        }
        .input-container {
            position: relative;
            margin: 10px 0;
        }
        .input-container input {
            width: 100%; /* Make input width 100% of container */
            padding: 10px 10px 10px 35px; /* Adjust padding for left icon */
            padding-right: 40px; /* Extra padding for the icon on the right */
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box; /* Include padding and border in element's total width and height */
        }
        .input-container i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        .input-container .fa-envelope {
            left: 10px; 
        }
        .input-container .fa-lock {
            left: 10px; 
        }
        .input-container .toggle-password {
            right: 10px; 
            cursor: pointer;
        }
        .login-form button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            margin-top: 10px;
        }
        .login-form button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .links {
            margin-top: 20px;
            font-size: 14px;
        }
        .links a {
            color: #007bff;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .welcome-message {
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>
    <!-- Added Title -->
    <div class="title">
        <h1>iEvent - Event Management System</h1>
    </div>
    
    <div class="login-form">
        <h1>Login</h1>
        <p class="welcome-message">Welcome back to EventGo!</p>
        <form action="" method="post">
            <div class="input-container">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="" placeholder="Email" required>
            </div>
            <div class="input-container">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i> <!-- Show password icon -->
            </div>
            <button type="submit" name="submit">Login</button>
        </form>

        <?php if (isset($error)) { echo '<p class="error">' . $error . '</p>'; } ?>

        <div class="links">
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
            <p><a href="forgot_password.php">Forgot Password?</a></p>
        </div>
    </div>

    <!-- JavaScript for toggling password visibility -->
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
