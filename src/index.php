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

        document.getElementById('currWeather').addEventListener('click', function() {
            fetch('/src/api/api.php/currWeather', {
                method: 'GET',
                headers: {'Accept': 'application/json'}
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Assuming your server responds with JSON
            })
            .then(data => {
                console.log("Data curled:", data);
                loadedData = data.main.temp;

                console.log(loadedData);
                document.querySelector('.toast-body').textContent = data.message; // Set message text
                document.querySelector('.toast').classList.add('show'); // Show toast
            })
            .catch(error => {
                console.error('Error loading data:', error);
            });
        });

        document.getElementById('averageWeather').addEventListener('click', function() {
            fetch('/src/api/api.php/averageWeather', {
                method: 'GET',
                headers: {'Accept': 'application/json'}
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Assuming your server responds with JSON
            })
            .then(data => {
                console.log("Data curled:", data);
                // loadedData = data.main.temp;

                // console.log(loadedData);
                document.querySelector('.toast-body').textContent = data.message; // Set message text
                document.querySelector('.toast').classList.add('show'); // Show toast
            })
            .catch(error => {
                console.error('Error loading data:', error);
            });
        });

    //      // Adding listener to the form
    // document.getElementById('vacationForm').addEventListener('submit', function(event) {
    //     event.preventDefault(); // Prevent the default form submission
    //     fetch('/src/api/api.php/weather', {
    //         method: 'GET',
    //         headers: {'Accept': 'application/json'}
    //     })
    //     .then(response => {
    //         if (!response.ok) {
    //             throw new Error('Network response was not ok');
    //         }
    //         return response.json();
    //     })
    //     .then(data => {
    //         console.log("Weather Data:", data);
    //         document.querySelector('.toast-body').textContent = 'Weather data loaded successfully';
    //         document.querySelector('.toast').classList.add('show'); // Show toast
    //     })
    //     .catch(error => {
    //         console.error('Error loading weather data:', error);
    //         document.querySelector('.toast-body').textContent = 'Failed to load weather data';
    //         document.querySelector('.toast').classList.add('show');
    //     });
    // });

    // document.getElementById('vacationForm').addEventListener('submit', function(event) {
    //     event.preventDefault(); // Prevent the default form submission
        
    //     // First, fetch current weather
    //     fetch('/src/api/api.php/currWeather', {
    //         method: 'GET',
    //         headers: {'Accept': 'application/json'}
    //     })
    //     .then(response => {
    //         if (!response.ok) {
    //             throw new Error('Network response was not ok');
    //         }
    //         return response.json();
    //     })
    //     .then(data => {
    //         console.log("Current Weather Data:", data);
    //         // Assuming latitude and longitude are part of the returned 'data'
    //         // const latitude = data.latitude;
    //         // const longitude = data.longitude;

    //         const latitude = 49.153495;
    //         const longitude = 20.425547;

    //         // Now fetch average weather using the retrieved latitude and longitude
    //         return fetch(`/src/api/api.php/averageWeather?latitude=${latitude}&longitude=${longitude}`, {
    //             method: 'GET',
    //             headers: {'Accept': 'application/json'}
    //         });
    //     })
    //     .then(response => {
    //         if (!response.ok) {
    //             throw new Error('Network response was not ok');
    //         }
    //         return response.json();
    //     })
    //     .then(averageWeatherData => {
    //         console.log("Average Weather Data:", averageWeatherData);
    //         document.querySelector('.toast-body').textContent = 'Weather data loaded successfully';
    //         document.querySelector('.toast').classList.add('show'); // Show toast
    //     })
    //     .catch(error => {
    //         console.error('Error loading weather data:', error);
    //         document.querySelector('.toast-body').textContent = 'Failed to load weather data';
    //         document.querySelector('.toast').classList.add('show');
    //     });
    // });


    document.getElementById('vacationForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent default form submission
      
      const fetchWeatherData = (endpoint, params = '') => fetch(`/src/api/api.php/${endpoint}${params}`, {
          method: 'GET',
          headers: {'Accept': 'application/json'}
      })
      .then(response => {
          if (!response.ok) throw new Error('Network response was not ok');
          return response.json();
      });

      fetchWeatherData('currWeather')
      .then(data => {
          console.log("Current Weather Data:", data);
          const latitude = 49.153495; // Assuming these are meant to be dynamic or fetched from 'data'
          const longitude = 20.425547;
          return fetchWeatherData('averageWeather', `?latitude=${latitude}&longitude=${longitude}`);
      })
      .then(averageWeatherData => {
          console.log("Average Weather Data:", averageWeatherData);
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