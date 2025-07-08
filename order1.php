<?php
session_start();
$conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if `cid` exists in cookies and validate it against the database
if (!isset($_COOKIE['cid'])) {
    header("Location: CustomerLogin.php");
    exit();
}
$cid = $_COOKIE['cid'];
$cid_query = "SELECT * FROM customer WHERE cid = $cid";
$cid_result = $conn->query($cid_query);
if ($cid_result->num_rows === 0) {
    header("Location: CustomerLogin.php");
    exit();
}

// Reset cookie
setcookie("cid", $cid, time() + 3600);

// Get `pid` from URL
if (!isset($_GET['pid'])) {
    die("Product ID not provided.");
}
$pid = $_GET['pid'];

// Fetch product price for real-time cost calculation
$product_query = "SELECT pcost FROM product WHERE pid = $pid";
$product_result = $conn->query($product_query);
if ($product_result->num_rows === 0) {
    die("Invalid Product ID.");
}
$product = $product_result->fetch_assoc();
$pcost = $product['pcost'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $oqty = $_POST['oqty'];
    $odeliverdate = $_POST['odeliverdate'];

    // Validate quantity and delivery date
    if ($oqty <= 0) 
    {
        $error_message = "Order quantity must be greater than 0.";
    } elseif (strtotime($odeliverdate) <= time()) 
    {
        $error_message = "Delivery date must be in the future.";
    } else 
    {
        // Calculate cost
        $ocost = $oqty * $pcost;
        $odate = date('Y-m-d H:i:s');

        // Generate unique `oid`
        do 
        {
            $oid = rand(1000, 9999);
            $oid_query = "SELECT * FROM orders WHERE oid = $oid";
            $oid_result = $conn->query($oid_query);
        } while ($oid_result->num_rows > 0);

        // Insert order into database
        $insert_query = "INSERT INTO orders (oid, odate, pid, oqty, ocost, cid, odeliverdate, ostatus)
                         VALUES ($oid, '$odate', $pid, $oqty, $ocost, $cid, '$odeliverdate', 1)";
        if ($conn->query($insert_query) === TRUE) 
        {
            header("Location: ViewOrder.php");
            exit();
        } 
        else 
        {
            $error_message = "Failed to place order: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function calculateCost() 
        {
            const oqty = document.getElementById('oqty').value;
            const pcost = <?= $pcost ?>;
            const ocost = oqty > 0 ? (oqty * pcost).toFixed(2) : 0;
            document.getElementById('ocost').value = ocost;
        }
    </script>
</head>
<body>
    <div class="navbar">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="CustomerBuy.php">Buy</a></li>
            <li><a href="ViewOrder.php">Orders</a></li>
        </ul>
    </div>

    <main>
        <h1>Place Your Order</h1>
        <div class="card">
            <?php if (isset($error_message)): ?>
                <p style="color: red;"><?= $error_message ?></p>
            <?php endif; ?>
            <form method="POST" action="order1.php?pid=<?= $pid ?>">
                <label for="oid">Order ID:</label>
                <input type="text" id="oid" name="oid" value="Auto-generated" disabled>

                <label for="odate">Order Date:</label>
                <input type="text" id="odate" name="odate" value="<?= date('Y-m-d H:i:s') ?>" disabled>

                <label for="pid">Product ID:</label>
                <input type="text" id="pid" name="pid" value="<?= $pid ?>" disabled>

                <label for="cid">Customer ID:</label>
                <input type="text" id="cid" name="cid" value="<?= $cid ?>" disabled>

                <label for="oqty">Order Quantity:</label>
                <input type="number" id="oqty" name="oqty" oninput="calculateCost()" required>

                <label for="ocost">Order Cost:</label>
                <input type="text" id="ocost" name="ocost" value="0.00" disabled>

                <label for="odeliverdate">Delivery Date:</label>
                <input type="datetime-local" id="odeliverdate" name="odeliverdate" required>

                <button type="submit">Confirm Order</button>
            </form>
        </div>
    </main>
</body>
</html>

<?php
$conn->close();
?>