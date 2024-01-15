<?php
session_start();

if (isset($_SESSION["logged_in"])) {
    header("Location: dashboard.php"); // Redirect to dashboard if already logged in
    exit();
}

$servername = "localhost";
$username = "root";
$password = "2626#26Vsl";  // Update with your actual MySQL password
$dbname = "ccweb_reg";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if the identifier is either a mobile number or email
    $sql = "SELECT * FROM ccweb_user WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION["logged_in"] = true;
        $_SESSION["user"] = $user;
        header("Location: profile.php"); // Redirect to the dashboard page
        exit();
    } else {
        echo '<div class="alert alert-danger" role="alert">Invalid email or password</div>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>User Login</title>
</head>
<body>
    <div class="container mt-5">
        <h2>CCLogin</h2>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="login">Login</button>
        </form>
        <p class="mt-3">Don't have an account? <a href="index.php">Register here</a></p>
        <p>Forgot your password? <a href="change_password.php">Change password</a></p>
    </div>
</body>
</html>
