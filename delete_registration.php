<?php
include 'config.php';

// Check if the event ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "<script>alert('No event ID provided.'); window.location.href='event_history.php';</script>";
    exit;
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id']; // Assuming user ID is stored in the session

// Delete the registration
$delete_query = "DELETE FROM event_registrations WHERE event_id = ? AND user_id = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("ii", $event_id, $user_id);

if ($delete_stmt->execute()) {
    // Increase the event slots after delete the registration
    $update_slots_query = "UPDATE events SET slots = slots + 1 WHERE event_id = ?";
    $update_slots_stmt = $conn->prepare($update_slots_query);
    $update_slots_stmt->bind_param("i", $event_id);
    
    if ($update_slots_stmt->execute()) {
        echo "<script>alert('Registration deleted successfully.'); window.location.href='event_history.php';</script>";
    } else {
        echo "<script>alert('Registration deleted, Error Update Slots.'); window.location.href='event_history.php';</script>";
    }
} else {
    echo "<script>alert('Error deleting registration.'); window.location.href='event_history.php';</script>";
}

$delete_stmt->close();
if (isset($update_slots_stmt)) {
    $update_slots_stmt->close();
}
$conn->close();
?>
