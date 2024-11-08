<?php
include 'config.php'; 

if (!isset($_SESSION['user_id']) || !isset($_GET['action']) || !isset($_GET['event_id']) || !isset($_GET['user_id'])) {
    echo "<script>alert('Invalid request.'); window.location.href='my_event.php';</script>";
    exit;
}

$action = $_GET['action'];
$event_id = $_GET['event_id'];
$user_id = $_GET['user_id'];

// Perform deletion based on the action type
if ($action == 'delete_registration') {
    // Delete a registration
    $delete_query = "DELETE FROM event_registrations WHERE event_id = ? AND user_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $event_id, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Registration deleted successfully.'); window.history.back();</script>";
    } else {
        echo "<script>alert('Error deleting registration.'); window.history.back();</script>";
    }
    $stmt->close();

} elseif ($action == 'delete_feedback') {
    // Delete feedback
    $delete_query = "DELETE FROM feedback WHERE event_id = ? AND user_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $event_id, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Feedback deleted successfully.'); window.history.back();</script>";
    } else {
        echo "<script>alert('Error deleting feedback.'); window.history.back();</script>";
    }
    $stmt->close();

} else {
    echo "<script>alert('Invalid action.'); window.history.back();</script>";
}

$conn->close();
?>
