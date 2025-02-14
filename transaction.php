<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bankmanagementsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert transaction details
$accountID = 1;
$transactionType = 'Deposit';
$amount = 100.00;
$description = 'Deposit of funds';
$recipientAccount = NULL;  // Not needed for deposit
$transactionDate = date('Y-m-d H:i:s');  // Current timestamp

$sql = "INSERT INTO transactions (AccountID, TransactionType, Amount, Description, TransactionDate) 
        VALUES ('$accountID', '$transactionType', '$amount', '$description', '$recipientAccount', '$transactionDate')";

if ($conn->query($sql) === TRUE) {
    echo "Transaction recorded successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
