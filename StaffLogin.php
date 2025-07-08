<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
    $hostname = "127.0.0.1";
    $database = "projectDB";
    $username = "root";
    $password = "";

    // Create the database connection
    $conn = new mysqli($hostname, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get and sanitize the input
    $sname = trim($_POST['sname']);
    $spassword = trim($_POST['spassword']);

    // Prepare the SQL query to find the sid using sname and spassword
    $sql = "SELECT sid FROM staff WHERE sname = ? AND spassword = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $sname, $spassword); // Bind both fields
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a matching record exists
    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();

        // Save the sid in a cookie for 1 hour
        setcookie('sid', $staff['sid'], time() + 3600, '/');

        // Redirect to the staff dashboard
        header("Location: Staff-interface.html");
        exit;
    } else {
        $error = "Invalid username or password. Please try again.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Staff Login</title>
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
        <h1>Staff Login</h1>
        <div class="card">
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form action="StaffLogin.php" method="post">
                <label for="sname">Staff Name:</label>
                <input type="text" id="sname" name="sname" required>
                <br />

                <label for="spassword">Password:</label>
                <input type="password" id="spassword" name="spassword" required>
                <br />

                <button type="submit">Login</button>
            </form>
        </div>
    </main>
</body>
</html>