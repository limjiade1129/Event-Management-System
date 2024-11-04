<?php
$title = "Manage Contact Us";
include '../config.php'; // Include your database configuration

// Handle AJAX request to update status
if (isset($_POST['contactus_id']) && isset($_POST['status'])) {
    $contactus_id = $_POST['contactus_id'];
    $status = $_POST['status'];

    // Update the status in the database
    $update_query = "UPDATE contact_us SET status = '$status' WHERE contactus_id = $contactus_id";
    $result = mysqli_query($conn, $update_query);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
    exit; // Terminate the script after handling AJAX
}

// Get the filter parameters from the URL if set
$selected_status = isset($_GET['status']) ? $_GET['status'] : '';

// Fetch data from the contact_us table based on status filter
$contact_us_query = "SELECT * FROM contact_us";
if (!empty($selected_status)) {
    $contact_us_query .= " WHERE status = ?";
    $stmt = $conn->prepare($contact_us_query);
    $stmt->bind_param("s", $selected_status);
} else {
    $stmt = $conn->prepare($contact_us_query);
}
$stmt->execute();
$contact_us_result = $stmt->get_result();
$has_contact_us = $contact_us_result->num_rows > 0;
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
        .contactus-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .contactus-table th, .contactus-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 0.9em;
            text-align: center;
        }
        .contactus-table th {
            background-color: #3498db;
            color: white;
            font-size: 1em;
        }
        .contactus-table tr:hover {
            background-color: #f2f2f2;
        }
        .action-button {
            padding: 10px;
            border-radius: 8px;
            font-size: 0.9em;
            text-decoration: none !important;
            cursor: pointer;
            color: white !important;
            background-color: #3498db;
            transition: background-color 0.3s ease;
            margin-left: 8px;
        }
        .action-button:hover {
            background-color: #2980b9;
        }
        .delete-button {
            background-color: #e74c3c;
        }
        .delete-button:hover {
            background-color: #c0392b;
        }
        .no-results {
            text-align: center;
            color: red;
            font-size: 1em;
            display: <?php echo $has_contact_us ? 'none' : 'table-row'; ?>;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

<!-- Main Content -->
<div class="main-content">
    <h1>Manage Contact Us</h1>
    <div class="d-flex align-items-center mb-3">
        <select class="filter-select" id="statusSelect" onchange="filterContactUs()">
            <option value="">All Status</option>
            <option value="Read" <?php echo ($selected_status === 'Read') ? 'selected' : ''; ?>>Read</option>
            <option value="Unread" <?php echo ($selected_status === 'Unread') ? 'selected' : ''; ?>>Unread</option>
        </select>
        <input type="text" id="searchInput" class="form-control ml-3" placeholder="Search..." onkeyup="searchTable()" style="width: 250px;">
    </div>

    <!-- Contact Us Table -->
    <table class="contactus-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Contact Us ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Title</th>
                <th>Message</th>
                <th>Time Created</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="contactTableBody">
            <?php 
            $no = 1;
            while ($contact = mysqli_fetch_assoc($contact_us_result)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $contact['contactus_id']; ?></td>
                    <td><?php echo $contact['username']; ?></td>
                    <td><?php echo $contact['email']; ?></td>
                    <td><?php echo $contact['subject']; ?></td>
                    <td><?php echo $contact['message']; ?></td>
                    <td><?php echo $contact['time_created']; ?></td>
                    <td><?php echo $contact['status']; ?></td>
                    <td>
                        <a href="javascript:void(0);" class="action-button" onclick="toggleStatus(<?php echo $contact['contactus_id']; ?>)">
                            <?php echo $contact['status'] === 'Read' ? 'Mark as Unread' : 'Mark as Read'; ?>
                        </a>
                        <a href="delete_contactus.php?id=<?php echo $contact['contactus_id']; ?>" class="action-button delete-button" onclick="return confirm('Are you sure you want to delete this contact message?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <tr id="noResultsRow" class="no-results">
                <td colspan="9">No results found.</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function filterContactUs() {
        var status = document.getElementById('statusSelect').value;
        window.location.href = 'admin_contactus.php?status=' + status;
    }

    function searchTable() {
        var input, filter, table, rows, td, i, j, txtValue, hasVisibleRows;
        input = document.getElementById("searchInput");
        filter = input.value.toLowerCase();
        table = document.getElementById("contactTableBody");
        rows = table.getElementsByTagName("tr");
        hasVisibleRows = false;

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
            if (isVisible && rows[i].id !== "noResultsRow") {
                hasVisibleRows = true;
            }
        }
        document.getElementById("noResultsRow").style.display = hasVisibleRows ? "none" : "table-row";
    }

    function toggleStatus(contactusId) {
        var link = event.target;
        var currentStatus = link.textContent.trim() === 'Mark as Read' ? 'Unread' : 'Read';
        var newStatus = currentStatus === 'Read' ? 'Unread' : 'Read';

        $.ajax({
            url: '',
            type: 'POST',
            data: {
                contactus_id: contactusId,
                status: newStatus
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    link.textContent = newStatus === 'Read' ? 'Mark as Unread' : 'Mark as Read';
                    link.closest('tr').querySelector('td:nth-child(8)').textContent = newStatus;
                    // Refresh the table if the filter is set to "Unread" or "Read"
                    var selectedStatus = document.getElementById('statusSelect').value;
                    if (selectedStatus === 'Unread' || selectedStatus === 'Read') {
                        location.reload();
                    }
                } else {
                    alert('Failed to update status: ' + result.message);
                }
            },
            error: function() {
                alert('Error occurred while updating the status');
            }
        });
    }
</script>

</body>
</html>
