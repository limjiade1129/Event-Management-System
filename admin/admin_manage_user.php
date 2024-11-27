<?php
$title = "Manage Users";
include '../config.php'; // Include your database configuration

// Get the filter role from the URL if set
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';

// Fetch users based on the filter role
if ($role_filter) {
    $user_query = "SELECT * FROM user WHERE role = '$role_filter'";
} else {
    $user_query = "SELECT * FROM user";
}
$user_result = mysqli_query($conn, $user_query);
$has_events = mysqli_num_rows($user_result) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- For sweet alert modal -->
    
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
        .add-user-button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 1em;
            text-decoration: none;
            font-weight: bold;
            margin-right: 15px;
            outline: none ;
        }
        .add-user-button:hover {
            background-color: #2980b9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .add-user-button:focus {
            outline: none; 
            box-shadow: none; 
        }
        .filter-select {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1em;
            cursor: pointer;
        }
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .user-table th, .user-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 0.9em;
            text-align: center;
        }
        .user-table th {
            background-color: #3498db;
            color: white;
            font-size: 1em;
        }
        .user-table tr:hover {
            background-color: #f2f2f2;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }
        .action-button {
            background-color: #3498db;
            color: white !important;
            padding: 10px 15px;
            border-radius: 15px;
            font-size: 0.9em;
            text-decoration: none !important;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 5px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            outline: none !important;
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
        .no-results {
            text-align: center;
            color: red;
            font-size: 1em;
            display: <?php echo $has_events ? 'none' : 'table-row'; ?>; /* Show if no events */
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?> <!-- Include the sidebar -->

<!-- Main Content -->
<div class="main-content">
    <h1>Manage Users</h1>
    <div class="d-flex align-items-center mb-3">
    <button class="add-user-button" data-toggle="modal" data-target="#addUserModal">Add User</button>
    <select class="filter-select" id="roleFilter" onchange="filterUsers()">
        <option value="" <?php echo $role_filter === '' ? 'selected' : ''; ?>>All Roles</option>
        <option value="User" <?php echo $role_filter === 'User' ? 'selected' : ''; ?>>User</option>
        <option value="Organizer" <?php echo $role_filter === 'Organizer' ? 'selected' : ''; ?>>Organizer</option>
        <option value="Admin" <?php echo $role_filter === 'Admin' ? 'selected' : ''; ?>>Admin</option>
    </select>
    <input type="text" id="searchInput" class="form-control ml-3" placeholder="Search..." onkeyup="searchTable()" style="width: 250px;">
    </div>
    <!-- User Table -->
    <table class="user-table">
        <thead>
            <tr>
                <th>No</th>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Password</th>
                <th>Tel No</th>
                <th>Role</th>
                <th>Time Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <?php 
            $no = 1;
            while($user = mysqli_fetch_assoc($user_result)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $user['user_id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['password']; ?></td>
                <td><?php echo $user['telno']; ?></td>
                <td><?php echo $user['role']; ?></td>
                <td><?php echo $user['time_created']; ?></td>
                <td class="table-actions">
                    <div class="action-buttons">
                        <a href="#" class="action-button" data-toggle="modal" data-target="#editUserModal<?php echo $user['user_id']; ?>" >Edit</a>
                        <a href="delete_user.php?id=<?php echo $user['user_id']; ?>" class="action-button delete-button" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </div>
                </td>
            </tr>

            <!-- Edit User Modal -->
            <div class="modal fade" id="editUserModal<?php echo $user['user_id']; ?>" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="editUserModalLabel">Edit User</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Edit User Form -->
                            <form action="edit_user.php" method="post"  onsubmit="return validateEditUserForm()">
                                <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                <div class="form-group">
                                    <label for="edit_username">Username</label>
                                    <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_email">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_password">Password (leave empty to keep current password):</label>
                                    <input type="password" class="form-control" name="password" id="edit_password">
                                </div>
                                <div class="form-group">
                                    <label for="edit_telno">Tel No</label>
                                    <input type="text" class="form-control" name="telno" value="<?php echo $user['telno']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_role">Role</label>
                                    <select class="form-control" name="role" required>
                                        <option value="User" <?php echo ($user['role'] == 'User') ? 'selected' : ''; ?>>User</option>
                                        <option value="Organizer" <?php echo ($user['role'] == 'Organizer') ? 'selected' : ''; ?>>Organizer</option>
                                        <option value="Admin" <?php echo ($user['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php endwhile; ?>
            <!-- No Results Row -->
            <tr id="noResultsRow" class="no-results">
                <td colspan="12">No users found.</td>
            </tr>
        </tbody>
    </table>
</div>



<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addUserModalLabel">Add User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add User Form -->
                <form action="add_user.php" method="post" onsubmit="return validateAddUserForm()">
                    <div class="form-group">
                        <label for="username">Username :</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password :</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="telno">Tel No :</label>
                        <input type="text" class="form-control" id="telno" name="telno" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role :</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="User">User</option>
                            <option value="Organizer">Organizer</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add User</button>    
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function searchTable() {
        var input, filter, table, rows, td, i, j, txtValue, hasVisibleRows;
        input = document.getElementById("searchInput");
        filter = input.value.toLowerCase();
        table = document.getElementById("userTableBody");
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

            // If a row is visible, mark hasVisibleRows as true
            if (isVisible) {
                hasVisibleRows = true;
            }
        }

        // Show or hide the "No results found" row
        document.getElementById("noResultsRow").style.display = hasVisibleRows ? "none" : "table-row";
    }
    
    function filterUsers() {
        var role = document.getElementById('roleFilter').value;
        window.location.href = 'admin_manage_user.php?role=' + role;
    }

    function validateAddUserForm() {
        // Get form field values
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var telno = document.getElementById("telno").value;

        // Regular expressions for validation

        var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        var passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/; // At least one uppercase, one lowercase, one number, one special char, and 8 characters
        var phoneRegex = /^0[0-9]{9}$/; 

        // Validate Email
        if (!emailRegex.test(email)) {
            alert("Please enter a valid email address.");
            return false;
        }

        // Validate Password
        if (!passwordRegex.test(password)) {
            alert("Password must be at least 8 characters long, including an uppercase letter, a lowercase letter, a number, and a special character.");
            return false;
        }

        // Validate Phone Number
        if (!phoneRegex.test(telno)) {
            alert("Please enter a valid phone number (e.g., 0161234567).");
            return false;
        }

        // If all validations pass
        return true;
    }

    function validateEditUserForm() {
    // Get form field values for Edit User from the specific form
    var editModal = document.querySelector('.modal.show');
    var email = editModal.querySelector("[name='email']").value;
    var password = editModal.querySelector("[name='password']").value;
    var telno = editModal.querySelector("[name='telno']").value;

    // Regular expressions for validation
    var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    var passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/; // At least one uppercase, one lowercase, one number, one special char, and 8 characters
    var phoneRegex = /^0[0-9]{9}$/; 

    // Validate Email
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    // Validate Phone Number
    if (!phoneRegex.test(telno)) {
        alert("Please enter a valid phone number (e.g., 0161234567).");
        return false;
    }

    // Validate Password only if a new password is entered
    if (password !== "" && !passwordRegex.test(password)) {
        alert("Password must be at least 8 characters long, including an uppercase letter, a lowercase letter, a number, and a special character.");
        return false;
    }

    // If all validations pass
    return true;
}

    // Clear the form fields when the "Add User" modal is closed
    $('#addUserModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
</script>

</body>
</html>
