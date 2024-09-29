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
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .input-container i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #888;
            pointer-events: none;
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
        <form action="handle_register.php" method="post" onsubmit="return validateForm()" autocomplete="off">
            <div class="input-container">
                <i class="fas fa-user"></i>
                <input type="text" name="username" id="username" placeholder="Username" required>
            </div>
            <div class="input-container">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
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
                <select name="role" id="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="User">User</option>
                    <option value="Organizer">Organizer</option>
                </select>
            </div>
            <button type="submit">Register</button>
        </form>

        <div class="links">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>

    <script>
        function validateForm() {
            // Get form field values
            var username = document.getElementById("username").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var confirmpassword = document.getElementById("confirmpassword").value;
            var telno = document.getElementById("telno").value;
            var role = document.getElementById("role").value;

            // Regular expressions for validation
            var usernameRegex = /^[a-zA-Z0-9]{3,15}$/; // Only alphanumeric, 3-15 characters
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Basic email format
            var passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/; // At least one uppercase, one lowercase, one number, one special char, and 8 characters
            var phoneRegex = /^[0-9]{10,12}$/; // Allows numbers between 10 and 12 digits

            // Validate Username
            if (!usernameRegex.test(username)) {
                alert("Username must be 3-15 characters long and contain only letters and numbers.");
                return false;
            }

            // Validate Email
            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            // Validate Password
            if (!passwordRegex.test(password)) {
                alert("Password must be at least 8 characters long, including an uppercase letter, a lowercase letter, a number, and a special character.");
                return false;
            }

            // Validate Confirm Password
            if (password !== confirmpassword) {
                alert("Passwords do not match.");
                return false;
            }

            // Validate Phone Number
            if (!phoneRegex.test(telno)) {
                alert("Please enter a valid phone number (10-12 digits).");
                return false;
            }

            // Validate Role selection
            if (role === "") {
                alert("Please select a role.");
                return false;
            }

            // If all validations pass
            return true;
        }
    </script>
</body>
</html>
