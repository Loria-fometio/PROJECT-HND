<?php
header("Content-Type: application/json");

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "bank_system";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// Get input data
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];
$password = $data['password'];

// Validate input
if (empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Email and password are required."]);
    exit;
}

// Check if the client already exists
$stmt = $conn->prepare("SELECT id, password_hash FROM clients_login WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Client exists, verify password
    $stmt->bind_result($id, $password_hash);
    $stmt->fetch();

    if (password_verify($password, $password_hash)) {
        // Password is correct, redirect to dashboard
        echo json_encode(["status" => "success", "message" => "Login successful!"]);
    } else {
        // Password is incorrect
        echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
    }
} else {
    // Client does not exist, create a new record
    $password_hash = password_hash($password, PASSWORD_BCRYPT); // Hash the password
    $stmt = $conn->prepare("INSERT INTO clients_login (email, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password_hash);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Login successful!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to create account."]);
    }
}

$stmt->close();
$conn->close();
?>