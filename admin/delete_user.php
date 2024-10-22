<?php
require '../config.php'; 

// Check if the user ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    // Execute the statement and check if the deletion was successful
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        ?>
        <script>
            alert("User deleted successfully!");
            window.location.href = "admin_manage_user.php"; // Redirect to the manage user page
        </script>
        <?php
    } else {
        $stmt->close();
        $conn->close();
        ?>
        <script>
            alert("Error occurred while deleting the user.");
            window.location.href = "admin_manage_user.php"; // Redirect to the manage user page if an error occurs
        </script>
        <?php
    }
} else {
    ?>
    <script>
        alert("Invalid request. User ID is missing.");
        window.location.href = "admin_manage_user.php"; // Redirect to the manage user page
    </script>
    <?php
    exit();
}
?>
