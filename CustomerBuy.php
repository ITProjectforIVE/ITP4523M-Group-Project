<?php
// 开启会话以读取 Cookie
session_start();

// check cid
if (!isset($_COOKIE['cid'])) {
    // if no cid, login
    header("Location: CustomerLogin.php");
    exit;
}

// get cid
$cid = $_COOKIE['cid'];

setcookie('cid', $cid, time() + 3600, '/');

$conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// use cid to get
$sql = "SELECT * FROM customer WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();

// check 
$customer = $result->fetch_assoc();
if (!$customer) {
    // user can not find
    header("Location: CustomerLogin.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Customer Profile</title>
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
        <h1>Customer Information</h1>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Name</th>
                        <th>Password</th>
                        <th>Telephone</th>
                        <th>Address</th>
                        <th>Company</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= htmlspecialchars($customer['cid']) ?></td>
                        <td><?= htmlspecialchars($customer['cname']) ?></td>
                        <td><?= htmlspecialchars($customer['cpassword']) ?></td>
                        <td><?= htmlspecialchars($customer['ctel']) ?></td>
                        <td><?= htmlspecialchars($customer['caddr']) ?></td>
                        <td><?= htmlspecialchars($customer['company']) ?></td>
                        <td>
                            <!-- Edit information CustomerProfile.php -->
                            <a href="profile.php">
                                <button>Edit information</button>
                            </a>
                            <!-- Buy Product productorder.php -->
                            <a href="productorder.php">
                                <button>Buy Product</button>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>