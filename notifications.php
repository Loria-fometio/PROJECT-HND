<?php
declare(strict_types=1); // Enforce strict type checking for better code quality

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Database configuration
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'Bank_system';

/**
 * Establishes a connection to the database.
 *
 * @return mysqli Returns a MySQLi connection object.
 * @throws Exception If the connection fails.
 */
function connectToDatabase(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    return $conn;
}

/**
 * Fetches notifications from the database.
 *
 * @param mysqli $conn Database connection object.
 * @return array Returns an array of notification records.
 * @throws Exception If the query fails.
 */
function fetchNotifications(mysqli $conn): array {
    $query = "SELECT * FROM notifications ORDER BY sent_at DESC";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

try {
    // Establish database connection
    $conn = connectToDatabase();

    // Fetch notifications
    $notifications = fetchNotifications($conn);

    // Close the database connection
    $conn->close();
} catch (Exception $e) {
    // Log the error and display a user-friendly message
    error_log($e->getMessage());
    die("An error occurred while processing your request. Please try again later.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Officer Dashboard</title>
    <!-- Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .notification-item {
            border-left: 4px solid #007bff;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f8f9fa;
        }
        .notification-item small {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Loan Officer Notifications</h2>
        <div class="list-group">
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item notification-item">
                        <p class="mb-1"><?= htmlspecialchars($notification['message'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <small><?= htmlspecialchars($notification['sent_at'], ENT_QUOTES, 'UTF-8'); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="list-group-item">
                    <p class="mb-1 text-muted">No notifications found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>