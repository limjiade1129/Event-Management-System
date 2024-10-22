<?php
require '../config.php'; 

// Get the form data
$user_id = $_POST['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$telno = $_POST['telno'];
$role = $_POST['role'];

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
        // Email does not exist for another user, proceed with the update
        $stmt = $conn->prepare("UPDATE user SET username = ?, email = ?, telno = ?, role = ? WHERE user_id = ?");
        $stmt->bind_param("ssssi", $username, $email, $telno, $role, $user_id);

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
                window.location.href = "admin_manage_user.php"; // Redirect back to manage users page
            </script>
            <?php
            exit();
        }
    }
}
?>
