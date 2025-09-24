<!DOCTYPE html>
<html>
<head>
    <title>Form Response</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f8ff;
            padding: 40px;
        }
        .response {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        p {
            font-size: 1.1em;
            color: #444;
            word-break: break-word;
        }
    </style>
</head>
<body>
    <div class="response">
        <h1>Form Submission Results</h1>
        <p><strong>GET:</strong> <?= var_dump($_GET) ?></p>
        <p><strong>POST:</strong> <?= var_dump($_POST) ?></p>
    </div>
</body>
</html>