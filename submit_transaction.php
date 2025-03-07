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

// Get transaction data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Log the received data for debugging
file_put_contents('debug_log.txt', print_r($data, true));

$accountNumber = $data['account_number'] ?? null;
$receiverAccountNumber = $data['receiver_account_number'] ?? null;
$amount = $data['amount'] ?? null;
$transactionType = $data['transaction_type'] ?? null;
$date = $data['date'] ?? null;
$description = $data['description'] ?? null;

// Validate required fields
if (!$accountNumber || !$receiverAccountNumber || !$amount || !$transactionType || !$date) {
    die(json_encode(['status' => 'error', 'message' => 'Missing required fields']));
}

// Insert the transaction into the transactions table
$sql = "INSERT INTO transactions (account_number, receiver_account_number, amount, transaction_type, date, description) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdsss", $accountNumber, $receiverAccountNumber, $amount, $transactionType, $date, $description);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Transaction submitted successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error submitting transaction: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
