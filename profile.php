<?php
// 开启会话以读取 Cookie
session_start();

// 检查是否存在 "cid" Cookie
if (!isset($_COOKIE['cid'])) {
    // 如果未检测到 "cid" Cookie，跳转到登录页面
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

// 根据 "cid" 查询当前客户的信息
$sql = "SELECT cname, cpassword, ctel, caddr, company FROM customer WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

// 如果客户不存在，跳转到登录页面
if (!$customer) {
    header("Location: CustomerLogin.php");
    exit;
}

// 关闭数据库连接
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Customer Profile</title>
    <script>
        // 显示/隐藏密码功能
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('cpassword');
            const toggleButton = document.getElementById('togglePassword');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleButton.textContent = 'Hide Password';
            } else {
                passwordField.type = 'password';
                toggleButton.textContent = 'Show Password';
            }
        }

        // 显示成功提示框
        function showSuccessMessage() {
            alert('Customer information updated successfully!');
        }
    </script>
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
        <h1>Edit Customer Information</h1>
        <div class="card">
            <!-- 表单提交到自己 -->
            <form id="profile-form" action="profile.php" method="post" onsubmit="showSuccessMessage();">
                <label for="cname">Customer Name:</label>
                <input type="text" id="cname" name="cname" value="<?= htmlspecialchars($customer['cname']) ?>" required>
                <br />

                <label for="cpassword">Password:</label>
                <input type="password" id="cpassword" name="cpassword" value="<?= htmlspecialchars($customer['cpassword']) ?>" required>
                <button type="button" id="togglePassword" onclick="togglePasswordVisibility()">Show Password</button>
                <br />

                <label for="ctel">Telephone:</label>
                <input type="tel" id="ctel" name="ctel" value="<?= htmlspecialchars($customer['ctel']) ?>" required>
                <br />

                <label for="caddr">Address:</label>
                <input type="text" id="caddr" name="caddr" value="<?= htmlspecialchars($customer['caddr']) ?>" required>
                <br />

                <label for="company">Company Name:</label>
                <input type="text" id="company" name="company" value="<?= htmlspecialchars($customer['company']) ?>" required>
                <br />

                <button type="submit">Confirm</button>
            </form>
        </div>
    </main>

    <?php
    // 处理表单提交逻辑
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 获取表单提交的数据
        $cname = $_POST['cname'];
        $cpassword = $_POST['cpassword'];
        $ctel = $_POST['ctel'];
        $caddr = $_POST['caddr'];
        $company = $_POST['company'];

        // 数据库连接
        $conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // 更新客户信息
        $sql = "UPDATE customer SET cname = ?, cpassword = ?, ctel = ?, caddr = ?, company = ? WHERE cid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $cname, $cpassword, $ctel, $caddr, $company, $cid);
        $stmt->execute();

        // 关闭数据库连接
        $conn->close();

        // 重定向到 CustomerBuy.php
        header("Location: CustomerBuy.php");
        exit;
    }
    ?>
</body>
</html>