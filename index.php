<?php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Telemetry Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #111827;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            background: #1f2937;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            width: 420px;
        }

        h1 {
            margin-bottom: 20px;
        }

        p {
            color: #d1d5db;
            margin-bottom: 30px;
        }

        .btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 14px 28px;
            font-size: 18px;
            border-radius: 10px;
            cursor: pointer;
        }

        .btn:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vehicle Telemetry Dashboard</h1>
        <p>Press start to view live speed, temperature, and voltage.</p>
        <form action="dashboard.php" method="get">
            <button class="btn" type="submit">Start</button>
        </form>
    </div>
</body>
</html>