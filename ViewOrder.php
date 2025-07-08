<?php
// Start session
session_start();

// Check if "cid" cookie exists
if (!isset($_COOKIE['cid'])) {
    header("Location: CustomerLogin.php");
    exit;
}

// Get "cid" from cookie and reset the cookie expiration
$cid = $_COOKIE['cid'];
setcookie('cid', $cid, time() + 3600, '/');

// Database connection
$conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verify "cid" exists in the database
$sql = "SELECT * FROM customer WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: CustomerLogin.php");
    exit;
}

// Handle order cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $oid = $_POST['cancel_order'];

    // Check if the order can be canceled (at least 2 days before delivery date)
    $sql = "SELECT odeliverydate FROM orders WHERE oid = ? AND cid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $oid, $cid);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order) {
        $currentDate = new DateTime();
        $deliveryDate = new DateTime($order['odeliverydate']);
        $interval = $currentDate->diff($deliveryDate);

        if ($interval->days >= 2 && $deliveryDate > $currentDate) {
            // Delete the order
            $sql = "DELETE FROM orders WHERE oid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $oid);
            if ($stmt->execute()) {
                $message = "Order canceled successfully.";
            } else {
                $message = "Failed to cancel the order. Please try again.";
            }
        } else {
            $message = "Order cannot be canceled as it is less than 2 days before the delivery date.";
        }
    } else {
        $message = "Order not found.";
    }
}

// Fetch all orders for the current customer
$sql = "SELECT * FROM orders WHERE cid = ? ORDER BY odate DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>View Orders</title>
</head>
<body>
    <div class="navbar">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="productList.html">Product</a></li>
            <li><a href="CustomerBuy.php">Buy</a></li>
            <li><a href="profile.php">Customer</a></li>
            <li><a href="ViewOrder.php">Order</a></li>
        </ul>
    </div>

    <main>
        <h1>Your Orders</h1>
        <?php if (isset($message)): ?>
            <p style="color: red;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <div class="card">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Product ID</th>
                            <th>Order Quantity</th>
                            <th>Order Cost</th>
                            <th>Delivery Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['oid']) ?></td>
                                <td><?= htmlspecialchars($order['odate']) ?></td>
                                <td><?= htmlspecialchars($order['pid']) ?></td>
                                <td><?= htmlspecialchars($order['oqty']) ?></td>
                                <td><?= htmlspecialchars($order['ocost']) ?></td>
                                <td><?= htmlspecialchars($order['odeliverydate']) ?></td>
                                <td><?= htmlspecialchars($order['ostatus']) ?></td>
                                <td>
                                    <?php
                                    $currentDate = new DateTime();
                                    $deliveryDate = new DateTime($order['odeliverydate']);
                                    $interval = $currentDate->diff($deliveryDate);

                                    if ($interval->days >= 2 && $deliveryDate > $currentDate): ?>
                                        <form method="POST" style="display: inline;">
                                            <button type="submit" name="cancel_order" value="<?= $order['oid'] ?>" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel</button>
                                        </form>
                                    <?php else: ?>
                                        <button disabled>Cannot Cancel</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>