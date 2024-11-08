<?php
$title = "Give Feedback";
include 'config.php';

// Check if the event ID is provided in the URL
if (!isset($_GET['id'])) {
    echo "<script>alert('No event ID provided.'); window.location.href='event_history.php';</script>";
    exit;
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch event details for the event that the user is giving feedback on
$query = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Event not found.'); window.location.href='event_history.php';</script>";
    exit;
}

$event = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    
    // Insert feedback into the database
    $query = "INSERT INTO feedback (event_id, user_id, feedback, rating, time_created) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisi", $event_id, $user_id, $feedback, $rating);

    if ($stmt->execute()) {
        echo "<script>alert('Thank you for your feedback!'); window.location.href='event_history.php';</script>";
    } else {
        echo "<script>alert('Error saving feedback.'); window.location.href='feedback.php';</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give Feedback</title>
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
        .create-event button.back-btn {
            background-color: #e74c3c;
        }
        .create-event button.back-btn:hover {
            background-color: #c0392b;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Give Feedback</h1>
        <form id="feedbackForm" action="feedback.php?id=<?php echo $event['event_id']; ?>" method="POST" onsubmit="return validateForm()">
            <label for="event_name">Event Name :</label>
            <input type="text" name="event_name" id="event_name" value="<?php echo $event['event_name']; ?>" readonly>

            <label for="rating">Rating (1-5):</label>
            <select name="rating" id="rating">
                <option value="">Select Rating</option>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>
            <span class="error" id="rating-error"></span>

            <label for="feedback">Feedback :</label>
            <textarea name="feedback" id="feedback" rows="5"></textarea>
            <span class="error" id="feedback-error"></span>

            <div class="create-event">
                <button type="submit">Submit Feedback</button>
                <button type="button" class="back-btn" onclick="goBack()">Back</button>
            </div>
        </form>
    </div>

    <script>
        function goBack() {
            window.history.back(); 
        }

        function validateForm() {
            let isValid = true;
            let rating = document.getElementById('rating').value;
            let feedback = document.getElementById('feedback').value;
            let ratingError = document.getElementById('rating-error');
            let feedbackError = document.getElementById('feedback-error');

            // Reset errors
            ratingError.textContent = '';
            feedbackError.textContent = '';

            // Validate rating
            if (rating === "") {
                ratingError.textContent = "Please select a rating.";
                isValid = false;
            }

            // Validate feedback
            if (feedback.trim() === "") {
                feedbackError.textContent = "Please provide your feedback.";
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
