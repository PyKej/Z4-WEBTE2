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
$endpoint = substr($uri, strpos($uri, '/api.php/') + strlen('/api.php'));

switch ($method) {
    case 'GET':
        if ($endpoint == '/currWeather'){
            http_response_code(200);
            echo json_encode($weather->getCurlCurrWeather(49.153495, 20.425547));

            // $currWeather = $weatherAPI->getCurrWeather("Bratislava");
            // print_r($currWeather);

        }
        else if($endpoint == '/averageWeather'){
            http_response_code(200);
            echo json_encode($weather->getAverageWeather(49.153495, 20.425547, '2022-01-01', '2022-12-31'));

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

?>