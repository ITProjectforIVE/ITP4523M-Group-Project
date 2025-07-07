<?php
// Database Connection
$conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch material details
$materialID = intval($_GET['mid']);
$materialQuery = "SELECT * FROM material WHERE mid = ?";
$stmt = $conn->prepare($materialQuery);
$stmt->bind_param('i', $materialID);
$stmt->execute();
$result = $stmt->get_result();
$material = $result->fetch_assoc();

if (!$material) {
    die("Material not found.");
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

    // Update material in the database (excluding `mid`)
    $updateQuery = "UPDATE material SET mname = ?, mqty = ?, mrqty = ?, munit = ?, mreorderqty = ? WHERE mid = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('siisii', $materialName, $physicalQty, $reservedQty, $unit, $reorderLevel, $materialID);
    $stmt->execute();
    echo "<script>alert('Material updated successfully!'); window.location.href='Insert_Material.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Update Material</title>
</head>
<body>
    <main>
        <h3>Update Material</h3>
        <div class="card">
            <form method="POST">
                <!-- Material ID (Read-Only) -->
                <label>Material ID:</label>
                <input type="text" value="<?= htmlspecialchars($material['mid']) ?>" readonly><br>

                <!-- Material Name -->
                <label>Material Name:</label>
                <input type="text" name="materialName" value="<?= htmlspecialchars($material['mname']) ?>" required><br>

                <!-- Physical Quantity -->
                <label>Physical Quantity:</label>
                <input type="number" name="physicalQty" value="<?= htmlspecialchars($material['mqty']) ?>" required><br>

                <!-- Reserved Quantity -->
                <label>Reserved Quantity:</label>
                <input type="number" name="reservedQty" value="<?= htmlspecialchars($material['mrqty']) ?>" required><br>

                <!-- Unit -->
                <label>Unit:</label>
                <input type="text" name="unit" value="<?= htmlspecialchars($material['munit']) ?>" required><br>

                <!-- Reorder Level -->
                <label>Reorder Level:</label>
                <input type="number" name="reorderLevel" value="<?= htmlspecialchars($material['mreorderqty']) ?>" required><br>

                <!-- Submit Button -->
                <button type="submit">Update</button>
            </form>
        </div>
    </main>
</body>
</html>