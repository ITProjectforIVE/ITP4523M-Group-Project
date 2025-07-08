<?php
// 开始会话
session_start();

// 检查是否存在 "cid" cookie
if (!isset($_COOKIE['cid'])) {
    header("Location: CustomerLogin.php");
    exit;
}

// 获取 "cid" 并重置 cookie 过期时间
$cid = $_COOKIE['cid'];
setcookie('cid', $cid, time() + 3600, '/');

// 连接数据库
$conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 验证 "cid" 是否存在于数据库
$sql = "SELECT * FROM customer WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: CustomerLogin.php");
    exit;
}

// 获取 "pid" 参数
if (!isset($_GET['pid'])) {
    die("缺少产品 ID 参数。");
}
$pid = $_GET['pid'];

// 自动生成唯一的 "oid"
do {
    $oid = rand(1000, 9999); // 随机生成订单 ID
    $sql = "SELECT * FROM orders WHERE oid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $oid);
    $stmt->execute();
    $result = $stmt->get_result();
} while ($result->num_rows > 0);

// 从产品表中获取指定产品的详细信息
$sql = "SELECT pname, pcost FROM product WHERE pid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pid);
$stmt->execute();
$product_result = $stmt->get_result();
if ($product_result->num_rows === 0) {
    die("未找到指定的产品记录。");
}
$product = $product_result->fetch_assoc();
$pname = $product['pname'];
$pcost = $product['pcost'];

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oqty = $_POST['oqty'];
    $odeliverydate = $_POST['odeliverydate'];

    // 验证用户输入
    if ($oqty <= 0) {
        $error = "订单数量必须大于 0。";
    } elseif (empty($odeliverydate)) {
        $error = "交货日期是必填项。";
    } else {
        $currentDate = date('Y-m-d H:i:s');
        if ($odeliverydate <= $currentDate) {
            $error = "交货日期必须晚于当前日期。";
        } else {
            $ocost = $pcost * $oqty; // 计算订单总价
            $ostatus = 1; // 默认订单状态为 "Open"

            // 将订单插入到数据库
            $sql = "INSERT INTO orders (oid, odate, pid, oqty, ocost, cid, odeliverydate, ostatus) 
                    VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiidisi", $oid, $pid, $oqty, $ocost, $cid, $odeliverydate, $ostatus);
            if ($stmt->execute()) {
                header("Location: ViewOrder.php");
                exit;
            } else {
                $error = "订单保存失败，请重试。";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>添加订单</title>
</head>
<body>
    <div class="navbar">
        <ul>
            <li><a href="index.html">主页</a></li>
            <li><a href="productList.html">产品列表</a></li>
            <li><a href="CustomerBuy.php">购买</a></li>
            <li><a href="profile.php">客户资料</a></li>
            <li><a href="ViewOrder.php">查看订单</a></li>
        </ul>
    </div>

    <main>
        <h1>添加订单</h1>
        <div class="card">
            <?php if (!empty($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="POST">
                <table>
                    <tr>
                        <th>订单编号</th>
                        <td><?= htmlspecialchars($oid) ?></td>
                    </tr>
                    <tr>
                        <th>客户编号</th>
                        <td><?= htmlspecialchars($cid) ?></td>
                    </tr>
                    <tr>
                        <th>产品名称</th>
                        <td><?= htmlspecialchars($pname) ?></td>
                    </tr>
                    <tr>
                        <th>产品价格</th>
                        <td><?= htmlspecialchars($pcost) ?></td>
                    </tr>
                    <tr>
                        <th>订单数量</th>
                        <td>
                            <input type="number" name="oqty" id="oqty" min="1" required
                                   oninput="calculateCost()" value="<?= isset($oqty) ? htmlspecialchars($oqty) : 1 ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>订单总价</th>
                        <td id="ocost"><?= isset($oqty) ? htmlspecialchars($pcost * $oqty) : $pcost ?></td>
                    </tr>
                    <tr>
                        <th>交货日期</th>
                        <td>
                            <input type="datetime-local" name="odeliverydate" required>
                        </td>
                    </tr>
                    <tr>
                        <th>订单状态</th>
                        <td>Open</td>
                    </tr>
                </table>
                <button type="submit">提交订单</button>
            </form>
        </div>
    </main>

    <script>
        function calculateCost() {
            const oqty = document.getElementById('oqty').value;
            const pcost = <?= $pcost ?>;
            const ocost = document.getElementById('ocost');
            if (oqty > 0) {
                ocost.textContent = (pcost * oqty).toFixed(2);
            } else {
                ocost.textContent = "0.00";
            }
        }
    </script>
</body>
</html>