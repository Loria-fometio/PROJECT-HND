<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bank_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get account number from the request
$data = json_decode(file_get_contents('php://input'), true);
$accountNumber = $data['account_number'];

// Check if the account number exists in the clients table
$sql = "SELECT id FROM clients WHERE account_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $accountNumber);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Account number is valid.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Account number not found.']);
}

$stmt->close();
$conn->close();
?>