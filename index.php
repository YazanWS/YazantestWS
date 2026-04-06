<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telemetry Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #0a0a0c;
            --panel: #1a1a1d;
            --panel-border: rgba(255, 255, 255, 0.08);
            --text-main: #ffffff;
            --text-soft: #b3b3ba;
            --accent-red: #ff2d2d;
            --accent-red-dark: #c81f1f;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.45);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top, rgba(255, 45, 45, 0.10), transparent 30%),
                linear-gradient(160deg, #050505 0%, #0d0d10 50%, #050505 100%);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            padding: 20px;
        }

        .container {
            background: var(--panel);
            padding: 40px;
            border-radius: 18px;
            border: 1px solid var(--panel-border);
            box-shadow: var(--shadow);
            width: 420px;
            max-width: 100%;
        }

        h1 {
            margin: 0 0 18px;
            font-family: 'Orbitron', sans-serif;
            font-size: 30px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        p {
            color: var(--text-soft);
            margin: 0 0 30px;
            line-height: 1.6;
            font-size: 15px;
        }

        .btn {
            background: linear-gradient(135deg, var(--accent-red-dark), var(--accent-red));
            color: white;
            border: none;
            padding: 14px 32px;
            font-size: 16px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 0 16px rgba(255, 45, 45, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(255, 45, 45, 0.32);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vehicle Telemetry Dashboard</h1>
        <p>Press start to view live speed, temperature, and voltage.</p>
        <form action="start_session.php" method="post">
            <button class="btn" type="submit">Start</button>
        </form>
    </div>
</body>
</html>