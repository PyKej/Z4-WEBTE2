<?php 
  require_once('db/connect.php');
  $conn = getDBConnection();
  require_once('curl/f_curl.php');

?>

<!DOCTYPE html>
<html lang="en">

<?php include 'parts/head.php'; ?>

<body>
  <!-- Navbar -->
  <?php include 'parts/navbar.php'; ?>

  <!-- Main content -->

  <div class="container">
    <div class="row">
      <div class="col-12">
      <h1>Zadanie4</h1>
        <button class="btn btn-primary" id="currWeather">Current weather</button>
        <button class="btn btn-primary" id="averageWeather">Average weather</button>
        
      </div>
    </div>


  </div>

  
  <div class="container custom-container mt-5">
    
    <h2>Enter Your Vacation Destination</h2>
    <form class="row" id="vacationForm">
        <div class="col-8 mb-3">
            <label for="destinationInput" class="form-label">Destination:</label>
            <input type="text" class="form-control" id="destinationInput" name="destination" placeholder="Enter destination">
        </div>
        <button type="submit" class="col-4 btn btn-primary">Submit</button>
    </form>
    
    </div>

    <div id="weatherContainer" class="container custom-container mt-5" style="display: none;">
    <div id="weatherResult"></div> <!-- Element to display weather data -->
    </div>


    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; bottom: 20px; right: 20px;">
        <div class="toast-header">
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?php echo $_SESSION['message'] ?? ''; ?>
        </div>
    </div>



</div>







  <!-- jQuery library - Must be loaded first -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- DataTables JS - Make sure this is the correct DataTables script source -->
  <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

  <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; bottom: 20px; right: 20px;">
    <div class="toast-header">
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      <?php
        echo $_SESSION['message'] ?? '';
      ?>
    </div>
  </div>



<script>
        let loadedData = []; // Global variable to store loaded data

        // document.getElementById('currWeather').addEventListener('click', function() {
        //     fetch('/src/api/api.php/currWeather', {
        //         method: 'GET',
        //         headers: {'Accept': 'application/json'}
        //     })
        //     .then(response => {
        //         if (!response.ok) {
        //             throw new Error('Network response was not ok');
        //         }
        //         return response.json(); // Assuming your server responds with JSON
        //     })
        //     .then(data => {
        //         console.log("Data curled:", data);
        //         loadedData = data.main.temp;

        //         console.log(loadedData);
        //         document.querySelector('.toast-body').textContent = data.message; // Set message text
        //         document.querySelector('.toast').classList.add('show'); // Show toast
        //     })
        //     .catch(error => {
        //         console.error('Error loading data:', error);
        //     });
        // });

        // document.getElementById('averageWeather').addEventListener('click', function() {
        //     fetch('/src/api/api.php/averageWeather', {
        //         method: 'GET',
        //         headers: {'Accept': 'application/json'}
        //     })
        //     .then(response => {
        //         if (!response.ok) {
        //             throw new Error('Network response was not ok');
        //         }
        //         return response.json(); // Assuming your server responds with JSON
        //     })
        //     .then(data => {
        //         console.log("Data curled:", data);
        //         // loadedData = data.main.temp;

        //         // console.log(loadedData);
        //         document.querySelector('.toast-body').textContent = data.message; // Set message text
        //         document.querySelector('.toast').classList.add('show'); // Show toast
        //     })
        //     .catch(error => {
        //         console.error('Error loading data:', error);
        //     });
        // });

  

// document.getElementById('vacationForm').addEventListener('submit', function(event) {
//     event.preventDefault(); // Prevent default form submission

//     const destination = document.getElementById('destinationInput').value; // Get the destination from the form
//     const fetchWeatherData = (endpoint, params = '') => fetch(`/src/api/api.php/${endpoint}/${params}`, {
//         method: 'GET',
//         headers: {'Accept': 'application/json'}
//     })
//     .then(response => {
//         if (!response.ok) throw new Error('Network response was not ok');
//         return response.json();
//     });

//     fetchWeatherData('currWeather', encodeURIComponent(destination))
//     .then(data => {
//         console.log("Current Weather Data:", data);
//         // console.log("Current Weather Data-small:", data.coord.lat);
        
