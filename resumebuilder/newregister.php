<?php
session_start();

// Database credentials
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "resume_builder"; // Ensure correct database name

// Connect to MySQL
$conn = new mysqli($host, $username_db, $password_db, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate fields
    if (empty($username) || empty($phone) || empty($email) || empty($password)) {
        header("Location: index.html?error=All fields are required!");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.html?error=Invalid email format!");
        exit();
    }

    // Validate phone number (must be 10 digits)
    if (!preg_match('/^\d{10}$/', $phone)) {
        header("Location: index.html?error=Phone number must be 10 digits!");
        exit();
    }

    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM newregister WHERE email = ?");
    if (!$check_email) {
        die("Prepare failed: " . $conn->error);
    }
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        header("Location: index.html?error=Email already registered!");
        exit();
    }
    $check_email->close();

    // Insert user into database
    $sql = "INSERT INTO newregister (username, phone, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("ssss", $username, $phone, $email, $hashed_password);

    if ($stmt->execute()) {
        header("Location: login.php?success=Registration successful! Please login.");
        exit();
    } else {
        header("Location: index.html?error=Registration failed. Please try again.");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
