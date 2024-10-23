<?php
include '../config.php'; // Include your database configuration

// Check if the feedback ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $feedback_id = $_GET['id'];

    // Prepare the SQL statement to delete the feedback
    $query = "DELETE FROM feedback WHERE feedback_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $feedback_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>
                alert('Feedback deleted successfully');
                window.location.href = 'admin_feedback.php';
              </script>";
    } else {
        // If there was an error during deletion, show an error message and redirect
        echo "<script>
                alert('Failed to delete feedback');
                window.location.href = 'admin_feedback.php';
              </script>";
    }
} else {
    // If the feedback ID is not valid, show an error message and redirect
    echo "<script>
            alert('Invalid feedback ID');
            window.location.href = 'admin_feedback.php';
          </script>";
}
?>
