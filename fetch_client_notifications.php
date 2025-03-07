<?php
$conn = new mysqli("localhost", "root", "", "bank_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume client ID is retrieved from session
$client_id = $_SESSION['client_id']; 

// Fetch unread notifications
$sql = "SELECT * FROM notifications WHERE client_id = '$client_id' AND is_read = 0 ORDER BY created_at DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<p>" . $row['message'] . "</p>";
}

// Mark notifications as read after displaying
$conn->query("UPDATE notifications SET is_read = 1 WHERE client_id = '$client_id'");

$conn->close();
?>
