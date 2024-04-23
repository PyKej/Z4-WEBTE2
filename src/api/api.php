<?php
require_once('../db/connect.php'); // Database configuration
require_once('timetable_api.php'); // The logic for handling timetable data
require_once('endAssigment_api.php'); // The logic for handling end assigment data
require_once('weather_api.php'); // The logic for handling weather data

header("Content-Type: application/json");

$conn = getDBConnection();
$timetable = new TimetableAPI($conn);
$endAssigment = new EndAssigmentAPI($conn);
$weather = new WeatherAPI($conn);

$method = $_SERVER['REQUEST_METHOD'];
// $endpoint = $_SERVER['REQUEST_URI'];

$uri = $_SERVER['REQUEST_URI'];
// $endpoint = substr($uri, strpos($uri, '/api.php/') + strlen('/api.php'));



// $endpoint = getEndpoint($_SERVER['QUERY_STRING']);
$endpoint = getEndpoint();

// $endpoint = substr($uri, strpos($uri, 'src/api/api.php/') + strlen('src/api/api.php'));


// var_dump($endpoint);
switch ($method) {
    case 'GET':
        if (preg_match('/^\/src\/api\/api\.php\/currWeather\/(\w+)$/', $endpoint, $matches)) {
            $destination = $matches[1];
            http_response_code(200);
            echo json_encode($weather->getCurlCurrWeatherByName("$destination"));
        }
        else if (preg_match('/^\/src\/api\/api\.php\/averageWeather\/([-+]?\d+\.\d+)\/([-+]?\d+\.\d+)$/', $endpoint, $matches)) {
            $latitude = $matches[1];
            $longitude = $matches[2];
            echo json_encode($weather->getAverageWeather($latitude, $longitude, '2022-01-01', '2022-12-31'));
        }
        else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}

function getEndpoint() {
    $uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($uri, PHP_URL_PATH);
    // echo $path;
    return $path;
}



?>