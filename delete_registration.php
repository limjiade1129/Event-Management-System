<?php
include 'config.php';

// Check if the event ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "<script>alert('No event ID provided.'); window.location.href='event_history.php';</script>";
    exit;
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id']; // Assuming user ID is stored in the session

// Check if the event date is in the future
$query = "SELECT date FROM events WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if ($event && strtotime($event['date']) > time()) {
    // Delete the registration
    $delete_query = "DELETE FROM event_registrations WHERE event_id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("ii", $event_id, $user_id);
    
    if ($delete_stmt->execute()) {
        echo "<script>alert('Registration deleted successfully.'); window.location.href='event_history.php';</script>";
    } else {
        echo "<script>alert('Error deleting registration.'); window.location.href='event_history.php';</script>";
    }
} else {
    echo "<script>alert('You cannot delete past event registrations.'); window.location.href='event_history.php';</script>";
}

$stmt->close();
$conn->close();
?>
