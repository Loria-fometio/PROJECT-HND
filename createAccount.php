<?php
// Database connection variables
$servername = "localhost";
$username = "root";  // Change this to your database username
$password = "";      // Change this to your database password
$dbname = "bankmanagementsystem"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$fullName = $_POST['fullName'];
$email = $_POST['email'];
$phoneNumber = $_POST['tel'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

// Validate password
$passwordRegex = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

if (!preg_match($passwordRegex, $password)) {
    echo "Password must contain at least one letter, one number, one special character, and be at least 8 characters long.";
    exit;
}

// Check if passwords match
if ($password !== $confirmPassword) {
    echo "Passwords do not match.";
    exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO users (FullName, Email, PhoneNumber, password, confirm_Password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $fullName, $email, $phoneNumber, $hashedPassword, $hashedPassword);

// Execute the query
if ($stmt->execute()) {
    echo "Account created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
