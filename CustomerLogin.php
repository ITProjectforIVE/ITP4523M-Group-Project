<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login Page</title>
</head>
<script src="jquery/jquery-2.1.4.js"></script>
    <script>
        $(document).ready(function() {
            $('#btnResult').click(function() {
                var sname = $('#username').val();
                var spassword = $('#password').val();
                $.ajax({
                    type:"post",
                    url: 'CustomerLogin.php',
                    dataType: 'json',
                    success: function(result) {
                        var message ="";
                        found = false;
                        for(var i=0; i<result.length; i++) {
                            if(result[i].sname == sname && result[i].spassword == spassword) {
                                found = true;
                                result = result[i].sresult;
                            }                                   
                        }               
                        if(found){
                            alert("Hello! " + sname + ", your result is " + result);
                        }
                        else {
                          alert("Invalid name and password."); 
                         } 
                        }                                   
                    })
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
        <form id="loginForm" action="customerLogin.php" method="POST">
            <input type="text" id="username" placeholder="Username" required>
            <br />
            <input type="password" id="password" placeholder="Password" required>
            <br />
            <button type="submit" name="submit" value="submit">Login</button>
        </form>
        <?php
$hostname = "127.0.0.1";
$database = "projectDB";
$username = "root";
$password = "";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM customer";
$result = $conn->query($sql);

$customer = [];
if ($result) {
    while ($rc = $result->fetch_assoc()) {
        $customer[] = $rc;
    }
    echo json_encode($customer);
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
        <div id="message"></div>
    </div>
</main>
</body>
</html>
