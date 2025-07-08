<?php
// 开启会话
session_start();

// 检查是否存在 "cid" Cookie
if (!isset($_COOKIE['cid'])) {
    header("Location: CustomerLogin.php");
    exit;
}

// 获取 "cid" 的值
$cid = $_COOKIE['cid'];

// 重置 Cookie 时间为 1 小时
setcookie('cid', $cid, time() + 3600, '/');

// 数据库连接
$conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 检查 "cid" 是否存在于数据库
$sql = "SELECT * FROM customer WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    // 如果客户不存在，跳转到登录页面
    header("Location: CustomerLogin.php");
    exit;
}

// 查询所有产品信息
$sql = "SELECT * FROM product";
$product_result = $conn->query($sql);

// 关闭数据库连接
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Order</title>
    <style>
        /* General table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent table background */
            color: #fff;
        }

        /* Table headers */
        th {
            background-color: blue;
            color: yellow;
            text-align: left;
            padding: 10px;
        }

        /* Table rows */
        td {
            padding: 10px;
            border: 1px solid #444;
            vertical-align: top;
        }

        /* Description column with text overflow handling */
        td.description {
            max-height: 4.8em; /* Limit the height to 3 lines */
            overflow: hidden; /* Hide overflowing content */
            text-overflow: ellipsis; /* Add ellipsis for truncated content */
            display: -webkit-box; /* Enable webkit box model */
            -webkit-line-clamp: 3; /* Limit to 3 lines */
            -webkit-box-orient: vertical;
            white-space: normal; /* Allow text to wrap */
        }

        /* Button styling */
        button {
            background-color: blue;
            color: yellow;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: darkblue;
        }

        /* Background image styling */
        body {
            background: url('Picture/background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Navbar styling */
        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: blue;
            padding: 10px;
        }

        .navbar ul li {
            margin-right: 15px;
        }

        .navbar ul li a {
            color: yellow;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar ul li a:hover {
            text-decoration: underline;
        }
    </style>
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
        <h1>Available Products</h1>
        <div class="card">
            <?php if ($product_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = $product_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['pid']) ?></td>
                                <td><?= htmlspecialchars($product['pname']) ?></td>
                                <td class="description"><?= htmlspecialchars($product['pdesc']) ?></td>
                                <td><?= htmlspecialchars($product['pcost']) ?></td>
                                <td>
                                    <form action="order1.php" method="GET">
                                        <input type="hidden" name="pid" value="<?= htmlspecialchars($product['pid']) ?>">
                                        <button type="submit">Buy</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No products available.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>