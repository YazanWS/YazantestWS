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
        <?php
            function sanitize($value) {
                return htmlspecialchars(trim($value));
            }

            $carNames = [
                "celica" => "1986 Toyota Celica",
                "datsun" => "1983 Datsun 280ZX Turbo",
                "mercedes" => "1995 Mercedes-Benz W140",
                "prelude" => "1995 Honda Prelude"
            ];

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $name = sanitize($_POST['name'] ?? '');
                $carKey = sanitize($_POST['favoriteCar'] ?? '');
                $car = $carNames[$carKey] ?? 'Unknown';

                echo "<p>Hello, your name is: <strong>$name</strong>.</p>";
                echo "<p>Your favorite car is: <strong>$car</strong>.</p>";
            } else {
                echo "<p>Form was not submitted correctly.</p>";
            }
        ?>
    </div>
</body>
</html>
