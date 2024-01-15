<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>User Dashboard</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome to Your Dashboard, <?php echo $user["name"]; ?></h2>
        <p>This is your user dashboard.</p>
        <p><a href="profile.php">View Profile</a></p>
        <p><a href="change_password.php">Change Password</a></p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
