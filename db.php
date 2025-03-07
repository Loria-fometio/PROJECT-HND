<?php
header("Content-Type: text/plain");

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "bank_system";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get form data
$firstName = $_POST['firstName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$accountNumber = $_POST['account']; // Use 'account' as the key
$pincode = $_POST['pincode'];
$accountType = $_POST['accountType'];

// Insert into database
$sql = "INSERT INTO clients (account_number, first_name, email, phone, pincode, account_type, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $accountNumber, $firstName, $email, $phone, $pincode, $accountType);

if ($stmt->execute()) {
    echo "Client added successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>