<?php 
$cameraStreamUrl = "http://192.168.137.8:81/stream";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Live Dashboard</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
:root {
    --bg-main: #0b0b0d;
    --bg-panel: rgba(30, 30, 32, 0.9);
    --bg-card: rgba(40, 40, 42, 0.9);
    --border: rgba(255,255,255,0.08);
    --text-main: #ffffff;
    --text-soft: #a1a1aa;

    --red: #ff2d2d;
    --red-soft: rgba(255,45,45,0.15);
    --green: #22c55e;
    --orange: #ff8a00;

    --shadow: 0 12px 40px rgba(0,0,0,0.6);
    --glow: 0 0 18px rgba(255,45,45,0.35);
}

body {
    margin:0;
    font-family:'Inter', sans-serif;
    color:var(--text-main);
    background:
        radial-gradient(circle at top, rgba(255,45,45,0.12), transparent 30%),
        linear-gradient(160deg, #050505, #0f0f12 50%, #050505);
}

/* HEADER */
.topbar {
    margin:20px;
    padding:20px 25px;
    border-radius:20px;
    background:var(--bg-panel);
    border:1px solid var(--border);
    display:flex;
    justify-content:space-between;
    align-items:center;
}

h1 {
    font-family:'Orbitron';
    letter-spacing:2px;
    margin:0;
}

.status {
    padding:10px 18px;
    border-radius:999px;
    background:var(--red-soft);
    border:1px solid rgba(255,45,45,0.4);
    box-shadow:var(--glow);
}

/* GRID */
.dashboard {
    display:grid;
    grid-template-columns: 2fr 1fr;
    gap:20px;
    padding:20px;
}

.panel {
    background:var(--bg-panel);
    border-radius:20px;
    padding:20px;
    border:1px solid var(--border);
}

/* GAUGES */
.gauges {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:15px;
}

.gauge {
    background:var(--bg-card);
    padding:15px;
    border-radius:18px;
    text-align:center;
}

.gauge h3 {
    font-size:14px;
    margin-bottom:5px;
    color:#ddd;
}

.value {
    font-family:'Orbitron';
    font-size:26px;
    margin-top:10px;
}

/* CAMERA */
.camera {
    display:flex;
    flex-direction:column;
}

.camera-box {
    flex:1;
    background:#000;
    border-radius:15px;
    overflow:hidden;
    border:1px solid var(--border);
}

.camera-box img {
    width:100%;
    height:100%;
    object-fit:cover;
}

.camera-status {
    margin-top:10px;
    color:var(--text-soft);
}

/* BUTTONS */
.buttons {
    text-align:center;
    margin:20px;
}

button {
    padding:14px 25px;
    margin:10px;
    border:none;
    border-radius:12px;
    font-family:'Orbitron';
    letter-spacing:1px;
    cursor:pointer;
}

.save {
    background:#16a34a;
}

.stop {
    background:var(--red);
    box-shadow:0 0 15px rgba(255,45,45,0.5);
}

/* MOBILE */
@media(max-width:900px){
    .dashboard {
        grid-template-columns:1fr;
    }
    .gauges {
        grid-template-columns:1fr;
    }
}
</style>
</head>

<body>

<div class="topbar">
    <h1>Telemetry Dashboard</h1>
    <div class="status" id="updatedAt">Waiting for data...</div>
</div>

<div class="dashboard">
    <!-- GAUGES -->
    <div class="panel">
        <div class="gauges">
            <div class="gauge">
                <h3>Speed</h3>
                <canvas id="speedGauge"></canvas>
                <div class="value" id="speedValue">0 km/h</div>
            </div>

            <div class="gauge">
                <h3>Temperature</h3>
                <canvas id="tempGauge"></canvas>
                <div class="value" id="tempValue">0 °C</div>
            </div>

            <div class="gauge">
                <h3>Voltage</h3>
                <canvas id="voltGauge"></canvas>
                <div class="value" id="voltValue">0 V</div>
            </div>
        </div>
    </div>

    <!-- CAMERA -->
    <div class="panel camera">
        <div class="camera-box">
            <img id="cameraStream" src="<?= htmlspecialchars($cameraStreamUrl) ?>">
        </div>
        <div class="camera-status" id="cameraStatus">Connecting...</div>
    </div>
</div>

<div class="buttons">
    <form action="save_session.php" method="post">
        <button class="save">Stop & Save</button>
    </form>
    <form action="stop_session.php" method="post">
        <button class="stop">Stop</button>
    </form>
</div>

<script>
function getColor(val, max) {
    const r = val/max;
    if(r < 0.5) return "#ffffff";
    if(r < 0.8) return "#ff8a00";
    return "#ff2d2d";
}

function createGauge(ctx, val, max){
    return new Chart(ctx, {
        type:'doughnut',
        data:{
            datasets:[{
                data:[val, max-val],
                backgroundColor:[getColor(val,max), "#2a2a2a"],
                borderWidth:0
            }]
        },
        options:{
            rotation:-90,
            circumference:180,
            cutout:'70%',
            plugins:{legend:{display:false}}
        }
    });
}

const speedChart = createGauge(speedGauge,0,45);
const tempChart  = createGauge(tempGauge,0,100);
const voltChart  = createGauge(voltGauge,0,15);

function update(chart,val,max){
    chart.data.datasets[0].data=[val,max-val];
    chart.data.datasets[0].backgroundColor=[getColor(val,max),"#2a2a2a"];
    chart.update();
}

async function fetchData(){
    try{
        const res = await fetch('latest_data.php?_='+Date.now());
        const d = await res.json();

        let s = parseFloat(d.speed||0);
        let t = parseFloat(d.tempC||0);
        let v = parseFloat(d.voltage||0);

        update(speedChart,s,45);
        update(tempChart,t,100);
        update(voltChart,v,15);

        speedValue.textContent = s.toFixed(1)+" km/h";
        tempValue.textContent  = t.toFixed(1)+" °C";
        voltValue.textContent  = v.toFixed(2)+" V";

        updatedAt.textContent = d.updated_at || "Waiting for data...";
    }catch{
        updatedAt.textContent = "Error reading data";
    }
}

cameraStream.onload = ()=>cameraStatus.textContent="Camera connected";
cameraStream.onerror = ()=>cameraStatus.textContent="Camera unavailable";

fetchData();
setInterval(fetchData,500);
</script>

</body>
</html>