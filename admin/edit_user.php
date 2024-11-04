<?php
require '../config.php'; 

// Get the form data
$user_id = $_POST['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$telno = $_POST['telno'];
$role = $_POST['role'];
$password = $_POST['password']; 

// Check the database connection
if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
} else {
    // Check if the email already exists in the database for another user
    $checkStmt = $conn->prepare("SELECT email FROM user WHERE email = ? AND user_id != ?");
    $checkStmt->bind_param("si", $email, $user_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Email already exists for another user, display an error message
        $checkStmt->close();
        $conn->close();
        ?>
        <script>
            alert("Email already exists. Try using a different email!");
            window.location.href = "admin_manage_user.php"; 
        </script>
        <?php
        exit();
    } else {
        // Prepare the SQL statement based on whether a new password is provided
        if (!empty($password)) {
            // Hash the new password and update it along with other details
            $hashed_password = md5($password);
            $stmt = $conn->prepare("UPDATE user SET username = ?, email = ?, telno = ?, role = ?, password = ? WHERE user_id = ?");
            $stmt->bind_param("sssssi", $username, $email, $telno, $role, $hashed_password, $user_id);
        } else {
            // Update other details without changing the password
            $stmt = $conn->prepare("UPDATE user SET username = ?, email = ?, telno = ?, role = ? WHERE user_id = ?");
            $stmt->bind_param("ssssi", $username, $email, $telno, $role, $user_id);
        }

        // Execute the update query
        if ($stmt->execute()) {
            // User updated successfully
            $stmt->close();
            $conn->close();
            ?>
            <script>
                alert("User updated successfully!");
                window.location.href = "admin_manage_user.php"; 
            </script>
            <?php
            exit();
        } else {
            // Error while updating the user
            $stmt->close();
            $conn->close();
            ?>
            <script>
                alert("Error occurred while updating the user!");
                window.location.href = "admin_manage_user.php"; 
            </script>
            <?php
            exit();
        }
    }
}
?>
