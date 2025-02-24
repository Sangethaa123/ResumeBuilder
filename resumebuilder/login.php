<?php
session_start();

// Database credentials
$host = "localhost";
$username_db = "root";
$password_db = "";
$database = "resume_builder"; // Ensure database name is correct

// Connect to MySQL
$conn = new mysqli($host, $username_db, $password_db, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed!"]));
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if email exists
    $sql = "SELECT id, username, password FROM newregister WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die(json_encode(["status" => "error", "message" => "Database error!"]));
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verify user credentials
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row["password"])) {
            // Start session and store user info
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            
            // Send success response in JSON format
            echo json_encode(["status" => "success", "message" => "Login successful!"]);
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password!"]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found!"]);
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
