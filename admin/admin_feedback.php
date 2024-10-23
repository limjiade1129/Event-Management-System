<?php
$title = "Manage Feedback";
include '../config.php'; // Include your database configuration

// Get the filter parameters from the URL if set
$selected_event_id = isset($_GET['event_id']) ? $_GET['event_id'] : '';

// Fetch all events for the dropdown filter
$event_query = "SELECT event_id, event_name FROM events";
$event_list = mysqli_query($conn, $event_query);

// Fetch feedback based on the filters
$feedback_query = "SELECT feedback.*, events.event_name, user.username 
                   FROM feedback 
                   LEFT JOIN events ON feedback.event_id = events.event_id 
                   LEFT JOIN user ON feedback.user_id = user.user_id";

if (!empty($selected_event_id)) {
    // Filter by selected event if event_id is provided
    $feedback_query .= " WHERE feedback.event_id = ?";
    $stmt = $conn->prepare($feedback_query);
    $stmt->bind_param("i", $selected_event_id);
} else {
    // Show all feedback if no specific event is selected
    $stmt = $conn->prepare($feedback_query);
}

$stmt->execute();
$feedback_result = $stmt->get_result();
$has_feedback = $feedback_result->num_rows > 0; // Check if there is any feedback

// Set feedback preview length
$feedback_preview_length = 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
            width: calc(100% - 250px);
            transition: margin-left 0.3s, width 0.3s;
        }
        .main-content h1 {
            font-size: 2.5em;
            margin-bottom: 30px;
        }
        .filter-select {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1em;
            margin-right: 10px;
        }
        .feedback-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .feedback-table th, .feedback-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 0.9em;
            text-align: center;
        }
        .feedback-table th {
            background-color: #3498db;
            color: white;
            font-size: 1em;
        }
        .feedback-table tr:hover {
            background-color: #f2f2f2;
        }
        .delete-button {
            background-color: #e74c3c;
            color: white !important;
            padding: 10px;
            border-radius: 8px;
            font-size: 0.9em;
            text-decoration: none !important;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #c0392b;
        }
        .toggle-feedback {
            color: #3498db;
            cursor: pointer;
            text-decoration: underline;
        }
        .no-results {
            text-align: center;
            color: red;
            font-size: 1em;
            display: <?php echo $has_feedback ? 'none' : 'table-row'; ?>; 
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

<!-- Main Content -->
<div class="main-content">
    <h1>Manage Feedback</h1>
    <div class="d-flex align-items-center mb-3">
        <select class="filter-select" id="eventSelect" onchange="filterFeedback()">
            <option value="">All Events</option>
            <?php while ($event = mysqli_fetch_assoc($event_list)): ?>
                <option value="<?php echo $event['event_id']; ?>" <?php echo ($selected_event_id == $event['event_id']) ? 'selected' : ''; ?>>
                    <?php echo $event['event_name']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        <input type="text" id="searchInput" class="form-control ml-3" placeholder="Search..." onkeyup="searchTable()" style="width: 250px;">
    </div>

    <!-- Feedback Table -->
    <table class="feedback-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Feedback ID</th>
                <th>Event ID</th>
                <th>Event Name</th>
                <th>User</th>
                <th>Feedback</th>
                <th>Rating</th>
                <th>Time Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <?php 
            if ($has_feedback):
                $no = 1;
                while ($feedback = mysqli_fetch_assoc($feedback_result)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $feedback['feedback_id']; ?></td>
                    <td><?php echo $feedback['event_id']; ?></td>
                    <td><?php echo $feedback['event_name']; ?></td>
                    <td><?php echo $feedback['username'] ? $feedback['username'] : '<span style="color: red;">Deleted User</span>'; ?></td>
                    <td>
                        <?php
                        if (strlen($feedback['feedback']) > $feedback_preview_length) {
                            $short_feedback = substr($feedback['feedback'], 0, $feedback_preview_length);
                            $full_feedback = $feedback['feedback'];
                            echo '<span class="short-feedback">' . $short_feedback . '...</span>';
                            echo '<span class="full-feedback" style="display:none;">' . $full_feedback . '</span>';
                            echo '<a href="javascript:void(0)" class="toggle-feedback" onclick="toggleFeedback(this)"> Read More</a>';
                        } else {
                            echo $feedback['feedback'];
                        }
                        ?>
                    </td>
                    <td><?php echo $feedback['rating']; ?>/5</td>
                    <td><?php echo $feedback['time_created']; ?></td>
                    <td>
                        <a href="delete_feedback.php?id=<?php echo $feedback['feedback_id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this feedback?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php endif; ?>
            <!-- No Results Row -->
            <tr id="noResultsRow" class="no-results">
                <td colspan="9">No feedback found.</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function filterFeedback() {
        var eventId = document.getElementById('eventSelect').value;
        window.location.href = 'admin_feedback.php?event_id=' + eventId;
    }

    function searchTable() {
        var input, filter, table, rows, td, i, j, txtValue, hasVisibleRows;
        input = document.getElementById("searchInput");
        filter = input.value.toLowerCase();
        table = document.getElementById("tableBody");
        rows = table.getElementsByTagName("tr");
        hasVisibleRows = false; // Variable to track if any row is visible

        for (i = 0; i < rows.length; i++) {
            var isVisible = false;
            td = rows[i].getElementsByTagName("td");

            // Skip the "No results" row during the search
            if (rows[i].id === "noResultsRow") {
                continue;
            }

            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        isVisible = true;
                        break;
                    }
                }
            }

            // Show or hide the current row based on the search result
            rows[i].style.display = isVisible ? "" : "none";

            // If a row is visible and it's not the "No results" row, mark hasVisibleRows as true
            if (isVisible) {
                hasVisibleRows = true;
            }
        }

        // Show or hide the "No results found" row
        document.getElementById("noResultsRow").style.display = hasVisibleRows ? "none" : "table-row";
    }

    function toggleFeedback(element) {
        const shortFeedback = element.previousElementSibling.previousElementSibling;
        const fullFeedback = element.previousElementSibling;
        
        if (fullFeedback.style.display === "none" || fullFeedback.style.display === "") {
            fullFeedback.style.display = "inline";
            shortFeedback.style.display = "none";
            element.innerText = " Read Less";
        } else {
            fullFeedback.style.display = "none";
            shortFeedback.style.display = "inline";
            element.innerText = " Read More";
        }
    }

    document.querySelectorAll('.full-feedback').forEach(item => item.style.display = 'none');
</script>

</body>
</html>
