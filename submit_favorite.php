<?php
<!DOCTYPE html>
<html>
<head>
    <title>Form response</title>
</head>
<?php
    // Retrieve submitted information
    $favorite_car = htmlspecialchars($_POST["favoriteCar"]);

    // Database connection settings
    $server = "localhost";
    $username = "yazan";
    $password = "Mango3990";
    $database = "autodb";

    $conn = mysqli_connect($server, $username, $password, $database);

    // Check for successful connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert into favoriteauto table
    $sql = "INSERT INTO favoriteauto (car) VALUES ('$favorite_car');";
    $result = mysqli_query($conn, $sql);
?>
<body>
    <p>My favorite car is <?= $favorite_car ?></p>
</body>
</html>