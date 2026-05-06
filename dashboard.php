<?php 
$cameraStreamUrl = "http://172.20.10.2:81/stream";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Dashboard</title>
 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
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
            --accent-green: #22c55e;
            --accent-orange: #ff8a00;
            --shadow: 0 12px 40px rgba(0, 0, 0, 0.60);
            --glow: 0 0 18px rgba(255, 45, 45, 0.35);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top center, rgba(255, 45, 45, 0.12), transparent 30%),
                radial-gradient(circle at bottom right, rgba(255, 45, 45, 0.08), transparent 28%),
                linear-gradient(160deg, #050505 0%, #0d0d10 45%, #050505 100%);
            overflow-x: hidden;
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
        .page {
            width: min(1450px, calc(100% - 40px));
            margin: 0 auto;
            padding: 28px 0 36px;
            position: relative;
            z-index: 1;
        }
        .topbar {
            background: rgba(10, 10, 12, 0.82);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 22px 28px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 24px;
            position: relative;
            z-index: 50;
        }
        .title-block h1 {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #ffffff;
            text-shadow: 0 0 14px rgba(255, 45, 45, 0.20);
        }
        .title-block p {
            margin: 8px 0 0;
            color: var(--text-soft);
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        .topbar-right {
            position: relative;
            z-index: 60;
        }
        .menu-toggle {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            border: 1px solid rgba(255, 45, 45, 0.25);
            background: rgba(255, 45, 45, 0.10);
            box-shadow: var(--glow);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s ease;
        }
        .menu-toggle:hover {
            transform: translateY(-1px);
            background: rgba(255, 45, 45, 0.14);
        }
        .burger {
            width: 22px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .burger span {
            display: block;
            height: 2.5px;
            width: 100%;
            border-radius: 999px;
            background: #ffffff;
        }
        .menu-dropdown {
            position: absolute;
            top: 70px;
            right: 0;
            width: 260px;
            background: rgba(20, 20, 23, 0.96);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 18px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.45);
            backdrop-filter: blur(14px);
            padding: 12px;
            display: none;
            z-index: 9999;
        }
        .menu-dropdown.show {
            display: block;
        }
        .menu-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 12px;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: var(--text-soft);
            padding: 8px 10px 10px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 8px;
        }
        .menu-item {
            display: block;
            width: 100%;
            text-decoration: none;
            color: #ffffff;
            background: linear-gradient(180deg, rgba(34, 34, 37, 0.96), rgba(16, 16, 18, 0.96));
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 14px;
            padding: 14px 14px;
            font-weight: 600;
            transition: 0.2s ease;
        }
        .menu-item:hover {
            border-color: rgba(255, 45, 45, 0.28);
            box-shadow: 0 0 16px rgba(255,45,45,0.18);
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.35fr 0.85fr;
            gap: 24px;
        }
        .panel {
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 28px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px);
            padding: 24px;
            position: relative;
            overflow: hidden;
        }
        .panel::before {
            content: "";
            position: absolute;
            top: 0;
            left: 24px;
            right: 24px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 45, 45, 0.5), transparent);
        }
        .panel-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 12px;
            flex-wrap: wrap;
        }
        .panel-title h2 {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            font-size: 18px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .panel-title-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            color: #ffffff;
            font-weight: 600;
            max-width: 100%;
            transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .status-pill.status-green {
            background: rgba(34, 197, 94, 0.10);
            border: 1px solid rgba(34, 197, 94, 0.28);
            box-shadow: 0 0 18px rgba(34, 197, 94, 0.28);
        }
        .status-pill.status-red {
            background: rgba(255, 45, 45, 0.10);
            border: 1px solid rgba(255, 45, 45, 0.25);
            box-shadow: var(--glow);
        }
        .status-pill-text {
            white-space: nowrap;
        }
        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
            transition: background 0.2s ease, box-shadow 0.2s ease;
        }
        .gauges {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }
        .gauge-card {
            background: linear-gradient(180deg, rgba(34, 34, 37, 0.96), rgba(16, 16, 18, 0.96));
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 24px;
            padding: 20px 18px 18px;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.04), 0 10px 24px rgba(0,0,0,0.30);
            position: relative;
        }
        .gauge-card::after {
            content: "";
            position: absolute;
            inset: auto 18px 0 18px;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, transparent, rgba(255,45,45,0.7), transparent);
            opacity: 0.75;
        }
        .gauge-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .gauge-header h3 {
            margin: 0;
            font-size: 15px;
            color: #f1f5f9;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .canvas-wrap {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 6px;
        }
        canvas {
            max-width: 230px;
            margin: 0 auto;
        }
        .value {
            margin-top: 10px;
            text-align: center;
            font-family: 'Orbitron', sans-serif;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #ffffff;
            text-shadow: 0 0 10px rgba(255,255,255,0.05);
        }
        .unit-note {
            margin-top: 6px;
            text-align: center;
            color: var(--text-soft);
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .crash-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            color: #ffffff;
            font-weight: 700;
            margin-top: 16px;
            background: rgba(255, 45, 45, 0.10);
            border: 1px solid rgba(255,45,45,0.22);
            box-shadow: 0 0 14px rgba(255,45,45,0.12);
        }
        .crash-pill .count {
            font-family: 'Orbitron', sans-serif;
            font-size: 18px;
            color: #ffdddd;
        }
        /* Light control pill */
        .light-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            color: #ffffff;
            font-weight: 700;
            margin-top: 16px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 45, 45, 0.35);
            box-shadow: 0 0 14px rgba(255,45,45,0.10);
            transition: border-color 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        }
        .light-pill.lights-on {
            background: rgba(255, 220, 50, 0.10);
            border-color: rgba(255, 220, 50, 0.45);
            box-shadow: 0 0 18px rgba(255, 220, 50, 0.25);
        }
        .light-pill-label {
            font-family: 'Orbitron', sans-serif;
            font-size: 13px;
            letter-spacing: 1px;
            color: #ff2d2d;
            transition: color 0.3s ease;
        }
        .light-pill.lights-on .light-pill-label {
            color: #fde047;
        }
        .light-icon {
            font-size: 18px;
            line-height: 1;
            transition: filter 0.3s ease;
        }
        .light-pill.lights-on .light-icon {
            filter: drop-shadow(0 0 6px rgba(255, 220, 50, 0.8));
        }
        .light-btn {
            border: none;
            border-radius: 8px;
            padding: 6px 14px;
            font-family: 'Orbitron', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .light-btn:hover {
            transform: translateY(-1px);
        }
        .light-btn-on {
            background: #22c55e;
            color: #fff;
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.35);
        }
        .light-btn-on:hover {
            box-shadow: 0 0 16px rgba(34, 197, 94, 0.55);
        }
        .light-btn-off {
            background: #ff2d2d;
            color: #fff;
            box-shadow: 0 0 10px rgba(255, 45, 45, 0.35);
        }
        .light-btn-off:hover {
            box-shadow: 0 0 16px rgba(255, 45, 45, 0.55);
        }
        .camera-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .camera-wrapper {
            flex: 1;
            min-height: 380px;
            border-radius: 22px;
            overflow: hidden;
            background: linear-gradient(180deg, rgba(8, 8, 10, 0.95), rgba(18, 18, 20, 0.98));
            border: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
            position: relative;
        }
        .camera-wrapper::before {
            content: "LIVE FEED";
            position: absolute;
            top: 14px;
            left: 14px;
            z-index: 2;
            font-family: 'Orbitron', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.4px;
            color: #fff;
            background: rgba(255, 45, 45, 0.15);
            border: 1px solid rgba(255, 45, 45, 0.4);
            padding: 8px 12px;
            border-radius: 999px;
            box-shadow: 0 0 12px rgba(255,45,45,0.3);
        }
        .camera-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .camera-note {
            margin-top: 14px;
            color: var(--text-soft);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .camera-note::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--accent-green);
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.8);
        }
        .buttons {
            margin-top: 24px;
            display: flex;
            justify-content: center;
            gap: 18px;
            flex-wrap: wrap;
        }
        .buttons form {
            margin: 0;
        }
        .btn {
            min-width: 200px;
            border: 1px solid transparent;
            padding: 15px 28px;
            border-radius: 16px;
            cursor: pointer;
            font-family: 'Orbitron', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: white;
            transition: all 0.25s ease;
            box-shadow: 0 10px 24px rgba(0,0,0,0.35);
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .save {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            border-color: rgba(255,255,255,0.08);
        }
        .save:hover {
            box-shadow: 0 0 18px rgba(34,197,94,0.30), 0 12px 26px rgba(0,0,0,0.32);
        }
        .stop {
            background: linear-gradient(135deg, #b91c1c, #ff2d2d);
            border-color: rgba(255,255,255,0.08);
        }
        .stop:hover {
            box-shadow: 0 0 18px rgba(255,45,45,0.28), 0 12px 26px rgba(0,0,0,0.32);
        }
        @media (max-width: 1180px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            .camera-wrapper {
                min-height: 320px;
            }
        }
        @media (max-width: 900px) {
            .gauges {
                grid-template-columns: 1fr;
            }
            .value {
                font-size: 24px;
            }
            .page {
                width: min(100% - 20px, 1450px);
            }
            .topbar,
            .panel {
                padding: 18px;
                border-radius: 22px;
            }
            .menu-dropdown {
                width: 220px;
            }
        }
        @media (max-width: 640px) {
            .panel-title {
                align-items: flex-start;
            }
            .panel-title-right {
                width: 100%;
                justify-content: flex-start;
            }
            .status-pill-text {
                white-space: normal;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="topbar">
            <div class="title-block">
                <h1>Dashboard</h1>
                <p>Real-time data monitoring interface</p>
            </div>
            <div class="topbar-right">
                <button class="menu-toggle" id="menuToggle" type="button" aria-label="Open menu">
                    <div class="burger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                <div class="menu-dropdown" id="menuDropdown">
                    <div class="menu-title">Menu</div>
                    <a class="menu-item" href="previous_laps.php">Previous Recorded Laps</a>
                </div>
            </div>
        </div>
 
        <div class="dashboard-grid">
            <div class="panel">
                <div class="panel-title">
                    <h2>Gauges</h2>
                    <div class="panel-title-right">
                        <div class="status-pill status-red" id="statusPill">
                            <span class="status-dot" id="statusDot"></span>
                            <span class="status-pill-text" id="updatedAt">Waiting for data...</span>
                        </div>
                    </div>
                </div>
                <div class="gauges">
                    <div class="gauge-card">
                        <div class="gauge-header">
                            <h3>Speed</h3>
                        </div>
                        <div class="canvas-wrap">
                            <canvas id="speedGauge"></canvas>
                        </div>
                        <div class="value" id="speedValue">0.0 km/h</div>
                        <div class="unit-note">Vehicle speed</div>
                    </div>
                    <div class="gauge-card">
                        <div class="gauge-header">
                            <h3>Temperature</h3>
                        </div>
                        <div class="canvas-wrap">
                            <canvas id="tempGauge"></canvas>
                        </div>
                        <div class="value" id="tempValue">0.0 °C</div>
                        <div class="unit-note">Temperature</div>
                    </div>
                    <div class="gauge-card">
                        <div class="gauge-header">
                            <h3>Voltage</h3>
                        </div>
                        <div class="canvas-wrap">
                            <canvas id="voltGauge"></canvas>
                        </div>
                        <div class="value" id="voltValue">0.00 V</div>
                        <div class="unit-note">Battery Voltage</div>
                    </div>
                </div>
 
                <!-- Crash + Headlights row -->
                <div style="display:flex; justify-content:center; gap:18px; flex-wrap:wrap; margin-top:16px;">
                    <div id="crashPill" class="crash-pill" role="status" aria-live="polite">
                        <div class="status-dot" style="width:12px;height:12px;border-radius:50%;background:#ff2d2d;box-shadow:0 0 8px rgba(255,45,45,0.6);"></div>
                        <div class="status-pill-text">Crashes: <span id="crashCount" class="count">0</span></div>
                    </div>
 
                    <div class="light-pill" id="lightPill">
                        <span class="light-icon" id="lightIcon">💡</span>
                        <span class="light-pill-label" id="lightLabel">Headlights</span>
                        <button class="light-btn light-btn-on" onclick="setLight('ON')">ON</button>
                        <button class="light-btn light-btn-off" onclick="setLight('OFF')">OFF</button>
                    </div>
                </div>
            </div>
 
            <div class="panel camera-card">
                <div class="panel-title">
                    <h2>Live Camera</h2>
                </div>
                <div class="camera-wrapper">
                    <img id="cameraStream" src="<?= htmlspecialchars($cameraStreamUrl) ?>" alt="ESP Camera Stream">
                </div>
                <div class="camera-note" id="cameraStatus">Connecting to camera...</div>
            </div>
        </div>
 
        <div class="buttons">
            <form action="save_session.php" method="post">
                <button class="btn save" type="submit">Stop and Save</button>
            </form>
            <form action="stop_session.php" method="post">
                <button class="btn stop" type="submit">Stop</button>
            </form>
        </div>
    </div>
 
    <script>
        // ── Light control ──────────────────────────────────────────────
        let lightsOn = false;
 
        function setLight(state) {
            fetch('set_command.php?light=' + state)
                .then(() => {
                    lightsOn = (state === 'ON');
                    const pill  = document.getElementById('lightPill');
                    const label = document.getElementById('lightLabel');
                    if (lightsOn) {
                        pill.classList.add('lights-on');
                        label.textContent = 'Lights ON';
                    } else {
                        pill.classList.remove('lights-on');
                        label.textContent = 'Headlights';
                    }
                })
                .catch(err => console.error('Light command failed:', err));
        }
 
        // ── Gauge helpers ──────────────────────────────────────────────
        function getGaugeColor(value, maxValue) {
            const ratio = value / maxValue;
            if (ratio < 0.5) return '#ffffff';
            if (ratio < 0.8) return '#ff8a00';
            return '#ff2d2d';
        }
 
        function createGauge(ctx, value, maxValue) {
            return new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [value, Math.max(maxValue - value, 0)],
                        backgroundColor: [getGaugeColor(value, maxValue), 'rgba(148, 148, 155, 0.18)'],
                        borderWidth: 0,
                        hoverOffset: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    rotation: -90,
                    circumference: 180,
                    cutout: '72%',
                    animation: { duration: 300 },
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                }
            });
        }
 
        const speedChart = createGauge(document.getElementById('speedGauge'), 0, 45);
        const tempChart  = createGauge(document.getElementById('tempGauge'),  0, 100);
        const voltChart  = createGauge(document.getElementById('voltGauge'),  0, 15);
 
        function updateGauge(chart, value, max) {
            const safeValue = Math.max(0, Math.min(value, max));
            chart.data.datasets[0].data = [safeValue, max - safeValue];
            chart.data.datasets[0].backgroundColor = [getGaugeColor(safeValue, max), 'rgba(148, 148, 155, 0.18)'];
            chart.update();
        }
 
        // ── Status pill ────────────────────────────────────────────────
        function setStatusWaiting() {
            const pill = document.getElementById('statusPill');
            const dot  = document.getElementById('statusDot');
            const txt  = document.getElementById('updatedAt');
            pill.classList.remove('status-green');
            pill.classList.add('status-red');
            dot.style.background  = '#ff2d2d';
            dot.style.boxShadow   = '0 0 10px rgba(255, 45, 45, 0.9)';
            txt.textContent = 'Waiting for data...';
        }
 
        function setStatusReceiving() {
            const pill = document.getElementById('statusPill');
            const dot  = document.getElementById('statusDot');
            const txt  = document.getElementById('updatedAt');
            pill.classList.remove('status-red');
            pill.classList.add('status-green');
            dot.style.background  = '#22c55e';
            dot.style.boxShadow   = '0 0 10px rgba(34, 197, 94, 0.9)';
            txt.textContent = 'Receiving data';
        }
 
        // ── Data fetch ─────────────────────────────────────────────────
        let lastGoodDataTime = 0;
        let crashCount = 0;
 
        async function fetchData() {
            try {
                const response = await fetch('latest_data.php?_=' + Date.now(), { cache: 'no-store' });
                if (!response.ok) throw new Error('Bad response');
                const data = await response.json();
 
                const speed = parseFloat(data.speed   || 0);
                const temp  = parseFloat(data.tempC   || 0);
                const volt  = parseFloat(data.voltage || 0);
 
                const crashRaw = data.crash ?? data.crashes ?? data.crash_flag ?? null;
                const crashVal = crashRaw !== null ? parseInt(crashRaw) : NaN;
                if (!Number.isNaN(crashVal) && crashVal === 1) {
                    crashCount++;
                    const crashEl = document.getElementById('crashCount');
                    if (crashEl) crashEl.textContent = String(crashCount);
                }
 
                updateGauge(speedChart, speed, 45);
                updateGauge(tempChart,  temp,  100);
                updateGauge(voltChart,  volt,  15);
 
                document.getElementById('speedValue').textContent = `${speed.toFixed(1)} km/h`;
                document.getElementById('tempValue').textContent  = `${temp.toFixed(1)} °C`;
                document.getElementById('voltValue').textContent  = `${volt.toFixed(2)} V`;
 
                lastGoodDataTime = Date.now();
                setStatusReceiving();
            } catch (err) {
                // watcher below handles the UI switch
            }
        }
 
        function watchDataTimeout() {
            if (!lastGoodDataTime || (Date.now() - lastGoodDataTime > 2000)) {
                setStatusWaiting();
            } else {
                setStatusReceiving();
            }
        }
 
        // ── Camera ─────────────────────────────────────────────────────
        const cam          = document.getElementById('cameraStream');
        const cameraStatus = document.getElementById('cameraStatus');
        cam.onload  = () => { cameraStatus.textContent = 'Camera connected';           cameraStatus.style.color = '#d4d4d8'; };
        cam.onerror = () => { cameraStatus.textContent = 'Camera stream unavailable';  cameraStatus.style.color = '#fca5a5'; };
 
        const menuToggle   = document.getElementById('menuToggle');
        const menuDropdown = document.getElementById('menuDropdown');
        menuToggle.addEventListener('click', e => { e.stopPropagation(); menuDropdown.classList.toggle('show'); });
        document.addEventListener('click', e => {
            if (!menuDropdown.contains(e.target) && !menuToggle.contains(e.target)) {
                menuDropdown.classList.remove('show');
            }
        });
 
        // ── Boot ───────────────────────────────────────────────────────
        setStatusWaiting();
        fetchData();
        setInterval(fetchData,          500);
        setInterval(watchDataTimeout,   250);
    </script>
</body>
</html>