<?php
$title = "Manage Events";
include '../config.php'; // Include your database configuration

// Get the filter status from the URL if set
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Fetch events based on the filter status and join with the user table to get the username
if ($status_filter === 'Pending') {
    $event_query = "SELECT events.*, user.username FROM events 
                    LEFT JOIN user ON events.created_by = user.user_id 
                    WHERE events.status = 'Pending'";
} elseif ($status_filter === 'Approved') {
    $event_query = "SELECT events.*, user.username FROM events 
                    LEFT JOIN user ON events.created_by = user.user_id 
                    WHERE events.status = 'Approved'";
} else {
    $event_query = "SELECT events.*, user.username FROM events 
                    LEFT JOIN user ON events.created_by = user.user_id";
}
$event_result = mysqli_query($conn, $event_query);
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
            cursor: pointer;
        }
        .add-event-button {
            background-color: #3498db;
            color: #fff !important;
            padding: 10px 20px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 1em;
            text-decoration: none !important;
            font-weight: bold;
            margin-right: 15px;
        }
        .add-event-button:hover {
            background-color: #2980b9;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        .event-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .event-table th, .event-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 0.9em;
            text-align: center;
        }
        .event-table th {
            background-color: #3498db;
            color: white;
            font-size: 1em;
        }
        .event-table tr:hover {
            background-color: #f2f2f2;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        .action-button  {
            background-color: #3498db;
            color: white !important;
            padding: 10px 15px;
            border-radius: 15px;
            font-size: 0.9em;
            text-decoration: none !important;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }
        .action-button:hover {
            background-color: #2980b9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .delete-button {
            background-color: #e74c3c;
            color: white;
        }
        .delete-button:hover {
            background-color: #c0392b;
        }
        .view-button {
            background-color: #f39c12;
            color: white;
        }
        .view-button:hover {
            background-color: #e67e22;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

<!-- Main Content -->
<div class="main-content">
    <h1>Manage Events</h1>
    <div class="d-flex align-items-center mb-3">
    <a href="add_event.php" class="add-event-button">Add Event</a>
        <select class="filter-select ml-3" id="statusFilter" onchange="filterEvents()">
            <option value="" <?php echo $status_filter === '' ? 'selected' : ''; ?>>All Events</option>
            <option value="Pending" <?php echo $status_filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Approved" <?php echo $status_filter === 'Approved' ? 'selected' : ''; ?>>Approved</option>
        </select>
        <input type="text" id="searchInput" class="form-control ml-3" placeholder="Search..." onkeyup="searchTable()" style="width: 250px;">
    </div>

    <!-- Event Table -->
    <table class="event-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Event ID</th>
                <th>Event Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Slots</th>
                <th>Created By</th>
                <th>Username</th>
                <th>Status</th>
                <th>Time Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="eventTableBody">
            <?php 
            $no = 1;
            while($event = mysqli_fetch_assoc($event_result)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $event['event_id']; ?></td>
                <td><?php echo $event['event_name']; ?></td>
                <td><?php echo date("j F Y", strtotime($event['date'])); ?></td>
                <td><?php echo date("g:i A", strtotime($event['start_time'])); ?> - <?php echo date("g:i A", strtotime($event['end_time'])); ?></td>
                <td><?php echo $event['location']; ?></td>
                <td><?php echo $event['slots']; ?></td>
                <td><?php echo $event['created_by']; ?></td>
                <td><?php echo $event['username'] ? $event['username'] : '<span style="color: red;">Deleted User</span>';?></td>
                <td><?php echo $event['status']; ?></td>
                <td><?php echo $event['time_created']; ?></td>
                <td class="table-actions">
                    <div class="action-buttons">
                        <a href="view_event.php?id=<?php echo $event['event_id']; ?>" class="action-button view-button">View Details</a>
                        <a href="edit_event.php?id=<?php echo $event['event_id']; ?>" class="action-button">Edit</a>
                        <a href="delete_event.php?id=<?php echo $event['event_id']; ?>" class="action-button delete-button" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEventModalLabel">Add Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add Event Form -->
                <form action="add_event.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="event_name">Event Name</label>
                        <input type="text" class="form-control" id="event_name" name="event_name" required>
                    </div>
                    <div class="form-group">
                        <label for="event_type">Event Type</label>
                        <select class="form-control" id="event_type" name="event_type" required>
                            <option value="Technology">Technology</option>
                            <option value="Sport">Sport</option>
                            <option value="Gaming">Gaming</option>
                            <option value="Music">Music</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <input type="time" class="form-control" id="start_time" name="start_time" required>
                    </div>
                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <input type="time" class="form-control" id="end_time" name="end_time" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="slots">Slots</label>
                        <input type="number" class="form-control" id="slots" name="slots" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="event_image">Event Image</label>
                        <input type="file" class="form-control" id="event_image" name="event_image" accept="image/*" required>
                    </div>
                    <input type="hidden" name="created_by" value="<?php echo $current_user_id; ?>">
                    <button type="submit" class="btn btn-primary">Add Event</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Edit Event Form -->
                <form action="edit_event.php" method="post">
                    <input type="hidden" id="edit_event_id" name="event_id">
                    <div class="form-group">
                        <label for="edit_event_name">Event Name</label>
                        <input type="text" class="form-control" id="edit_event_name" name="event_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_event_type">Event Type</label>
                        <input type="text" class="form-control" id="edit_event_type" name="event_type" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_date">Date</label>
                        <input type="date" class="form-control" id="edit_date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_location">Location</label>
                        <input type="text" class="form-control" id="edit_location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_slots">Slots</label>
                        <input type="number" class="form-control" id="edit_slots" name="slots" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function filterEvents() {
        var status = document.getElementById('statusFilter').value;
        window.location.href = 'admin_manage_event.php?status=' + status;
    }

    function searchTable() {
        var input, filter, table, rows, td, i, j, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toLowerCase();
        table = document.getElementById("eventTableBody");
        rows = table.getElementsByTagName("tr");

        for (i = 0; i < rows.length; i++) {
            var isVisible = false;
            td = rows[i].getElementsByTagName("td");
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        isVisible = true;
                        break;
                    }
                }
            }
            rows[i].style.display = isVisible ? "" : "none";
        }
    }

</script>

</body>
</html>
