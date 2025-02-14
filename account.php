<?php
// Database connection variables
$servername = "localhost";
$username = "root";  // Your database username
$password = "";      // Your database password
$dbname = "bankmanagementsystem"; // Your database name

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
$accountNumber = $_POST['accountNumber']; // assuming the user inputs an account number
$accountType = $_POST['accountType']; // either 'Savings' or 'Checking'

// Check if the email already exists
$sql_check_email = "SELECT * FROM users WHERE Email = ?";
$stmt_check_email = $conn->prepare($sql_check_email);
$stmt_check_email->bind_param("s", $email);
$stmt_check_email->execute();
$result = $stmt_check_email->get_result();

if ($result->num_rows > 0) {
    // Email exists
    echo "The email address is already registered.";
} else {
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the users table
    $sql_user = "INSERT INTO users (FullName, Email, PhoneNumber, password) VALUES (?, ?, ?, ?)";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("ssss", $fullName, $email, $phoneNumber, $hashedPassword);

    // Execute the user insert query
    if ($stmt_user->execute()) {
        // Get the UserID of the newly created user
        $userID = $stmt_user->insert_id;

        // Insert the account data into the accounts table using the UserID
        $sql_account = "INSERT INTO accounts (UserID, AccountNumber, AccountType) VALUES (?, ?, ?)";
        $stmt_account = $conn->prepare($sql_account);
        $stmt_account->bind_param("iss", $userID, $accountNumber, $accountType);

        // Execute the account insert query
        if ($stmt_account->execute()) {
            echo "Account created successfully!";
        } else {
            echo "Error inserting account: " . $stmt_account->error;
        }

        $stmt_account->close();
    } else {
        echo "Error inserting user: " . $stmt_user->error;
    }

    $stmt_user->close();
}

$stmt_check_email->close();
$conn->close();
?>
