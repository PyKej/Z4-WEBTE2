<?php
class WeatherAPI {
    protected $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getCurlCurrWeather($latitude, $longitude) {
        // API key should be stored in a configuration file or environment variable for security
        $apiKey = "5c976b1a4dd03882a6a9af9a0c9e1451";
        $units = "metric"; // or 'imperial' for Fahrenheit

        // Construct the URL
        $url = "https://api.openweathermap.org/data/2.5/weather?lat=" . $latitude . "&lon=". $longitude . "&units=$units&APPID=$apiKey";

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        // Execute the GET request
        $response = curl_exec($ch);

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response to a PHP array
        $weatherData = json_decode($response, true);

        // Handle the case where cURL failed
        if ($response === false) {
            return "cURL Error: " . curl_error($ch);
        }

        // Check if the API returned a successful response
        if (isset($weatherData['cod']) && $weatherData['cod'] == 200) {
            return $weatherData;
        } else {
            // Handle API error response
            return isset($weatherData['message']) ? $weatherData['message'] : 'Error retrieving weather data';
        }
    }


    public function getAverageWeather($latitude, $longitude, $startDate, $endDate) {
        // Construct the Archive API URL
        $url = "https://archive-api.open-meteo.com/v1/archive?latitude=$latitude&longitude=$longitude&start_date=$startDate&end_date=$endDate&daily=temperature_2m_max,temperature_2m_min&timezone=Europe%2FLondon";
    
        // Initialize cURL session
        $ch = curl_init();
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
    
        // Execute the GET request
        $response = curl_exec($ch);
    
        // Close cURL session
        curl_close($ch);
    
        // Decode the JSON response to a PHP array
        $weatherData = json_decode($response, true);
    
        // Handle the case where cURL failed
        if ($response === false) {
            return "cURL Error: " . curl_error($ch);
        }
    
        // Check if the API returned a successful response
        if (isset($weatherData['daily']) && is_array($weatherData['daily'])) {
            $monthlyData = [];
            
            foreach ($weatherData['daily']['time'] as $index => $date) {
                $month = substr($date, 0, 7); // Get the year-month portion of the date
                $maxTemp = $weatherData['daily']['temperature_2m_max'][$index];
                $minTemp = $weatherData['daily']['temperature_2m_min'][$index];
                $avgTemp = ($maxTemp + $minTemp) / 2;
    
                // Initialize the month array if not already set
                if (!isset($monthlyData[$month])) {
                    $monthlyData[$month] = ['sumAvg' => 0, 'count' => 0];
                }
    
                // Add average temperature to the month data
                $monthlyData[$month]['sumAvg'] += $avgTemp;
                $monthlyData[$month]['count']++;
            }
    
            // Calculate the averages for each month
            $monthlyAverages = [];
            $numMounth = 1;
            foreach ($monthlyData as $month => $values) {

                $averageTemp = $values['sumAvg'] / $values['count'];
                $monthlyAverages[$numMounth] = round($averageTemp, 2);
                $numMounth++;
            }
    
            return $monthlyAverages;
        } else {
            // Handle API error response
            return isset($weatherData['message']) ? $weatherData['message'] : 'Error retrieving average weather data';
        }
    }
    




















}


?>