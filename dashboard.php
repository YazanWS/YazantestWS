<?php
$cameraBaseUrl = "http://192.168.137.8"; // change to your ESP-WROVER-CAM IP
?>
<!DOCTYPE html>
<html>
<head>
    <title>Live Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: white;
            text-align: center;
        }

        h1 {
            padding-top: 20px;
            margin-bottom: 10px;
        }

        .status {
            margin-bottom: 20px;
            color: #cbd5e1;
        }

        .dashboard-grid {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 30px;
            flex-wrap: wrap;
            padding: 20px;
        }

        .gauges {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .gauge-card,
        .camera-card {
            background: #1e293b;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        }

        .gauge-card {
            width: 280px;
        }

        .camera-card {
            width: 380px;
        }

        canvas {
            max-width: 240px;
            margin: 0 auto;
        }

        .value {
            font-size: 24px;
            margin-top: 15px;
            font-weight: bold;
        }

        .camera-wrapper {
            width: 340px;
            height: 240px;
            margin: 0 auto;
            background: #0b1220;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #334155;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .camera-wrapper img {
            width: 340px;
            height: 240px;
            object-fit: cover;
            display: block;
        }

        .camera-note {
            margin-top: 12px;
            color: #cbd5e1;
            font-size: 14px;
        }

        .buttons {
            margin: 30px 0 40px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .btn {
            border: none;
            padding: 14px 24px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            color: white;
        }

        .save {
            background: #16a34a;
        }

        .save:hover {
            background: #15803d;
        }

        .stop {
            background: #dc2626;
        }

        .stop:hover {
            background: #b91c1c;
        }
    </style>
</head>
<body>
    <h1>Live Telemetry Dashboard</h1>
    <div class="status" id="updatedAt">Waiting for data...</div>

    <div class="dashboard-grid">
        <div class="gauges">
            <div class="gauge-card">
                <h2>Speed</h2>
                <canvas id="speedGauge"></canvas>
                <div class="value" id="speedValue">0 km/h</div>
            </div>

            <div class="gauge-card">
                <h2>Temperature</h2>
                <canvas id="tempGauge"></canvas>
                <div class="value" id="tempValue">0 °C</div>
            </div>

            <div class="gauge-card">
                <h2>Voltage</h2>
                <canvas id="voltGauge"></canvas>
                <div class="value" id="voltValue">0 V</div>
            </div>
        </div>

        <div class="camera-card">
            <h2>Live Camera</h2>
            <div class="camera-wrapper">
                <img id="cameraStream" src="" alt="ESP Camera Stream">
            </div>
            <div class="camera-note" id="cameraStatus">Initializing camera...</div>
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

    <script>
        const CAMERA_BASE = "<?= htmlspecialchars($cameraBaseUrl) ?>";
        const cameraStatus = document.getElementById('cameraStatus');
        const cameraStream = document.getElementById('cameraStream');

        function createGauge(ctx, value, maxValue, label) {
            return new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [value, Math.max(maxValue - value, 0)],
                        backgroundColor: ['#3b82f6', '#334155'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    rotation: -90,
                    circumference: 180,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                }
            });
        }

        const speedChart = createGauge(document.getElementById('speedGauge'), 0, 45, 'Speed');
        const tempChart  = createGauge(document.getElementById('tempGauge'), 0, 100, 'Temp');
        const voltChart  = createGauge(document.getElementById('voltGauge'), 0, 15, 'Voltage');

        function updateGauge(chart, value, max) {
            const safeValue = Math.max(0, Math.min(value, max));
            chart.data.datasets[0].data = [safeValue, max - safeValue];
            chart.update();
        }

        async function fetchData() {
            try {
                const response = await fetch('latest_data.php?_=' + new Date().getTime());
                const data = await response.json();

                updateGauge(speedChart, parseFloat(data.speed || 0), 45);
                updateGauge(tempChart, parseFloat(data.tempC || 0), 100);
                updateGauge(voltChart, parseFloat(data.voltage || 0), 15);

                document.getElementById('speedValue').textContent = `${parseFloat(data.speed || 0).toFixed(1)} km/h`;
                document.getElementById('tempValue').textContent  = `${parseFloat(data.tempC || 0).toFixed(1)} °C`;
                document.getElementById('voltValue').textContent  = `${parseFloat(data.voltage || 0).toFixed(2)} V`;

                document.getElementById('updatedAt').textContent =
                    data.updated_at ? `Last update: ${data.updated_at}` : 'Waiting for data...';
            } catch (err) {
                document.getElementById('updatedAt').textContent = 'Error reading live data';
            }
        }

        async function initCamera() {
            cameraStatus.textContent = 'Setting camera format...';

            try {
                // QVGA = 320x240 on many ESP32 camera builds
                await fetch(CAMERA_BASE + '/control?var=framesize&val=5', { mode: 'no-cors' });
            } catch (e) {}

            try {
                await fetch(CAMERA_BASE + '/control?var=quality&val=12', { mode: 'no-cors' });
            } catch (e) {}

            setTimeout(() => {
                cameraStatus.textContent = 'Starting live stream...';

                // Try common stream endpoint
                cameraStream.src = CAMERA_BASE + ':81/stream';

                cameraStream.onerror = function() {
                    cameraStatus.textContent = 'Trying alternate stream endpoint...';
                    cameraStream.onerror = function() {
                        cameraStatus.textContent = 'Camera stream unavailable';
                    };
                    cameraStream.src = CAMERA_BASE + '/stream';
                };

                cameraStream.onload = function() {
                    cameraStatus.textContent = 'Camera stream connected';
                };
            }, 1000);
        }

        fetchData();
        setInterval(fetchData, 500);
        initCamera();
    </script>
</body>
</html>