//         return fetchWeatherData('averageWeather', `${data.coord.lat}/${data.coord.lon}`);
//     })
//     .then(averageWeatherData => {
//         console.log("Average Weather Data:", averageWeatherData);
//         const toastBody = document.querySelector('.toast-body');
//         toastBody.textContent = 'Weather data loaded successfully';
//         toastBody.closest('.toast').classList.add('show'); // Show toast
//     })
//     .catch(error => {
//         console.error('Error loading weather data:', error);
//         const toastBody = document.querySelector('.toast-body');
//         toastBody.textContent = 'Failed to load weather data';
//         toastBody.closest('.toast').classList.add('show');
//     });
// });


document.getElementById('vacationForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            const destination = document.getElementById('destinationInput').value; // Get the destination from the form
            const fetchWeatherData = (endpoint, params = '') => fetch(`/src/api/api.php/${endpoint}/${params}`, {
                method: 'GET',
                headers: {'Accept': 'application/json'}
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            });

            const weatherContainer = document.getElementById('weatherContainer');
            weatherContainer.style.display = 'none'; // Hide the container


            fetchWeatherData('currWeather', encodeURIComponent(destination))
            .then(data => {
                console.log("Current Weather Data:", data);
                const weatherResultDiv = document.getElementById('weatherResult');
                // weatherResult.textContent = JSON.stringify(data, null, 2); // Display the weather data
                weatherContainer.style.display = 'block'; // Show the container
                weatherResultDiv.innerHTML = `
                <h3>Actual weather in ${data.name} (${data.sys.country? (data.sys.country) : '' }) <img  src="https://openweathermap.org/img/wn/${data.weather[0].icon}.png" alt="${data.weather [0].description}"  ></h3>

                <div class="row">
                <div class="col">
                <p>General: ${data.weather[0].description}</p>
                <p>Rain: ${data.rain ? data.rain['1h'] : 0} mm</p>
                <p>Current Temperature: ${data.main.temp}°C</p>
                <p>Min Temperature: ${data.main.temp_min}°C</p>
                <p>Max Temperature: ${data.main.temp_max}°C</p>
                <p>Feels like: ${data.main.feels_like}°C</p>
                <p>Visibility: ${data.visibility / 1000} km</p>
                </div>
                <div class="col">
                <p>Pressure: ${data.main.pressure} hPa</p>
                <p>Humidity: ${data.main.humidity}%</p>
                
                <p>Wind Speed: ${data.wind.speed} m/s</p>
                <p>Wind Direction: ${data.wind.deg}°</p>
                <p>Cloudiness: ${data.clouds.all}%</p>
                <p>Sunrise: ${new Date(data.sys.sunrise * 1000).toLocaleTimeString()}</p>
                <p>Sunset: ${new Date(data.sys.sunset * 1000).toLocaleTimeString()}</p>
                </div>
                </div>
              
                `;
                
                return fetchWeatherData('averageWeather', `${data.coord.lat}/${data.coord.lon}`);
            })
            .then(averageWeatherData => {
                console.log("Average Weather Data:", averageWeatherData);
                const weatherResultDiv = document.getElementById('weatherResult');
                weatherResultDiv.innerHTML += `
                <h3>Average temperatures for year 2023</h3>
                <div class="row">
                <div class="col">
                <p>January: ${averageWeatherData[1]}°C</p>
                <p>February: ${averageWeatherData[2]}°C</p>
                <p>March: ${averageWeatherData[3]}°C</p>
                <p>April: ${averageWeatherData[4]}°C</p>
                <p>May: ${averageWeatherData[5]}°C</p>
                <p>June: ${averageWeatherData[6]}°C</p>
                </div>
                <div class="col">
                <p>July: ${averageWeatherData[7]}°C</p>
                <p>August: ${averageWeatherData[8]}°C</p>
                <p>September: ${averageWeatherData[9]}°C</p>
                <p>October: ${averageWeatherData[10]}°C</p>
                <p>November: ${averageWeatherData[11]}°C</p>
                <p>December: ${averageWeatherData[12]}°C</p>
                </div>
                </div>
                
                
                `;
                
                const toastBody = document.querySelector('.toast-body');
                toastBody.textContent = 'Weather data loaded successfully';
                toastBody.closest('.toast').classList.add('show'); // Show toast
            })
            .catch(error => {
                console.error('Error loading weather data:', error);
                const toastBody = document.querySelector('.toast-body');
                toastBody.textContent = 'Failed to load weather data';
                toastBody.closest('.toast').classList.add('show');
            });
        });


    
        


</script>











</body>

</html>