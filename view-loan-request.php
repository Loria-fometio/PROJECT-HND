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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accountNumber = $_POST['accountNumber'];
    $loanAmount = $_POST['loanAmount'];
    $loanType = $_POST['loanType'];
    $tenure = $_POST['tenure'];
    $purpose = $_POST['purpose'];

    // Get the client ID using the account number
    $clientQuery = $conn->prepare("SELECT id, first_name FROM clients WHERE account_number = ?");
    $clientQuery->bind_param("s", $accountNumber);
    $clientQuery->execute();
    $clientResult = $clientQuery->get_result();

    if ($clientResult->num_rows > 0) {
        $client = $clientResult->fetch_assoc();
        $clientId = $client['id'];
        $clientName = $client['first_name'];

        // Insert loan request into database
        $insertLoan = $conn->prepare("INSERT INTO loan_requests (client_id, loan_amount, loan_type, tenure, purpose, request_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $insertLoan->bind_param("idssi", $clientId, $loanAmount, $loanType, $tenure, $purpose);

        if ($insertLoan->execute()) {
            // Insert notification for the loan officer
            $notificationMessage = "You received a loan request from $clientName (Account No: $accountNumber).";
            $insertNotification = $conn->prepare("INSERT INTO notifications (client_id, message, sent_at) VALUES (?, ?, NOW())");
            $insertNotification->bind_param("is", $clientId, $notificationMessage);
            $insertNotification->execute();

            echo "Loan request submitted successfully!";
        } else {
            echo "Error submitting loan request.";
        }
    } else {
        echo "Invalid account number!";
    }
}
?>
