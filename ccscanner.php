<?php
$servername = "localhost";
$username = "root";
$password = "2626#26Vsl";
$database = "ccweb_reg";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $scannedUrl = $_POST["scannedUrl"];

    try {
        $stmt = $pdo->prepare("SELECT * FROM ccweb_user WHERE email = :email");
        $stmt->bindParam(":email", $scannedUrl);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $email = $row["email"];
            echo json_encode(["email" => $email, "status" => "Approved"]);
        } else {
            echo json_encode(["error" => "ID not found"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }

    // Exit after sending JSON response to prevent HTML output
    exit();
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="https://cdn.rawgit.com/cozmo/jsQR/master/dist/jsQR.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-md5"></script>
</head>
<body>
    <h1>Chalana Chitram</h1>
    <div id="preview-container">
        <video id="camera" width="100%" height="auto" playsinline autoplay></video>
        <canvas id="preview-canvas" style="display:none;"></canvas>
    </div>
    <div id="result"></div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('camera');
            const previewCanvas = document.getElementById('preview-canvas');
            const resultDiv = document.getElementById('result');

            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                    .then(stream => {
                        video.srcObject = stream;
                        video.addEventListener('loadedmetadata', () => {
                            previewCanvas.width = video.videoWidth;
                            previewCanvas.height = video.videoHeight;
                            scanQRCode();
                        });
                    })
                    .catch(error => console.error('Error accessing camera:', error));
            } else {
                console.error('getUserMedia is not supported');
            }

            function scanQRCode() {
                const context = previewCanvas.getContext('2d');

                function scan() {
                    context.drawImage(video, 0, 0, previewCanvas.width, previewCanvas.height);
                    const imageData = context.getImageData(0, 0, previewCanvas.width, previewCanvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);

                    if (code) {
                        checkIfUrlIsApproved(code.data);
                    }

                    requestAnimationFrame(scan);
                }

                // Start the scanning loop
                scan();
            }

            function checkIfUrlIsApproved(scannedUrl) {
    // Log the scanned URL for troubleshooting
    console.log('Scanned URL:', scannedUrl);

    // Send the scanned URL to the server for verification
    fetch('ccscanner.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'scannedUrl=' + encodeURIComponent(scannedUrl),
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error('Error:', data.error);
            resultDiv.textContent = 'Error: ' + data.error;
        } else {
            const { email, status } = data; // Fix: use data.email
            console.log('Found ID:', scannedUrl, 'with e1 value:', status);
            resultDiv.textContent = `email: ${email}, ID: ${scannedUrl}, Status: ${status === 'Approved' ? 'Approved' : 'Rejected'}`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultDiv.textContent = 'Error: ' + error.message;
    });
}

        });
    </script>
</body>
</html>
