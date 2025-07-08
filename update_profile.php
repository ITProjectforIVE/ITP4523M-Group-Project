<?php
session_start(); // Assuming you have session management for logged-in users

$hostname = "localhost";
$database = "projectDB";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming customer_id is stored in session after login
$customer_id = $_SESSION['customer_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $new_contact_number = $_POST['new_contact_number'];
    $new_address = $_POST['new_address'];

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update customer information
    $stmt = $conn->prepare("UPDATE customers SET customer_password = ?, customer_telephone = ?, customer_address = ? WHERE customer_id = ?");
    $stmt->bind_param("sssi", $hashed_password, $new_contact_number, $new_address, $customer_id);

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>