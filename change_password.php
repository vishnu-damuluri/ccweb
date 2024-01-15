<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Change Password</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Change Password</h2>

        <?php
        session_start();

        if (!isset($_SESSION["logged_in"])) {
            header("Location: login.php"); // Redirect to login page if not logged in
            exit();
        }

        if (isset($_POST["change_password"])) {
            $email = $_SESSION["email"];
            $current_password = $_POST["current_password"];
            $new_password = $_POST["new_password"];

            $conn = new mysqli("localhost", "root", "2626#26Vsl", "ccweb_reg");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Check if the current password matches the one in the database
            $check_sql = "SELECT * FROM ccweb_user WHERE email='$email' AND password='$current_password'";
            $check_result = $conn->query($check_sql);

            if ($check_result->num_rows > 0) {
                // Update the password
                $update_sql = "UPDATE ccweb_user SET password='$new_password' WHERE mobile='$mobile'";
                $conn->query($update_sql);
                echo '<div class="alert alert-success" role="alert">Password changed successfully</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Incorrect current password</div>';
            }

            $conn->close();
        }
        ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="change_password">Change Password</button>
        </form>
        <p class="mt-3"><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
