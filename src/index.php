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


  
  <div class="container custom-container mt-5">
    
    <h2>Enter Your Vacation Destination</h2>
    <form class="row" id="vacationForm">
        <div class="col-8 mb-3">
            <label for="destinationInput" class="form-label">Destination:</label>
            <input type="text" class="form-control" id="destinationInput" name="destination" placeholder="Enter destination">
        </div>
        <!-- <div class="col-1"></div> -->
        <button type="submit" class="col-3 btn btn-primary">Submit</button>
    </form>
    
    </div>

    <div id="weatherContainer" class="container info-container-main" style="display: none;">
      <div id="actualWeatherResult" class="info-container"></div> <!-- Element to display weather data -->
      <div id="averageWeatherResult" class="info-container"></div> <!-- Element to display weather data -->
      <div id="countryInfo" class="info-container"></div> <!-- Element to display country data -->
      <div id="currencyInfo" class="info-container" style="display: none;"></div> <!-- Added this line for currency info -->
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

        let countryCode;

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
                  const actualWeatherResult = document.getElementById('actualWeatherResult');
                  countryCode = data.sys.country; // assuming data.sys.country contains the country code

                  // Record the search immediately after getting the weather data
                  return fetch(`/src/api/api.php/recordSearch/${encodeURIComponent(destination)}/${encodeURIComponent(countryCode)}`, {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'Accept': 'application/json'
                      },
                      body: JSON.stringify({ country: countryCode }) // Assuming your backend expects JSON
                  })
                  .then(response => response.json())
                  .then(recordResponse => {
                      console.log('Record Search Response:', recordResponse);
                      return data; // continue with the original data
                  });
              })
            .then(data => {
                console.log("Current Weather Data:", data);
                const actualWeatherResult = document.getElementById('actualWeatherResult');
                // weatherResult.textContent = JSON.stringify(data, null, 2); // Display the weather data
                countryCode = data.sys.country;

                weatherContainer.style.display = 'block'; // Show the container
                actualWeatherResult.innerHTML = `
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
                const averageWeatherResult = document.getElementById('averageWeatherResult');
                averageWeatherResult.innerHTML = `
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
                
               
                return fetchWeatherData('countryInfo', countryCode);
            })
            .then(countryData => {
              const currencyCode = Object.keys(countryData[0].currencies)[0]; // Assuming currency data is fetched here
              const countryInfoDiv = document.getElementById('countryInfo');
                countryInfoDiv.innerHTML = `<h3>Country Information</h3>
                    <div class="row">
                    <div class="col">
                    <p><img src="${countryData[0].flags.png}" alt="${countryData[0].name.common} flag" style="width: 100px; height: auto;"></p>
                    <p>Name: ${countryData[0].name.common}</p>
                    <p>Capital: ${countryData[0].capital[0]}</p>
                    
                    <p>Population: ${countryData[0].population}</p>
                    <p>Region: ${countryData[0].region}</p>
                    </div>
                    <div class="col">
                    <p>Area: ${countryData[0].area} km²</p>
                    <p>Timezones: ${countryData[0].timezones}</p>
                    <p>Country Currency: ${currencyCode}</p>
                    <p>Language: ${Object.values(countryData[0].languages).join(', ')}</p>
                    
                    <p>Wikipedia: <a href="https://en.wikipedia.org/wiki/${countryData[0].name.common}" target="_blank">Link</a></p>
                    <p>Bordering Countries: ${countryData[0].borders.join(', ')}</p>
                    </div>
                    </div>
                    `;
                weatherContainer.style.display = 'block'; // Show the container



                if (currencyCode !== 'EUR') {
                    return fetchCurrencyRate(currencyCode, 'EUR').then(currencyRate => {
                        displayCurrencyInfo(`1 ${currencyCode} = ${currencyRate.conversion_rate} EUR`, currencyInfo);
                    });
                } else {
                    displayCurrencyInfo('1 EUR = 1 EUR', currencyInfo);
                    const currencyInfoDivShow = document.getElementById('currencyInfo');
                    currencyInfoDivShow.style.display = 'none';

                }
   

                console.log("Country Data:", countryData);

                const toastBody = document.querySelector('.toast-body');
                toastBody.textContent = 'Weather data loaded successfully';
                toastBody.closest('.toast').classList.add('show'); // Show toast
            })
            .catch(error => {
                console.error('Error:', error);
                displayError('Failed to load data');
            });

        });

        function fetchCurrencyRate(fromCurrency, toCurrency) {
    return fetch(`https://v6.exchangerate-api.com/v6/3efd5620afac285455617cb4/pair/${fromCurrency}/${toCurrency}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.conversion_rate) {
                return data;
            } else {
                throw new Error('Failed to retrieve currency data');
            }
        });
}

function displayCurrencyInfo(message, element) {
    if (element) {
        element.innerHTML = `
        <h3>Currency Information</h3>
        <p>${message}</p>`;
    }
    const currencyInfoDivShow = document.getElementById('currencyInfo');
    currencyInfoDivShow.style.display = 'block';
}

function displayError(message) {
    const toastBody = document.querySelector('.toast-body');
    toastBody.textContent = message;
    toastBody.closest('.toast').classList.add('show');
}   


</script>











</body>

</html>