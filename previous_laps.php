<?php
// previous_laps.php
// Lists database tables (assumed to be recorded laps) and exposes each as a hyperlink
// Configure your DB credentials below.
$db_host = 'localhost';
$db_name = 'sensor_dash';
$db_user = 'yazan';
$db_pass = 'Mango3990';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,
    ]);
} catch (Exception $e) {
    $error = $e->getMessage();
    $pdo = null;
}

$tables = [];
if ($pdo) {
    try {
        $stmt = $pdo->query('SHOW TABLES');
        while ($row = $stmt->fetch()) {
            $tables[] = $row[0];
        }
    } catch (Exception $e) {
        try {
            $stmt = $pdo->prepare('SELECT table_name FROM information_schema.tables WHERE table_schema = :schema');
            $stmt->execute([':schema' => $db_name]);
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
        } catch (Exception $e2) {
            $error = $e2->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Previous Recorded Laps</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">

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

        .table-list { display:grid; gap:12px; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }

        .table-card {
            background: linear-gradient(180deg, rgba(34,34,37,0.96), rgba(16,16,18,0.96));
            border: 1px solid rgba(255,255,255,0.06);
            padding:14px 16px; border-radius:12px; text-decoration:none; color:var(--text-main);
            display:block; transition:0.18s ease;
        }
        .table-card:hover { transform: translateY(-6px); box-shadow: 0 10px 30px rgba(0,0,0,0.45); border-color: rgba(255,45,45,0.16); }

        .table-name { font-family:'Orbitron', sans-serif; font-weight:700; font-size:15px; letter-spacing:1px; }
        .table-meta { margin-top:8px; color:var(--text-soft); font-size:13px }

        .actions { display:flex; gap:12px; margin-top:18px; }
        .btn { padding:10px 14px; border-radius:10px; background:transparent; border:1px solid rgba(255,255,255,0.06); color:var(--text-main); cursor:pointer }
        .btn.primary { background: linear-gradient(135deg,#ff4d4d,#ff2d2d); border-color: rgba(255,255,255,0.06) }

        .note { color:var(--text-soft); margin-top:12px; font-size:13px }

        .error { background:#2b0000; padding:12px; border-radius:8px; color:#ffd6d6; margin-bottom:12px }

        @media (max-width:640px) {
            .title-block h1 { font-size:20px }
            .table-list { grid-template-columns: 1fr }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="topbar">
            <div class="title-block">
                <h1>Previous Recorded Laps</h1>
                <p>Choose a recorded session to view its data (graphs page will be implemented separately)</p>
            </div>

            <div>
                <a href="dashboard.php" class="btn">Back to Dashboard</a>
            </div>
        </div>

        <div class="panel">
            <?php if (isset($error) && $error): ?>
                <div class="error">Database connection error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (empty($tables)): ?>
                <div class="note">No tables found in the configured database. If you expect recorded laps to exist, please verify your DB credentials and that the database contains tables.</div>
            <?php else: ?>
                <div class="table-list">
                    <?php foreach ($tables as $tbl): ?>
                        <?php $safeName = htmlspecialchars($tbl, ENT_QUOTES, 'UTF-8'); ?>
                        <a class="table-card" href="view_lap.php?table=<?= rawurlencode($tbl) ?>">
                            <div class="table-name"><?= $safeName ?></div>
                            <div class="table-meta">Click to view graphs for this session</div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="actions">
                <form method="get" action="previous_laps.php" style="margin:0">
                    <button class="btn" type="submit">Refresh</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
