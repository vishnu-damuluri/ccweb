
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>User Registration</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Chalana Chitram Registration</h2>

        <?php
$servername = "localhost";
$username = "root";
$password = "2626#26Vsl";
$dbname = "ccweb_reg";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $adhar = $_POST["adhar"];
    $city = $_POST["city"];
    // Set the password as the mobile number
    $password = $_POST["mobile"];

    $sql = "INSERT INTO ccweb_user (name, email, mobile, adhar, city, password) VALUES ('$name', '$email', '$mobile', '$adhar', '$city', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="alert alert-success" role="alert">Registration successful</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error: ' . $sql . "<br>" . $conn->error . '</div>';
    }
}

$conn->close();
?>


        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile:</label>
                <input type="tel" class="form-control" id="mobile" name="mobile" required>
            </div>
            <div class="form-group">
                <label for="adhar">Adhar Number:</label>
                <input type="text" class="form-control" id="adhar" name="adhar" required>
            </div>
            <div class="form-group">
                <label for="city">Indian City:</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p class="mt-3">Already registered? <a href="login.php">Login here</a></p>

    </div>
</body>
</html>
