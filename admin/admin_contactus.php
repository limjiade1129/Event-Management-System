<?php
$title = "Manage Contact Us";
include '../config.php'; // Include your database configuration

// Fetch data from the contact_us table
$contact_us_query = "SELECT * FROM contact_us";
$contact_us_result = mysqli_query($conn, $contact_us_query);
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
        .no-results {
            text-align: center;
            color: red;
            font-size: 1em;
            display: none; 
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

<!-- Main Content -->
<div class="main-content">
    <h1>Manage Contact Us</h1>
    <div class="d-flex align-items-center mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search..." onkeyup="searchTable()" style="width: 250px;">
    </div>

    <!-- Contact Us Table -->
    <table class="contactus-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Contact Us ID</th>
                <th>Name</th>
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
            <>
                <td><?php echo $no++; ?></td>
                <td><?php echo $contact['contactus_id']; ?></td>
                <td><?php echo $contact['name']; ?></td>
                <td><?php echo $contact['email']; ?></td>
                <td><?php echo $contact['subject']; ?></td>
                <td><?php echo $contact['message']; ?></td>
                <td><?php echo $contact['time_created']; ?></td>
                <td><?php echo $contact['status']; ?></td>
                <td>
                <button class="btn btn-secondary" onclick="toggleStatus(<?php echo $contact['contactus_id']; ?>)">
                <?php echo $contact['status'] === 'Read' ? 'Mark as Unread' : 'Mark as Read'; ?>
                </button>
                    <a href="delete_contactus.php?id=<?php echo $contact['contactus_id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this contact message?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
            <tr id="noResultsRow" class="no-results">
                <td colspan="8">No results found.</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.getElementById("noResultsRow").style.display = "none";

    function searchTable() {
        var input, filter, table, rows, td, i, j, txtValue, hasVisibleRows;
        input = document.getElementById("searchInput");
        filter = input.value.toLowerCase();
        table = document.getElementById("contactTableBody");
        rows = table.getElementsByTagName("tr");
        hasVisibleRows = false; // Variable to track if any row is visible

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

        // Show or hide the "No results found" row
        document.getElementById("noResultsRow").style.display = hasVisibleRows ? "none" : "table-row";
    }
</script>

</body>
</html>
