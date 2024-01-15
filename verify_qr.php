<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


$servername = 'localhost';  // Replace with your MySQL host
$username = 'root';         // Replace with your MySQL username
$password = '2626#26Vsl';   // Replace with your MySQL password
$database = 'ccweb_reg';    // Replace with your MySQL database name

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the QR data from the AJAX request
$qrData = isset($_POST['qrData']) ? $_POST['qrData'] : '';

// Query the database to verify the QR data
$query = "SELECT * FROM ccweb_user WHERE email = '$qrData'"; // Adjust column name
$result = $conn->query($query);

// Process the result
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $email = $row['email'];
    // You can return the email or other relevant information to the JavaScript code
    echo json_encode(['status' => 'success', 'email' => $email]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'QR data not found']);
}

// Close the database connection
$conn->close();
?>
