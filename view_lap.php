<?php
// view_lap.php
// Displays all columns in the selected table as graphs (value vs id)
$db_host = 'localhost';
$db_name = 'sensor_dash';
$db_user = 'yazan';
$db_pass = 'Mango3990';

$table = isset($_GET['table']) ? $_GET['table'] : '';
$error = null;
$columns = [];
$rows = [];

if ($table) {
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        // Get columns
        $stmt = $pdo->query("SHOW COLUMNS FROM `" . str_replace("`", "``", $table) . "`");
        while ($col = $stmt->fetch()) {
            $columns[] = $col['Field'];
        }
        // Get all rows
        $stmt = $pdo->query("SELECT * FROM `" . str_replace("`", "``", $table) . "`");
        while ($row = $stmt->fetch()) {
            $rows[] = $row;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Lap Graph Viewer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-main: #0a0a0c;
            --bg-panel: rgba(25, 25, 28, 0.85);
            --bg-card: rgba(35, 35, 38, 0.90);
            --border: rgba(255, 255, 255, 0.08);
            --text-main: #ffffff;
            --text-soft: #a1a1aa;
            --accent-red: #ff2d2d;
            --shadow: 0 12px 40px rgba(0, 0, 0, 0.60);
            --glow: 0 0 18px rgba(255, 45, 45, 0.35);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; color: var(--text-main);
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at top center, rgba(255, 45, 45, 0.12), transparent 30%),
                radial-gradient(circle at bottom right, rgba(255, 45, 45, 0.08), transparent 28%),
                linear-gradient(160deg, #050505 0%, #0d0d10 45%, #050505 100%);
        }
        .page { width: min(1100px, calc(100% - 40px)); margin: 28px auto; }
        .topbar {
            background: rgba(10,10,12,0.82);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 20px;
            display:flex; align-items:center; justify-content:space-between; gap:12px;
            box-shadow: var(--shadow); backdrop-filter: blur(10px);
        }
        .title-block h1 {
            margin:0; font-family: 'Orbitron', sans-serif; font-size: 26px; letter-spacing:1.6px; text-transform:uppercase;
        }
        .title-block p { margin:6px 0 0; color:var(--text-soft); font-size:13px }
        .panel {
            margin-top:18px; background: var(--bg-panel); border:1px solid var(--border);
            padding:18px; border-radius:16px; box-shadow: var(--shadow);
        }
        .graph-list { display:grid; gap:32px; margin-top:18px; }
        .graph-card {
            background: linear-gradient(180deg, rgba(34,34,37,0.96), rgba(16,16,18,0.96));
            border: 1px solid rgba(255,255,255,0.06);
            padding:18px 16px; border-radius:12px; color:var(--text-main);
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }
        .graph-title {
            font-family:'Orbitron', sans-serif; font-weight:700; font-size:17px; letter-spacing:1px; margin-bottom:10px;
        }
        .actions { display:flex; gap:12px; margin-top:18px; }
        .btn { padding:10px 14px; border-radius:10px; background:transparent; border:1px solid rgba(255,255,255,0.06); color:var(--text-main); cursor:pointer }
        .btn.primary { background: linear-gradient(135deg,#ff4d4d,#ff2d2d); border-color: rgba(255,255,255,0.06) }
        .error { background:#2b0000; padding:12px; border-radius:8px; color:#ffd6d6; margin-bottom:12px }
        .note { color:var(--text-soft); margin-top:12px; font-size:13px }
        @media (max-width:640px) {
            .title-block h1 { font-size:20px }
            .graph-list { grid-template-columns: 1fr }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="topbar">
            <div class="title-block">
                <h1>Lap Graph Viewer</h1>
                <p>Table: <b><?= htmlspecialchars($table) ?></b></p>
            </div>
            <div>
                <a href="previous_laps.php" class="btn">Back to Laps</a>
            </div>
        </div>
        <div class="panel">
            <?php if ($error): ?>
                <div class="error">Error: <?= htmlspecialchars($error) ?></div>
            <?php elseif (!$table): ?>
                <div class="note">No table selected. Please go back and choose a session.</div>
            <?php elseif (empty($columns) || empty($rows)): ?>
                <div class="note">No data found in this table.</div>
            <?php else: ?>
                <div class="graph-list">
                    <?php foreach ($columns as $col): if ($col === 'id') continue; ?>
                    <div class="graph-card">
                        <div class="graph-title"><?= htmlspecialchars($col) ?> vs id</div>
                        <canvas id="chart_<?= htmlspecialchars($col) ?>"></canvas>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($table && !$error && !empty($columns) && !empty($rows)): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const rows = <?= json_encode($rows) ?>;
        const columns = <?= json_encode($columns) ?>;
        columns.forEach(col => {
            if (col === 'id') return;
            const ctx = document.getElementById('chart_' + col);
            if (!ctx) return;
            const data = rows.map(r => parseFloat(r[col]));
            const ids = rows.map(r => r['id']);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ids,
                    datasets: [{
                        label: col + ' vs id',
                        data: data,
                        borderColor: '#ff2d2d',
                        backgroundColor: 'rgba(255,45,45,0.13)',
                        pointRadius: 2,
                        borderWidth: 2,
                        tension: 0.2,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { title: { display: true, text: 'id', color: '#fff' }, ticks: { color: '#fff' } },
                        y: { title: { display: true, text: col, color: '#fff' }, ticks: { color: '#fff' } }
                    }
                }
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
