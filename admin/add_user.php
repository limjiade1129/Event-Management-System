<?php
require '../config.php'; // Include your database configuration

// Get the form data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$telno = $_POST['telno'];
$role = $_POST['role'];

// Check the database connection
if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
} else {
    // Check if the email already exists in the database
    $checkStmt = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Email already exists, display an error message
        $checkStmt->close();
        $conn->close();
        ?>
        <script>
            alert("Email already exists. Try adding a user with a different email!");
            window.location.href = "admin_manage_user.php"; 
        </script>
        <?php
        exit();
    } else {

        // Hash the password using md5 
        $hashedPassword = md5($password);

        // Email does not exist, proceed with adding the user
        $stmt = $conn->prepare("INSERT INTO user (username, email, password, telno, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $hashedPassword, $telno, $role);
        
        if ($stmt->execute()) {
            // User added successfully
            $stmt->close();
            $conn->close();
            ?>
            <script>
                alert("User added successfully!");
                window.location.href = "admin_manage_user.php"; // Redirect to manage users page
            </script>
            <?php
            exit();
        } else {
            // Error while adding the user
            $stmt->close();
            $conn->close();
            ?>
            <script>
                alert("Error occurred while adding the user!");
                window.location.href = "admin_manage_user.php"; // Redirect back to manage users page
            </script>
            <?php
            exit();
        }
    }
}
?>
