
<!DOCTYPE html>
<html>
<head>
    <title>Google</title>
</head>
<body>
    <h1>Google Search</h1>
    <form method="POST">
        <label for="search">Search Google or Type URL</label>
        <input type="text" id="search" name="search" required>
        <input type="submit" value="search">
    </form>  
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ip = $_SERVER["REMOTE_ADDR"];
        $search = htmlspecialchars($_POST["search"]);

        $server = "localhost";
        $username = "yazan";
        $password = "Mango3990";
        $database = "g00gle";

        $conn = mysqli_connect($server, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "INSERT INTO searchinfo (IP, search) VALUES ('$ip', '$search');";
        mysqli_query($conn, $sql);
      
        echo "<p>Your search is: <strong>" . $search . "</strong></p>";
        echo "<p>Your IP address is: <strong>" . $ip . "</strong></p>";
    
        redirect: header("Location: https://www.google.com/search?q=" . urlencode($search));
        exit();
    }
    ?>
</body>
</html>