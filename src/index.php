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
      <h1>Zadanie444444</h1>
        <button class="btn btn-primary" id="currWeather">Current weather</button>
        <button class="btn btn-primary" id="averageWeather">Average weather</button>
        
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

        


</script>











</body>

</html>