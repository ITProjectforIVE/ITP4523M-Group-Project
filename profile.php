<?php
session_start();

// Check if "cid" Cookie exists
if (!isset($_COOKIE['cid'])) {
    // Redirect to login page if "cid" is not found
    header("Location: CustomerLogin.php");
    exit;
}

// Get "cid" value
$cid = $_COOKIE['cid'];

// Reset Cookie time to 1 hour
setcookie('cid', $cid, time() + 3600, '/');

// Database connection
$conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query customer information by "cid"
$sql = "SELECT cname, cpassword, ctel, caddr, company FROM customer WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

// If customer does not exist, redirect to login page
if (!$customer) {
    header("Location: CustomerLogin.php");
    exit;
}

// Close database connection
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
        // Toggle password visibility
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

        // Show success message
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
            <!-- Form submission -->
            <form id="profile-form" action="profile.php" method="post" onsubmit="showSuccessMessage();">
                <label for="cname">Customer Name:</label>
                <!-- Lock this field -->
                <input type="text" id="cname" name="cname" value="<?= htmlspecialchars($customer['cname']) ?>" readonly>
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
                <!-- Lock this field -->
                <input type="text" id="company" name="company" value="<?= htmlspecialchars($customer['company']) ?>" readonly>
                <br />

                <button type="submit">Confirm</button>
            </form>
        </div>
    </main>

    <?php
    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get submitted data
        $cpassword = $_POST['cpassword'];
        $ctel = $_POST['ctel'];
        $caddr = $_POST['caddr'];

        // Database connection
        $conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Update customer information (only password, telephone, and address)
        $sql = "UPDATE customer SET cpassword = ?, ctel = ?, caddr = ? WHERE cid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $cpassword, $ctel, $caddr, $cid);
        $stmt->execute();

        // Close database connection
        $conn->close();

        // Redirect to CustomerBuy.php
        header("Location: CustomerBuy.php");
        exit;
    }
    ?>
</body>
</html>