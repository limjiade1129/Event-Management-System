<?php
require 'config.php';

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $organizer_id = $_SESSION['user_id'];

    // Fetch the image file name from the database before deleting the event
    $query = "SELECT image FROM events WHERE event_id = ? AND created_by = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $event_id, $organizer_id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    // Check if the image file exists and delete it
    if ($image) {
        $image_path = "uploads/" . $image;
        if (file_exists($image_path)) {
            unlink($image_path); // Delete the image file
        }
    }

    // Delete related records from the event_registrations table
    $delete_registrations_query = "DELETE FROM event_registrations WHERE event_id = ?";
    $delete_registrations_stmt = $conn->prepare($delete_registrations_query);
    $delete_registrations_stmt->bind_param("i", $event_id);
    $delete_registrations_stmt->execute();
    $delete_registrations_stmt->close();

    // Delete related records from the feedback table
    $delete_feedback_query = "DELETE FROM feedback WHERE event_id = ?";
    $delete_feedback_stmt = $conn->prepare($delete_feedback_query);
    $delete_feedback_stmt->bind_param("i", $event_id);
    $delete_feedback_stmt->execute();
    $delete_feedback_stmt->close();

    // Proceed with deleting the event from the database
    $delete_event_query = "DELETE FROM events WHERE event_id = ? AND created_by = ?";
    $delete_event_stmt = $conn->prepare($delete_event_query);
    $delete_event_stmt->bind_param("ii", $event_id, $organizer_id);

    if ($delete_event_stmt->execute()) {
        echo "<script>
                alert('Event deleted successfully!');
                window.location.href = 'my_event.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting event.');
                window.location.href = 'my_event.php';
              </script>";
    }

    $delete_event_stmt->close();
    $conn->close();
} else {
    header('Location: my_event.php');
}
?>
