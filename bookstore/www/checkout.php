<?php
session_start(); // Start the session at the very beginning

if(isset($_SESSION['id'])){
    $servername = "db";
    $username = "abhishek";
    $password = "abhishek";

    $conn = new mysqli($servername, $username, $password); 

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "USE bookstore";
    $conn->query($sql);

    $sql = "SELECT CustomerID from customer WHERE UserID = ".$_SESSION['id']."";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
        $cID = $row['CustomerID'];
    }

    $sql = "UPDATE cart SET CustomerID = ".$cID." WHERE 1";
    $conn->query($sql);

    $sql = "SELECT * FROM cart";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
        $sql = "INSERT INTO `order`(CustomerID, BookID, DatePurchase, Quantity, TotalPrice, Status) 
        VALUES(".$row['CustomerID'].", '".$row['BookID']
        ."', CURRENT_TIME, ".$row['Quantity'].", ".$row['TotalPrice'].", 'N')";
        $conn->query($sql);
    }
    $sql = "DELETE FROM cart";
    $conn->query($sql);

    $sql = "SELECT customer.CustomerName, customer.CustomerIC, customer.CustomerGender, customer.CustomerAddress, customer.CustomerEmail, customer.CustomerPhone, book.BookTitle, book.Price, book.Image, `order`.`DatePurchase`, `order`.`Quantity`, `order`.`TotalPrice`
        FROM customer, book, `order`
        WHERE `order`.`CustomerID` = customer.CustomerID AND `order`.`BookID` = book.BookID AND `order`.`Status` = 'N' AND `order`.`CustomerID` = ".$cID."";
    $result = $conn->query($sql);
    echo '<div class="container">';
    echo '<blockquote>';
?>
<input class="button" style="float: right;" type="button" name="cancel" value="Continue Shopping" onClick="window.location='index.php';" />
<?php
    echo '<h2 style="color: #000;">Order Successful</h2>';
    echo "<table style='width:100%'>";
    echo "<tr><th>Order Summary</th>";
    echo "<th></th></tr>";
    $row = $result->fetch_assoc();
    echo "<tr><td>Name: </td><td>".$row['CustomerName']."</td></tr>";
    echo "<tr><td>No.Number: </td><td>".$row['CustomerIC']."</td></tr>";
    echo "<tr><td>E-mail: </td><td>".$row['CustomerEmail']."</td></tr>";
    echo "<tr><td>Mobile Number: </td><td>".$row['CustomerPhone']."</td></tr>";
    echo "<tr><td>Gender: </td><td>".$row['CustomerGender']."</td></tr>";
    echo "<tr><td>Address: </td><td>".$row['CustomerAddress']."</td></tr>";
    echo "<tr><td>Date: </td><td>".$row['DatePurchase']."</td></tr>";

    $sql = "SELECT customer.CustomerName, customer.CustomerIC, customer.CustomerGender, customer.CustomerAddress, customer.CustomerEmail, customer.CustomerPhone, book.BookTitle, book.Price, book.Image, `order`.`DatePurchase`, `order`.`Quantity`, `order`.`TotalPrice`
        FROM customer, book, `order`
        WHERE `order`.`CustomerID` = customer.CustomerID AND `order`.`BookID` = book.BookID AND `order`.`Status` = 'N' AND `order`.`CustomerID` = ".$cID."";
    $result = $conn->query($sql);
    $total = 0;
    while($row = $result->fetch_assoc()){
        echo "<tr><td style='border-top: 2px solid #ccc;'>";
        echo '<img src="'.$row["Image"].'"width="20%"></td><td style="border-top: 2px solid #ccc;">';
        echo $row['BookTitle']."<br>Rs".$row['Price']."<br>";
        echo "Quantity: ".$row['Quantity']."<br>";
        echo "</td></tr>";
        $total += $row['TotalPrice'];
    }
    echo "<tr><td style='background-color: #ccc;'></td><td style='text-align: right;background-color: #ccc;'>Total Price: <b>Rs ".$total."</b></td></tr>";
    echo "</table>";
    echo "</div>";

    $sql = "UPDATE `order` SET Status = 'y' WHERE CustomerID = ".$cID."";
    $conn->query($sql);
}

$nameErr = $emailErr = $genderErr = $addressErr = $icErr = $contactErr = "";
$name = $email = $gender = $address = $ic = $contact = "";
$cID;

if(isset($_POST['submitButton'])){
    // Your form validation and checkout logic
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Checkout</title>
    <style> 
    /* Your CSS styles go here */
    </style>
</head>
<body>
    <!-- Your HTML content and form go here -->
</body>
</html>
