<?php

$apiKey = "bc2fe2ab317fd391ca9683c0f45aa957";


$city = isset($_GET['city']) ? htmlspecialchars($_GET['city']) : "";
$message = ""; 

function dd($var)
{
    dump($var);
    die;
}
function dump($var)
{
    preprint($var);
}
function preprint($var)
{
    echo "<pre style='color:red'>";
    print_r($var);
    echo "</pre>";
}

if (!empty($city)) {
    
    $url = "http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&lang=en&units=metric";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);

    if ($response === FALSE) {
        $message = '<div class="alert alert-danger">Error retrieving data: ' . curl_error($ch) . '</div>';
    } else {
        curl_close($ch);

        $data = json_decode($response, true); 
        // Pour avoir les inforamtion du dump en rouge :
        // dump($data);

        if ($data['cod'] == 200) {
            $temp = $data['main']['temp'];
            $message = '<div class="alert alert-success">The current temperature in <strong>' . htmlspecialchars($city) . '</strong> is <strong>' . round($temp, 2) . '°C</strong>.</div>';
        } else {
            $message = '<div class="alert alert-danger">City not found. Please check the spelling and try again.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Météo avec OpenWeatherMap</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h2>🌤️ Vérifier la météo</h2>
                </div>
                <div class="card-body">
                    <form method="get" action="">
                        <div class="mb-3">
                            <label for="city" class="form-label">Entrer une ville :</label>
                            <input type="text" id="city" name="city" class="form-control" placeholder="Exemple: Paris" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Afficher la météo</button>
                        </div>
                    </form>
                    
                    <?php if (!empty($message)) echo "<div class='mt-4'>$message</div>"; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>