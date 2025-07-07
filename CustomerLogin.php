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
// Prepare and bind
$stmt = $conn->prepare("INSERT INTO customer (cname, cpassword, ctel, caddr, company) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssiss", $cname, $cpassword, $ctel, $caddr, $company);

// Set parameters and execute
$cname = $_POST['cname'];
$cpassword = $_POST['cpassword'];
$ctel = $_POST['ctel'] ? $_POST['ctel'] : null; // Handle optional phone
$caddr = $_POST['caddr'];
$company = $_POST['company'];

if ($stmt->execute()) {
    echo "New customer added successfully. <br>";
    // Set a cookie to record the user status
    setcookie("user", $cname, time() + (86400 * 30), "/"); // Cookie valid for 30 days
    echo "Cookie set for user: " . $cname;
} else {
    echo "Error: " . $stmt->error;
}

// Check if user can log in
if (isset($_POST['login'])) {
    $loginName = $_POST['loginName'];
    $loginPassword = $_POST['loginPassword'];
    
    // Check credentials
    $loginStmt = $conn->prepare("SELECT * FROM customer WHERE cname = ? AND cpassword = ?");
    $loginStmt->bind_param("ss", $loginName, $loginPassword);
    $loginStmt->execute();
    $result = $loginStmt->get_result();
    
    if ($result->num_rows > 0) {
        // Set cookie if login is successful
        setcookie("user", $loginName, time() + (86400 * 30), "/");
        echo "Login successful! Cookie set for user: " . $loginName;
    } else {
        echo "Invalid username or password.";
    }
}
// Close connection
$stmt->close();
$conn->close();
?>
</body>
</html>