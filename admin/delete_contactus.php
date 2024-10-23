<?php
include '../config.php'; 

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $contactus_id = $_GET['id'];

    $query = "DELETE FROM contact_us WHERE contactus_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $contactus_id);


    if ($stmt->execute()) {
        // If deletion was successful, show an alert and redirect to the manage contact page
        echo "<script>
                alert('Contact message deleted successfully');
                window.location.href = 'admin_contactus.php';
              </script>";
    } else {
        // If there was an error during deletion, show an error message and redirect
        echo "<script>
                alert('Failed to delete contact message');
                window.location.href = 'admin_contactus.php';
              </script>";
    }
} else {
    // If the contactus_id is not valid, show an error message and redirect
    echo "<script>
            alert('Invalid contact message ID');
            window.location.href = 'admin_contactus.php';
          </script>";
}
?>
