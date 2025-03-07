<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";  // Change if necessary
$password = "";
$dbname = "bank_system"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["account_number"]) || empty($data["account_number"])) {
    echo json_encode(["status" => "error", "message" => "Missing or empty account number"]);
    exit;
}

$account_number = $conn->real_escape_string($data["account_number"]);

// Query the clients table
$sql = "SELECT first_name FROM clients WHERE account_number = '$account_number'";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "SQL Error: " . $conn->error]);
    exit;
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["status" => "success", "account_name" => $row["first_name"]]);
} else {
    echo json_encode(["status" => "error", "message" => "Account not found"]);
}

$conn->close();
?>
