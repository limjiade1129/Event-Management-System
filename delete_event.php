<?php
require 'config.php';

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $organizer_id = $_SESSION['user_id'];

    // Delete the event only if it belongs to the logged-in organizer
    $query = "DELETE FROM events WHERE event_id = ? AND created_by = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $event_id, $organizer_id);
    
    if ($stmt->execute()) {
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

    $stmt->close();
    $conn->close();
} else {
    header('Location: my_event.php');
}
?>
