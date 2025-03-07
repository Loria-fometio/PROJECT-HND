<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "Bank_system";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle notification submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_notification'])) {
    $clientId = (int)$_POST['client_id'];
    $message = $_POST['message'];

    // Insert notification into the database
    $query = "INSERT INTO notifications (client_id, message) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $clientId, $message);

    if ($stmt->execute()) {
        // Fetch client email
        $clientEmail = getClientEmail($clientId, $conn);
        if ($clientEmail) {
            // Send email notification
            $subject = "Notification from Loan Officer";
            $headers = "From: loan_officer@example.com";
            if (mail($clientEmail, $subject, $message, $headers)) {
                echo "<script>alert('Notification sent successfully!');</script>";
            } else {
                echo "<script>alert('Error sending email notification.');</script>";
            }
        } else {
            echo "<script>alert('Client email not found.');</script>";
        }
    } else {
        echo "<script>alert('Error saving notification.');</script>";
    }
}

// Function to fetch client email by ID
function getClientEmail($clientId, $conn) {
    $query = "SELECT email FROM clients WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['email'];
    }
    return null;
}

// Fetch client data for the dropdown
$clientsQuery = "SELECT id, first_name, email FROM clients";
$clientsResult = $conn->query($clientsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notification</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        h2 {
            color: #007bff;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 500;
            color: #333;
        }
        .form-control {
            border-radius: 4px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
        }
        .btn-primary {
            width: 100%;
            background-color: #007bff;
            border: none;
            padding: 10px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="bi bi-bell-fill"></i> Send Notification</h2>
        <div class="form-container">
            <form method="POST" action="">
                <!-- Client Dropdown -->
                <div class="mb-3">
                    <label for="client_id" class="form-label">Select Client</label>
                    <select class="form-control" id="client_id" name="client_id" required>
                        <option value="">Choose a client</option>
                        <?php while ($row = $clientsResult->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['first_name'] ?> (<?= $row['email'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Notification Message -->
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="send_notification" class="btn btn-primary"><i class="bi bi-send"></i> Send Notification</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>