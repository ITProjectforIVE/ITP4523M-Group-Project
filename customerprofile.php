<?php
// Database connection
$conn = new mysqli($hostname, $username, $password, $database);
$hostname = "127.0.0.1";
$database = "projectDB";
$username = "root";
$password = "";
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
/**
 * Update customer profile information
 *
 * @param int $customerId
 * @param array $data
 * @return bool
 */
function updateCustomerProfile($customerId, $data) {
    global $conn;

    // Prepare an array for the fields to update
    $fields = [];
    $params = [];
    $types = '';

    // Check and add fields if they exist
    if (isset($data['password'])) {
        $fields[] = "password = ?";
        $params[] = password_hash($data['password'], PASSWORD_DEFAULT); // Hash the password
        $types .= 's'; // For string
    }
    
    if (isset($data['contact_number'])) {
        $fields[] = "contact_number = ?";
        $params[] = $data['contact_number'];
        $types .= 's';
    }
    
    if (isset($data['address'])) {
        $fields[] = "address = ?";
        $params[] = $data['address'];
        $types .= 's';
    }

    if (empty($fields)) {
        return false; // No fields to update
    }

    // Create the SQL query
    $sql = "UPDATE customers SET " . implode(", ", $fields) . " WHERE id = ?";
    $params[] = $customerId;
    $types .= 'i'; // For integer

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    // Execute and check if successful
    return $stmt->execute();
}

// Example usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerId = $_POST['customer_id'];
    $data = [
        'password' => $_POST['password'] ?? null,
        'contact_number' => $_POST['contact_number'] ?? null,
        'address' => $_POST['address'] ?? null
    ];

    if (updateCustomerProfile($customerId, $data)) {
        echo "Profile updated successfully!";
    } else {
        echo "No changes made or update failed.";
    }
}

$conn->close();
?>