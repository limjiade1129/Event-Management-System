
<?php
require 'config.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmpassword = $_POST['confirmpassword'];
$telno = $_POST['telno'];
$role = $_POST['role'];

if ($conn->connect_error) {
    die('Connection Failed : '.$conn->connect_error);
} else {
    // Check if the email already exists in the database
    $checkStmt = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Email already exists, display an error message
        $checkStmt->close();
        $conn->close();
        ?>
        <script>
            alert("Email already exists.Try to sign up with another email!");
            window.location.href = "register.php"; // Redirect to the register page
        </script>
        <?php
        exit();
    } else {
        // Hash the password using md5 
        $hashedPassword = md5($password);

        // Email does not exist, proceed with the registration
        $stmt = $conn->prepare("INSERT INTO user(username, email, password, telno, role) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $username, $email, $hashedPassword, $telno, $role);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            ?>
            <script>
                alert("Registered successfully!");
                window.location.href = "login.php";
            </script>
            <?php
            exit();
        } else {
            $stmt->close(); 
            $conn->close();
            ?>
            <script>
                alert("Error occurred while registering!");
                window.location.href = "register.php"; // Redirect to the register page if an error occurs
            </script>
            <?php
            exit();
        }
    }
}
?>
