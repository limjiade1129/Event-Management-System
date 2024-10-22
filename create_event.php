<?php
$title = "Create New Event";
include 'config.php';

// Initialize variables for form inputs
$event_name = $event_type = $date = $start_time = $end_time = $location = $slots = $description = "";
$image_err = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['event_name'];
    $event_type = $_POST['event_type'];
    $date = $_POST['date'];
    $start_time = date("H:i:s", strtotime($_POST['start_time'])); // Convert to 24-hour format
    $end_time = date("H:i:s", strtotime($_POST['end_time'])); // Convert to 24-hour format
    $location = $_POST['location'];
    $slots = $_POST['slots'];
    $description = $_POST['description'];
    $created_by = $_SESSION['user_id']; 

    // Handle file upload for image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file already exists
        if (file_exists($target_file)) {
            // Generate a unique file name (e.g., append timestamp)
            $unique_name = pathinfo($image, PATHINFO_FILENAME) . '_' . time() . '.' . $imageFileType;
            $target_file = $target_dir . $unique_name;
            $image = $unique_name; // Update the image name to the new unique name
        }

        // Allow only specific file formats
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            // Upload the file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Save event to the database
                $stmt = $conn->prepare("INSERT INTO events (event_name, event_type, date, start_time, end_time, location, slots, description, image, created_by, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
                $stmt->bind_param("ssssssisss", $event_name, $event_type, $date, $start_time, $end_time, $location, $slots, $description, $image, $created_by);

                if ($stmt->execute()) {
                    echo "<script>alert('Event created successfully!'); window.location.href='my_event.php';</script>";
                } else {
                    echo "<script>alert('Error creating event.');</script>";
                }
                $stmt->close();
            } else {
                $image_err = "Error uploading the image.";
            }
        } else {
            $image_err = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        $image_err = "Please upload an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Event</title>
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
            resize: vertical; /* Allow only vertical resizing */
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Create New Event</h1>
        <form action="create_event.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()" autocomplete="off">
            <label for="event_name">Event Name :</label>
            <input type="text" name="event_name" id="event_name" required>

            <label for="event_type">Event Type :</label>
            <select name="event_type" id="event_type" required>
            <option value="Workshop">Workshop</option>
                <option value="Networking">Networking</option>
                <option value="Sport">Sport</option>
                <option value="Career">Career</option>
                <option value="Art">Art</option>
                <option value="Competition">Competition</option>
                <option value="Gaming">Gaming</option>
                <option value="Social">Social</option>
                <option value="Charity">Charity</option>
                <option value="Others">Others</option>
            </select>

            <label for="date">Event Date :</label>
            <input type="date" name="date" id="date" required>

            <label for="start_time">Start Time :</label>
            <input type="time" name="start_time" id="start_time" required>

            <label for="end_time">End Time :</label>
            <input type="time" name="end_time" id="end_time" required>

            <label for="location">Location :</label>
            <input type="text" name="location" id="location" required>

            <label for="slots">Slots Available :</label>
            <input type="number" name="slots" id="slots" required>

            <label for="description">Event Description :</label>
            <textarea name="description" id="description" rows="5" required></textarea>

            <label for="image">Event Image (JPG, JPEG, PNG & GIF files): </label>
            <input type="file" name="image" id="image" accept="image/*" required>
            <span class="error"><?php echo $image_err; ?></span>

            <div class="create-event">
                <button type="submit">Create Event</button>
            </div>
            <div class="create-event">
                <button type="button" onclick="goBack()">Back</button>
            </div>
        </form>
    </div>
</body>

<script>
    // Prevent manual typing in the date field
    document.getElementById('date').addEventListener('keydown', function(event) {
        event.preventDefault(); // Prevent any typing
    });

    function goBack() {
        window.history.back(); // Navigate to the previous page
    }

    function validateForm() {
        // Get form fields
        const dateField = document.getElementById('date').value;
        const startTimeField = document.getElementById('start_time').value;
        const endTimeField = document.getElementById('end_time').value;

        // Get current date in the format YYYY-MM-DD
        const today = new Date().toISOString().split('T')[0];

        // Check if the date is in the past
        if (dateField < today) {
            alert("Event date cannot be before today's date.");
            return false;
        }

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
