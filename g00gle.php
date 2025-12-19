<!DOCTYPE html>
<html>
<head>
    <title>Google</title>
    <body>
        <h1>Google Search</h1>
        <form method="POST">
        <label for="search">Search Google or Type URL</label>
        <input type="text" id="search" name="search" required>
        <input type="submit" value="Search">
    </form>
      <script>
        <?php
        $ip = $_SERVER["REMOTE_ADDR"];
            
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search = htmlspecialchars($_POST["search"]);
        }
        $server = "localhost";
        $username = "yazan";
        $password = "Mango3990";
        $database = "g00gle";

        $conn = mysqli_connect($server, $username, $password, $database);
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
         $sql = "INSERT INTO searchinfo (IP, search) VALUES ('$ip', '$search');"
        ?>
        </script> 
    </body>
</html>