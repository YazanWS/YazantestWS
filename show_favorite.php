<!DOCTYPE html>
<html>
<head>
    <title>Your Favorite Car</title>
</head>
<body>
    <h2>Find your favorite car entry</h2>
    <form method="POST">
        <label for="name">Enter your name:</label>
        <input type="text" id="name" name="name" required>
        <input type="submit" value="Show My Favorite Car">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars($_POST["name"]);

        $server = "localhost";
        $username = "yazan";
        $password = "Mango3990";
        $database = "autodb";

        $conn = mysqli_connect($server, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT car FROM favoriteauto WHERE name = '$name' ORDER BY id DESC LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            echo "<p>Your favorite car is: <strong>" . htmlspecialchars($row['car']) . "</strong></p>";
        } else {
            echo "<p>No entry found for that name.</p>";
        }

        mysqli_close($conn);
    }
    ?>
</body>
</html>