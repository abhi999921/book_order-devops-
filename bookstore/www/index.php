<?php
session_start(); // Start the session at the beginning

// Database connection parameters
$servername = "db";
$username = "abhishek";
$password = "abhishek";
$dbname = "bookstore";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'ac' form is submitted (add to cart)
if (isset($_POST['ac'])) {
    $bookID = $_POST['ac'];
    $quantity = $_POST['quantity'];

    // Retrieve book details from the database
    $sql = "SELECT * FROM book WHERE BookID = '$bookID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $price = $row['Price'];

        // Insert the selected book into the cart
        $sql = "INSERT INTO cart (BookID, Quantity, Price, TotalPrice) VALUES ('$bookID', $quantity, $price, $price * $quantity)";
        $conn->query($sql);
    }
}

// Check if the 'delc' form is submitted (delete cart)
if (isset($_POST['delc'])) {
    // Empty the cart by deleting all records
    $sql = "DELETE FROM cart";
    $conn->query($sql);
}

// Retrieve books from the database
$sql = "SELECT * FROM book";
$result = $conn->query($sql);
?>

<html>
<meta http-equiv="Content-Type"'.' content="text/html; charset=utf8"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style.css">
<body>

<?php
// Display the header based on session status
echo '<header>';
echo '<blockquote>';
echo '<a href="index.php"><img src="image/logo.png"></a>';

if (isset($_SESSION['id'])) {
    echo '<form class="hf" action="logout.php"><input class="hi" type="submit" name="submitButton" value="Logout"></form>';
    echo '<form class="hf" action="edituser.php"><input class="hi" type="submit" name="submitButton" value="Edit Profile"></form>';
} else {
    echo '<form class="hf" action="Register.php"><input class="hi" type="submit" name="submitButton" value="Register"></form>';
    echo '<form class="hf" action="login.php"><input class="hi" type="submit" name="submitButton" value="Login"></form>';
}

echo '</blockquote>';
echo '</header>';
echo '<blockquote>';

// Display the book details and add to cart form
echo "<table id='myTable' style='width:80%; float:left'>";
while ($row = $result->fetch_assoc()) {
    echo "<td>";
    echo "<table>";
    echo '<tr><td>'.'<img src="'.$row["Image"].'"width="80%">'.'</td></tr><tr><td style="padding: 5px;">Title: '.$row["BookTitle"].'</td></tr><tr><td style="padding: 5px;">ISBN: '.$row["ISBN"].'</td></tr><tr><td style="padding: 5px;">Author: '.$row["Author"].'</td></tr><tr><td style="padding: 5px;">Type: '.$row["Type"].'</td></tr><tr><td style="padding: 5px;">Rs '.$row["Price"].'</td></tr><tr><td style="padding: 5px;">
        <form action="" method="post">
        Quantity: <input type="number" value="1" name="quantity" style="width: 20%"/><br>
        <input type="hidden" value="'.$row['BookID'].'" name="ac"/>
        <input class="button" type="submit" value="Add to Cart"/>
        </form></td></tr>';
    echo "</table>";
    echo "</td>";
}
echo "</table>";

// Retrieve cart items from the database
$sql = "SELECT book.BookTitle, book.Image, cart.Price, cart.Quantity, cart.TotalPrice FROM book, cart WHERE book.BookID = cart.BookID";
$result = $conn->query($sql);

// Display the cart details
echo "<table style='width:20%; float:right;'>";
echo "<th style='text-align:left;'><i class='fa fa-shopping-cart' style='font-size:24px'></i> Cart <form style='float:right;' action='' method='post'><input type='hidden' name='delc'/><input class='cbtn' type='submit' value='Empty Cart'></form></th>";
$total = 0;
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>";
    echo '<img src="'.$row["Image"].'"width="20%"><br>';
    echo $row['BookTitle']."<br>Rs ".$row['Price']."<br>";
    echo "Quantity: ".$row['Quantity']."<br>";
    echo "Total Price: Rs ".$row['TotalPrice']."</td></tr>";
    $total += $row['TotalPrice'];
}
echo "<tr><td style='text-align: right;background-color: #f2f2f2;'>";
echo "Total: <b>Rs ".$total."</b><center><form action='checkout.php' method='post'><input class='button' type='submit' name='checkout' value='CHECKOUT'></form></center>";
echo "</td></tr>";
echo "</table>";

echo '</blockquote>';
?>

</body>
</html>
