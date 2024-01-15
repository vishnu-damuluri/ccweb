<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}

// Check if the user identifier key exists in the session
if (!isset($_SESSION["user"]["id"])) {
    echo "Error: User identifier not found.";
    exit();
}

$id = $_SESSION["user"]["id"];

// Database connection
$servername = "localhost";
$username = "root";
$password = "2626#26Vsl";  // Update with your actual MySQL password
$dbname = "ccweb_reg";

// Use try-catch to handle database connection errors
try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check for database connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// Initialize variables
$registrationSuccess = false;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve selected events from the form
    $events = isset($_POST["events"]) ? $_POST["events"] : array();

    // Use prepared statement to prevent SQL injection
    $sqlUpdate = "UPDATE ccweb_user SET e1=?, e2=?, e3=?, e4=? WHERE id=?";
    $stmt = $conn->prepare($sqlUpdate);

    // Bind parameters
    $stmt->bind_param("iiiii", $e1, $e2, $e3, $e4, $id);

    // Set parameter values
    $e1 = (in_array("e1", $events)) ? 1 : 0;
    $e2 = (in_array("e2", $events)) ? 1 : 0;
    $e3 = (in_array("e3", $events)) ? 1 : 0;
    $e4 = (in_array("e4", $events)) ? 1 : 0;

    // Execute the update
    $stmt->execute();

    // Check for errors in the update
    if ($stmt->error) {
        echo "Error updating events: " . $stmt->error;
        exit();
    }

    // Set registration success flag
    $registrationSuccess = true;

    // Close the statement
    $stmt->close();

    // Redirect to logout script after successful registration
    header("Location: logout.php");
    exit();
}

// Fetch user details
$sqlSelect = "SELECT id, name, email, mobile, adhar, city, e1, e2, e3, e4 FROM ccweb_user WHERE id = ?";
$stmtSelect = $conn->prepare($sqlSelect);
$stmtSelect->bind_param("i", $id);
$stmtSelect->execute();

// Check for errors in the select
if ($stmtSelect->error) {
    echo "Error selecting user: " . $stmtSelect->error;
    exit();
}

// Get result
$resultSelect = $stmtSelect->get_result();
$userDetails = $resultSelect->fetch_assoc();

// Close the statement
$stmtSelect->close();

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

    <title>User Profile</title>
</head>
<body>
    <div class="container mt-5">
        <h2>User Profile</h2>
        <p>Name: <?php echo $userDetails["name"]; ?></p>
        <p>Email: <?php echo $userDetails["email"]; ?></p>
        <p>Mobile: <?php echo $userDetails["mobile"]; ?></p>
        <p>Adhar Number: <?php echo $userDetails["adhar"]; ?></p>
        <p>Indian City: <?php echo $userDetails["city"]; ?></p>

        <?php if ($registrationSuccess): ?>
            <p class="text-success">Registered successfully!</p>
        <?php endif; ?>

        <?php if ($userDetails["e1"] || $userDetails["e2"] || $userDetails["e3"] || $userDetails["e4"]): ?>
            <p class="text-info">You are already registered for the following events:</p>
            <ul>
                <?php if ($userDetails["e1"]) echo "<li>Event 1</li>"; ?>
                <?php if ($userDetails["e2"]) echo "<li>Event 2</li>"; ?>
                <?php if ($userDetails["e3"]) echo "<li>Event 3</li>"; ?>
                <?php if ($userDetails["e4"]) echo "<li>Event 4</li>"; ?>
            </ul>
            <!-- Generate QR code using user's email -->
            <div id="qrcode"></div>
            <script>
                var email = "<?php echo $userDetails['email']; ?>";
                var qrcode = new QRCode(document.getElementById("qrcode"), {
                    text: email,
                    width: 128,
                    height: 128
                });
            </script>
        <?php else: ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <h3>Images</h3>
<div class="row">
                <div class="col-md-3">
                    <img src="ev.jpeg" alt="Event 1" class="img-fluid">
                    <label for="event1">Event 1</label>
                    <input type="checkbox" id="event1" name="events[]" value="e1">
                </div>
                <div class="col-md-3">
                    <img src="ev.jpeg" alt="Event 2" class="img-fluid">
                    <label for="event2">Event 2</label>
                    <input type="checkbox" id="event2" name="events[]" value="e2">
                </div>
                <div class="col-md-3">
                    <img src="ev.jpeg" alt="Event 3" class="img-fluid">
                    <label for="event3">Event 3</label>
                    <input type="checkbox" id="event3" name="events[]" value="e3">
                </div>
                <div class="col-md-3">
                    <img src="ev.jpeg" alt="Event 4" class="img-fluid">
                    <label for="event4">Event 4</label>
                    <input type="checkbox" id="event4" name="events[]" value="e4">
                </div>
            </div>
                <button type="submit" class="btn btn-primary mt-3">Register</button>
            </form>
        <?php endif; ?>
<br>
        <p><a href="change_password.php">Change Password</a></p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
