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
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #0a0a0c;
            --bg-panel: rgba(25, 25, 28, 0.85);
            --bg-card: rgba(35, 35, 38, 0.90);
            --border: rgba(255, 255, 255, 0.08);
            --text-main: #ffffff;
            --text-soft: #a1a1aa;
            --accent-red: #ff2d2d;
            --accent-red-soft: rgba(255, 45, 45, 0.12);
            --shadow: 0 12px 40px rgba(0, 0, 0, 0.60);
            --glow: 0 0 18px rgba(255, 45, 45, 0.35);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: center;
            background:
                radial-gradient(circle at top center, rgba(255, 45, 45, 0.12), transparent 30%),
                radial-gradient(circle at bottom right, rgba(255, 45, 45, 0.08), transparent 28%),
                linear-gradient(160deg, #050505 0%, #0d0d10 45%, #050505 100%);
            overflow: hidden;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                repeating-linear-gradient(
                    90deg,
                    rgba(255,255,255,0.02) 0px,
                    rgba(255,255,255,0.02) 1px,
                    transparent 1px,
                    transparent 80px
                ),
                repeating-linear-gradient(
                    0deg,
                    rgba(255,255,255,0.015) 0px,
                    rgba(255,255,255,0.015) 1px,
                    transparent 1px,
                    transparent 80px
                );
            pointer-events: none;
            opacity: 0.35;
        }

        .container {
            width: min(460px, calc(100% - 30px));
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 42px 34px;
            text-align: center;
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 24px;
            right: 24px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 45, 45, 0.5), transparent);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            margin-bottom: 22px;
            border-radius: 999px;
            background: rgba(255, 45, 45, 0.10);
            border: 1px solid rgba(255, 45, 45, 0.25);
            box-shadow: var(--glow);
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .badge::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--accent-red);
            box-shadow: 0 0 10px rgba(255, 45, 45, 0.9);
        }

        h1 {
            margin: 0 0 16px;
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(28px, 4vw, 36px);
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            line-height: 1.2;
            text-shadow: 0 0 14px rgba(255, 45, 45, 0.20);
        }

        p {
            margin: 0 0 30px;
            color: var(--text-soft);
            font-size: 15px;
            line-height: 1.6;
        }

        .info-card {
            background: linear-gradient(180deg, rgba(34, 34, 37, 0.96), rgba(16, 16, 18, 0.96));
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 22px;
            padding: 18px 16px;
            margin-bottom: 28px;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.04), 0 10px 24px rgba(0,0,0,0.30);
            position: relative;
        }

        .info-card::after {
            content: "";
            position: absolute;
            inset: auto 18px 0 18px;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, transparent, rgba(255,45,45,0.7), transparent);
            opacity: 0.75;
        }

        .info-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 13px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #ff6b6b;
            margin-bottom: 10px;
        }

        .info-text {
            color: #f4f4f5;
            font-size: 14px;
            line-height: 1.6;
        }

        form {
            margin: 0;
        }

        .btn {
            min-width: 220px;
            border: 1px solid rgba(255,255,255,0.08);
            padding: 16px 30px;
            border-radius: 16px;
            cursor: pointer;
            font-family: 'Orbitron', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: #ffffff;
            background: linear-gradient(135deg, #b91c1c, #ff2d2d);
            box-shadow: 0 0 18px rgba(255,45,45,0.28), 0 12px 26px rgba(0,0,0,0.32);
            transition: all 0.25s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 22px rgba(255,45,45,0.38), 0 14px 30px rgba(0,0,0,0.34);
        }

        .btn:active {
            transform: translateY(0);
        }

        @media (max-width: 600px) {
            .container {
                padding: 32px 22px;
                border-radius: 22px;
            }

            h1 {
                font-size: 26px;
            }

            .btn {
                width: 100%;
                min-width: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="badge">System Ready</div>

        <h1>Car Dashboard</h1>

        <p>Start the live monitoring to see real-time speed, temperature, voltage, and dashcam footage.</p>

        <div class="info-card">
            <div class="info-title">Live Session</div>
            <div class="info-text">
                Press start to open the real-time dashboard.
            </div>
        </div>

        <form action="start_session.php" method="post">
            <button class="btn" type="submit">Start</button>
        </form>
    </div>
</body>
</html>