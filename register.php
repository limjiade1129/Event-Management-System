<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        }
        .register-form {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .register-form h1 {
            margin-bottom: 10px;
        }
        .input-container {
            position: relative;
            margin: 10px 0;
        }
        .input-container input, .input-container select {
            width: 100%; /* Make input width 100% of container */
            padding: 10px 10px 10px 35px; /* Adjust padding for left icon */
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box; /* Include padding and border in element's total width and height */
        }
        .input-container i {
            position: absolute;
            top: 50%;
            left: 10px; /* Position icons to the left */
            transform: translateY(-50%);
            color: #888;
        }
        .register-form button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            margin-top: 10px;
        }
        .register-form button:hover {
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
    <div class="register-form">
        <h1>Register</h1>
        <p class="welcome-message">Join us at EventGo!</p>
        <form action="handle_register.php" method="post">
            <div class="input-container">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-container">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-container">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <div class="input-container">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password" required>
            </div>
            <div class="input-container">
                <i class="fas fa-phone"></i>
                <input type="text" name="telno" id="telno" placeholder="Phone Number" required>
            </div>
            <div class="input-container">
                <i class="fas fa-user-tag"></i>
                <select name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="user">User</option>
                    <option value="organizer">Organizer</option>
                </select>
            </div>
            <button type="submit">Register</button>
        </form>

        <?php if (isset($error)) { echo '<p class="error">' . $error . '</p>'; } ?>

        <div class="links">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
