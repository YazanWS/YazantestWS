<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ip = $_SERVER["REMOTE_ADDR"];
    $search = htmlspecialchars($_POST["search"]);

    // Database connection
    $server = "localhost";
    $username = "yazan";
    $password = "Mango3990";
    $database = "g00gle";
    $conn = mysqli_connect($server, $username, $password, $database);

    if ($conn) {
        $sql = "INSERT INTO searchinfo (IP, search) VALUES ('$ip', '$search');";
        mysqli_query($conn, $sql);
        mysqli_close($conn);
    }

    // Redirect to Google search
    header("Location: https://www.google.com/search?q=" . urlencode($search));
    exit();
}
?>