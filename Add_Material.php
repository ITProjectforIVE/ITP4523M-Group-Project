<?php
// Database Connection
$conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $materialName = $_POST['materialName'];
    $physicalQty = intval($_POST['physicalQty']);
    $reservedQty = intval($_POST['reservedQty']);
    $unit = $_POST['unit'];
    $reorderLevel = intval($_POST['reorderLevel']);

    // Check if reorder level exceeds physical quantity
    if ($reorderLevel > $physicalQty) {
        echo "<script>alert('Warning: Reorder level exceeds physical quantity. Please monitor stock levels.');</script>";
    }

    // Insert material into the database
    $insertQuery = "INSERT INTO material (mname, mqty, mrqty, munit, mreorderqty) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('siisi', $materialName, $physicalQty, $reservedQty, $unit, $reorderLevel);
    $stmt->execute();
    echo "<script>alert('Material added successfully!'); window.location.href='Insert_Material.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Add Material</title>
</head>
<body>
    <main>
        <h3>Add Material</h3>
        <div class="card">
            <form method="POST">
                <label>Material Name:</label>
                <input type="text" name="materialName" required><br>
                <label>Physical Quantity:</label>
                <input type="number" name="physicalQty" required><br>
                <label>Reserved Quantity:</label>
                <input type="number" name="reservedQty" required><br>
                <label>Unit:</label>
                <input type="text" name="unit" required><br>
                <label>Reorder Level:</label>
                <input type="number" name="reorderLevel" required><br>
                <button type="submit">Add</button>
            </form>
        </div>
    </main>
</body>
</html>