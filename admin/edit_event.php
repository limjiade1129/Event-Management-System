<?php
$title = "Admin Edit Event";
include '../config.php';

// Check if the event ID is provided
if (!isset($_GET['id'])) {
    echo "<script>alert('No event ID provided.'); window.history.back();</script>";
    exit;
}

$event_id = $_GET['id'];

// Fetch event details from the database
$query = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Event not found or you do not have permission to edit this event.'); window.location.href='admin_manage_event.php';</script>";
    exit;
}

$event = $result->fetch_assoc();
$image_err = "";

// Handle form submission for updating the event
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['event_name'];
    $event_type = $_POST['event_type'];
    $date = $_POST['date'];
    $start_time = date("H:i:s", strtotime($_POST['start_time'])); 
    $end_time = date("H:i:s", strtotime($_POST['end_time'])); 
    $location = $_POST['location'];
    $slots = $_POST['slots'];
    $description = $_POST['description'];
    $image = $event['image']; 
    $status = $_POST['status'];

    // Handle file upload for image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $new_image = $_FILES['image']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($new_image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file already exists
        if (file_exists($target_file)) {
            // Generate a unique file name (e.g., append timestamp)
            $unique_name = pathinfo($new_image, PATHINFO_FILENAME) . '_' . time() . '.' . $imageFileType;
            $target_file = $target_dir . $unique_name;
            $new_image = $unique_name; // Update the image name to the new unique name
        }

        // Allow only specific file formats
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            // Delete the old image if it exists and is different from the default placeholder
            if (!empty($event['image']) && file_exists("../uploads/" . $event['image'])) {
                unlink("../uploads/" . $event['image']);
            }

            // Upload the new file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $new_image; // Update the image name for the database
            } else {
                $image_err = "Error uploading the new image.";
            }
        } else {
            $image_err = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Update the event, whether a new image is uploaded or not
    $stmt = $conn->prepare("UPDATE events SET event_name = ?, event_type = ?, date = ?, start_time = ?, end_time = ?, location = ?, slots = ?, description = ?, image = ?, status = ? WHERE event_id = ?");
    $stmt->bind_param("ssssssisssi", $event_name, $event_type, $date, $start_time, $end_time, $location, $slots, $description, $image, $status, $event_id);

    if ($stmt->execute()) {
        echo "<script>alert('Event updated successfully!'); window.location.href='admin_manage_event.php';</script>";
    } else {
        echo "<script>alert('Error updating event.');</script>";
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            font-size: 2.2em;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 1.0em;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input, select, textarea {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 0.9em;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        input[type="file"] {
            padding: 3px;
        }
        textarea {
            resize: vertical;
        }
        .create-event {
            text-align: center;
            margin-top: 15px;
        }
        .create-event button {
            background-color: #3498db;
            padding: 10px 40px;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 1.0em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .create-event button:hover {
            background-color: #2980b9;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
        .current-file {
            font-size: 0.9em;
            color: #555;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Event</h1>
        <form action="edit_event.php?id=<?php echo $event['event_id']; ?>" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()" autocomplete="off">
            <label for="event_name">Event Name :</label>
            <input type="text" name="event_name" id="event_name" value="<?php echo $event['event_name']; ?>" required>

            <label for="event_type">Event Type :</label>
            <select name="event_type" id="event_type" required>
                <option value="Workshop" <?php echo ($event['event_type'] == 'Workshop') ? 'selected' : ''; ?>>Workshop</option>
                <option value="Networking" <?php echo ($event['event_type'] == 'Networking') ? 'selected' : ''; ?>>Networking</option>
                <option value="Sport" <?php echo ($event['event_type'] == 'Sport') ? 'selected' : ''; ?>>Sport</option>
                <option value="Career" <?php echo ($event['event_type'] == 'Career') ? 'selected' : ''; ?>>Career</option>
                <option value="Art" <?php echo ($event['event_type'] == 'Art') ? 'selected' : ''; ?>>Art</option>
                <option value="Competition" <?php echo ($event['event_type'] == 'Competition') ? 'selected' : ''; ?>>Competition</option>
                <option value="Gaming" <?php echo ($event['event_type'] == 'Gaming') ? 'selected' : ''; ?>>Gaming</option>
                <option value="Social" <?php echo ($event['event_type'] == 'Social') ? 'selected' : ''; ?>>Social</option>
                <option value="Charity" <?php echo ($event['event_type'] == 'Charity') ? 'selected' : ''; ?>>Charity</option>
                <option value="Others" <?php echo ($event['event_type'] == 'Others') ? 'selected' : ''; ?>>Others</option>
            </select>

            <label for="date">Event Date :</label>
            <input type="date" name="date" id="date" value="<?php echo $event['date']; ?>" required>

            <label for="start_time">Start Time :</label>
            <input type="time" name="start_time" id="start_time" value="<?php echo $event['start_time']; ?>" required>

            <label for="end_time">End Time :</label>
            <input type="time" name="end_time" id="end_time" value="<?php echo $event['end_time']; ?>" required>

            <label for="location">Location :</label>
            <input type="text" name="location" id="location" value="<?php echo $event['location']; ?>" required>

            <label for="slots">Slots Available :</label>
            <input type="number" name="slots" id="slots" value="<?php echo $event['slots']; ?>" required>

            <label for="description">Event Description :</label>
            <textarea name="description" id="description" rows="5" required><?php echo $event['description']; ?></textarea>

            <!-- Show current image -->
            <div class="current-file">
                Current Image: <?php echo $event['image']; ?> (If you don't upload a new image, the existing one will remain)
            </div>

            <label for="image">Event Image (JPG, JPEG, PNG & GIF files): </label>
            <input type="file" name="image" id="image" accept="image/*">
            <span class="error"><?php echo $image_err; ?></span>

            <label for="status">Status :</label>
            <select name="status" id="status" required>
                <option value="Pending" <?php echo ($event['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Approved" <?php echo ($event['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
            </select>

            <div class="create-event">
                <button type="submit">Update Event</button>
            </div>
            <div class="create-event">
                <button type="button" onclick="goBack()">Back</button>
            </div>
        </form>
    </div>
</body>

<script>
    function goBack() {
        window.history.back(); // Navigate to the previous page
    }

    function validateForm() {
        // Get form fields
        const startTimeField = document.getElementById('start_time').value;
        const endTimeField = document.getElementById('end_time').value;

        // Check if the start time is after the end time
        if (startTimeField >= endTimeField) {
            alert("End time cannot be before or the same as start time.");
            return false;
        }

        return true; // Return true if all validations pass
    }
</script>

</html>

<?php
$conn->close();
?>
