<?php
// Database connection
$conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch product data from the database
$sql = "SELECT pid, pname, pdesc, pcost FROM product";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('Picture/Background.jpg');
            background-size: cover;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .navbar {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px 0;
            z-index: 1000;
        }
        .navbar ul {
            display: flex;
            justify-content: center;
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        .navbar ul li {
            margin: 0 15px;
        }
        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            transition: background-color 0.3s;
        }
        .navbar ul li a:hover {
            background-color: #444;
            border-radius: 5px;
        }

        table {
            width: 90%;
            margin: 100px auto 20px;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: rgba(0, 0, 0, 0.7);
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ccc;
            color: #fff;
        }
        table th {
            background-color: #333;
        }
        table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .make-order-btn {
            display: block;
            text-align: center;
            margin: 20px auto;
        }
        .make-order-btn button {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .make-order-btn button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="CustomerBuy.php">Buy</a></li>
            <li><a href="ViewOrder.php">Orders</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <main>
        <h1>Product List</h1>
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Product Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) 
                {
                    while ($row = $result->fetch_assoc()) 
                    {
                        echo "<tr>
                            <td>{$row['pid']}</td>
                            <td>{$row['pname']}</td>
                            <td>{$row['pdesc']}</td>
                            <td>{$row['pcost']}</td>
                        </tr>";
                    }
                } 
                else 
                {
                    echo "<tr><td colspan='4'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Make Order Button -->
        <div class="make-order-btn">
            <form action="CustomerBuy.php" method="GET">
                <button type="submit">Make Order</button>
            </form>
        </div>
    </main>
</body>
</html>

<?php
$conn->close();
?>