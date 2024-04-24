<?php
require_once('../db/connect.php'); // Database configuration
require_once('weather_api.php'); // The logic for handling weather data

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Accept");

header("Content-Type: application/json");

$conn = getDBConnection();
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
        elseif (preg_match('/^\/src\/api\/api\.php\/countryInfo\/(\w+)$/', $endpoint, $matches)) {
            $countryCode = $matches[1];
            $result = $weather->getCountryInfo($countryCode);
            echo json_encode($result);
        }
        else if (preg_match('/^\/src\/api\/api\.php\/getSearchStats$/', $endpoint)) {
            if ($conn) {
                // Assuming $conn is a PDO instance; adjust accordingly if using MySQLi
                try {
                    $stmt = $conn->prepare("SELECT destination, country, search_count FROM searches");
                    $stmt->execute();
                    $searchStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    http_response_code(200);
                    echo json_encode($searchStats);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
                }
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Failed to connect to the database']);
            }
        }
        else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'POST':
        if (preg_match('/^\/src\/api\/api\.php\/recordSearch\/([^\/]+)\/([^\/]+)$/', $endpoint, $matches)) {
            $destination = urldecode($matches[1]);
            $country = urldecode($matches[2]);
            $result = $weather->recordSearch($destination, $country);
            echo json_encode($result);
        } else {
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