<?php
<!DOCTYPE html>
<html>
<head>
    <title>Form response</title>
</head>
<?php
    $name = htmlspecialchars($_POST["name"]);
    $favorite_car = htmlspecialchars($_POST["favoriteCar"]);

    $server = "localhost";
    $username = "yazan";
    $password = "Mango3990";
    $database = "autodb";

    $conn = mysqli_connect($server, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO favoriteauto (name, car) VALUES ('$name', '$favorite_car');";
    $result = mysqli_query($conn, $sql);
?>
<body>
    <p>Thank you, <?= $name ?>! Your favorite car is <?= $favorite_car ?>.</p>
</body>
</html>