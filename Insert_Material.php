<?php
// Database Connection
$conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch materials
$materialsQuery = "SELECT * FROM material";
$materialsResult = $conn->query($materialsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Material Management</title>
</head>
<body>
    <div class="navbar">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="Staff.html">Staff</a></li>
            <li><a href="order.html">Order</a></li>
            <li><a href="customer.html">Customer</a></li>
            <li><a href="Report.html">Report</a></li>
        </ul>
    </div>

    <main>
        <h3>Material Management</h3>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Material ID</th>
                        <th>Material Name</th>
                        <th>Physical Quantity</th>
                        <th>Reserved Quantity</th>
                        <th>Unit</th>
                        <th>Reorder Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($materialsResult->num_rows > 0): ?>
                        <?php while ($row = $materialsResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['mid']) ?></td>
                                <td><?= htmlspecialchars($row['mname']) ?></td>
                                <td><?= htmlspecialchars($row['mqty']) ?></td>
                                <td><?= htmlspecialchars($row['mrqty']) ?></td>
                                <td><?= htmlspecialchars($row['munit']) ?></td>
                                <td><?= htmlspecialchars($row['mreorderqty']) ?></td>
                                <td>
                                    <a href="Update_Material.php?mid=<?= $row['mid'] ?>">
                                        <button>Update</button>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No materials found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="Add_Material.php">
                <button>Add Material</button>
            </a>
        </div>
    </main>
</body>
</html>