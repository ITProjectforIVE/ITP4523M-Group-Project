<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login Page</title>
</head>
<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('message').textContent = data.message;
            if (data.success) {
                // Redirect to another page or perform another action
                window.location.href = 'dashboard.html';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>
<body>
    <div class="navbar">
        <ul>
        <li><a  href="index.html">Home</a></li>
        <li><a  href="productList.html">Product</a></li>
        <li><a  href="CustomerBuy.html">Buy</a></li>
        <li><a  href="customer.html">Customer</a></li>
        <li><a  href="ViewOrder.html">Order</a></li>
        </ul>  
    </div>
    <main>
    <div class="card">
        <h2>Login</h2>
        <form id="loginForm">
            <input type="text" id="username" placeholder="Username" required>
            <br />
            <input type="password" id="password" placeholder="Password" required>
            <br />
            <button type="submit">Login</button>
        </form>
        <div id="message"></div>
    </div>
</main>
</body>
</html>
<?php
$hostname = "127.0.0.1";
$database = "projectDB";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: application/json');

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'];
$password = $data['password'];

$response = ['success' => false, 'message' => 'Invalid username or password.'];

// Prepare and bind
$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    
    // Verify password (assuming passwords are hashed)
    if (password_verify($password, $hashed_password)) {
        // Set a cookie for the user session
        setcookie('user_status', 'logged_in', time() + (86400 * 30), "/"); // 30 days
        $response = ['success' => true, 'message' => 'Login successful!'];
    }
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>