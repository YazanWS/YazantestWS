<!DOCTYPE html>
<html>
<head>
    <title>Search Result</title>
</head>
<body>
    <h1>Search Result</h1>
    <?php
    $search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
    $ip = isset($_GET['ip']) ? htmlspecialchars($_GET['ip']) : '';
    ?>
    <p>Your search is: <strong><?php echo $search; ?></strong></p>
    <p>Your IP address is: <strong><?php echo $ip; ?></strong></p>
    <a href="g00gle.php">Back to search</a>
</body>
</html>