<?php
// Database connection
$hostname = "127.0.0.1";
$username = "root";
$password = "";
$database = "projectDB";

$conn = new mysqli($hostname, $username, $password, $database);
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

    $fields = [];
    $params = [];
    $types = '';

    if (isset($data['password'])) {
        $fields[] = "password = ?";
        $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        $types .= 's';
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
        return false;
    }

    $sql = "UPDATE customers SET " . implode(", ", $fields) . " WHERE id = ?";
    $params[] = $customerId;
    $types .= 'i';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    return $stmt->execute();
}

/**
 * Delete order record and update material quantity
 *
 * @param int $orderId
 * @return bool
 */
function deleteOrder($orderId) {
    global $conn;

    // Check delivery date
    $sql = "SELECT delivery_date FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if (!$order) {
        return false; // Order not found
    }

    $deliveryDate = new DateTime($order['delivery_date']);
    $currentDate = new DateTime();
    $interval = $currentDate->diff($deliveryDate);

    if ($interval->days > 2) {
        return false; // Cannot delete order
    }

    // Update material quantity and delete order
    $sql = "DELETE FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $orderId);
    $stmt->execute();

    // Update material quantity logic goes here
    // Example: updateMaterialQuantity($orderId); // Implement this function as needed

    return true;
}

// Example usage for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
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

    if (isset($_POST['delete_order'])) {
        $orderId = $_POST['order_id'];

        if (deleteOrder($orderId)) {
            echo "Order deleted successfully!";
        } else {
            echo "Order cannot be deleted. It may be too late.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Order Management</title>
</head>
<body>
<header>
<div class="navbar">
        <ul>
            <li><a  href="index.html">Home</a></li>
            <li><a  href="productList.html">Product</a></li>
            <li><a  href="CustomerBuy.html">Buy</a></li>
        </ul>  
    </div>
    <main>
    <h2>Update Customer Profile</h2>
    </header>
    <form method="post" method="customer_order_management.php">
        <input type="hidden" name="customer_id" value="1"> <!-- Replace with actual customer ID -->
        <label for="password">Password:</label>
        <input type="password" name="password"><br>
        <label for="contact_number">Contact Number:</label>
        <input type="text" name="contact_number"><br>
        <label for="address">Address:</label>
        <input type="text" name="address"><br>
        <button type="submit" name="update_profile">Update Profile</button>
    </form>

    <h2>Delete Order</h2>
    <form method="post" onsubmit="return confirm('Are you sure you want to delete this order?');">
        <input type="hidden" name="order_id" value="1"> <!-- Replace with actual order ID -->
        <button type="submit" name="delete_order">Delete Order</button>
    </form>
</main>
</body>
</html>