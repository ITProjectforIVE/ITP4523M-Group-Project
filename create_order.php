<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./style/style.css">
    <title>Create Order</title>
</head>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('get_products.php')
        .then(response => response.json())
        .then(data => {
            const productList = document.getElementById('productList');
            data.sort((a, b) => a.price - b.price); // Sort by price

            data.forEach(product => {
                if (product.stock_quantity > 0) {
                    const item = document.createElement('div');
                    item.innerHTML = `<strong>${product.product_name}</strong> - $${product.price} (${product.stock_quantity} in stock)`;
                    productList.appendChild(item);
                }
            });
        });
});
</script>
<body>
<div class="navbar">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="productList.html">Product</a></li>
        </ul>
    </div>
    <main>
    <div class="card">
    <form id="orderForm" action="create_order.php" method="POST">
        <h2>Create Order</h2>
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br>

        <label for="customer_password">Password:</label>
        <input type="password" id="customer_password" name="customer_password" required><br>

        <label for="customer_telephone">Telephone:</label>
        <input type="text" id="customer_telephone" name="customer_telephone" required><br>

        <label for="customer_address">Address:</label>
        <input type="text" id="customer_address" name="customer_address" required><br>

        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" required><br>

        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br>

        <button type="submit">Submit Order</button>
    </form>
    <?php
$hostname = "localhost";
$database = "projectDB";
$username = "root";
$password = "";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products WHERE stock_quantity > 0";
$result = $conn->query($sql);

$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
</div>
</main>
</body>
</html>