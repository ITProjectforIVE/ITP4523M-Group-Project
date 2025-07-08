<?php
// Start the PHP session to handle cookies
session_start();

// Check if the user has an existing `cid` cookie
if (isset($_COOKIE['cid'])) {
    // Database connection
    $conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Verify if the `cid` in the cookie exists in the database
    $cid = $_COOKIE['cid'];
    $sql = "SELECT * FROM customer WHERE cid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $cid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // If the customer exists, redirect to the next page
        header("Location: productList.html");
        exit;
    }

    // Close the database connection
    $conn->close();
}

// Handle login functionality via POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Database connection failed']));
    }

    // Fetch input data
    $customer_name = $_POST['customer_name'];
    $password = $_POST['password'];

    // Validate input
    if (empty($customer_name) || empty($password)) {
        die(json_encode(['success' => false, 'message' => 'Please fill out all fields']));
    }

    // Check credentials
    $sql = "SELECT * FROM customer WHERE cname = ? AND cpassword = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $customer_name, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $customer = $result->fetch_assoc();
        // Set a cookie for the customer ID (expires in 1 hour)
        setcookie('cid', $customer['cid'], time() + 3600, '/');
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid customer name or password']);
    }

    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style.css">
    <title>Customer Login</title>
</head>
<body>
    <div class="navbar">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="productList.html">Product</a></li>
        </ul>
    </div>

    <main>
        <h1>Customer Login</h1>
        <div class="card">
            <form id="login-form">
                <label for="customer_name">Customer Name:</label>
                <input type="text" id="customer_name" name="customer_name" placeholder="Enter your name" required>
                <br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <br>

                <button type="button" id="login-button">Login</button>
                <br><br>
                <button type="button" id="create-account-button">Create Account</button>
            </form>
        </div>
    </main>

    <script>
        // Handle login via AJAX
        document.getElementById('login-button').addEventListener('click', function () {
            const customerName = document.getElementById('customer_name').value;
            const password = document.getElementById('password').value;

            if (!customerName || !password) {
                alert('Please fill out all fields!');
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'CustomerLogin.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert(response.message);
                        window.location.href = 'productList.html'; // Redirect to product page on success
                    } else {
                        alert(response.message);
                    }
                } else {
                    alert('An error occurred. Please try again.');
                }
            };

            // Send the login request
            xhr.send(`customer_name=${encodeURIComponent(customerName)}&password=${encodeURIComponent(password)}`);
        });

        // Handle "Create Account" button
        document.getElementById('create-account-button').addEventListener('click', function () {
            window.location.href = 'create_order.php'; // Redirect to account creation page
        });
    </script>
</body>
</html>