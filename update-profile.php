<?php
// Database configuration
$host = 'localhost'; // Replace with your database host
$dbname = 'bank_system'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

// Create a connection to the database
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];

    // Validate form data (basic validation)
    if (empty($fullName) || empty($email)) {
        die("Full name and email are required.");
    }

    // Handle profile picture upload
    $profilePicturePath = null;
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Directory to store uploaded images
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create the directory if it doesn't exist
        }

        $profilePictureName = basename($_FILES['profilePicture']['name']);
        $profilePicturePath = $uploadDir . uniqid() . '_' . $profilePictureName; // Unique filename to avoid conflicts

        // Move the uploaded file to the uploads directory
        if (!move_uploaded_file($_FILES['profilePicture']['tmp_name'], $profilePicturePath)) {
            die("Failed to upload profile picture.");
        }
    }

    // Insert or update the user profile in the database
    try {
        // Check if the user already exists (based on email)
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Update existing user
            $stmt = $conn->prepare("UPDATE users SET full_name = :fullName, profile_picture = :profilePicture WHERE id = :id");
            $stmt->execute([
                ':fullName' => $fullName,
                ':profilePicture' => $profilePicturePath,
                ':id' => $user['id'],
            ]);
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, profile_picture) VALUES (:fullName, :email, :profilePicture)");
            $stmt->execute([
                ':fullName' => $fullName,
                ':email' => $email,
                ':profilePicture' => $profilePicturePath,
            ]);
        }

        // Redirect back to the profile page with a success message
        header("Location: profile.html?message=Profile+updated+successfully");
        exit();
    } catch (PDOException $e) {
        die("Error updating profile: " . $e->getMessage());
    }
}
?>