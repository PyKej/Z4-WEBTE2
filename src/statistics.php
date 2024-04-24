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


  
  <div class="container mt-5">
    <table id="searchResultsTable" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Destination</th>
                <th>Country</th>
                <th>Search Count</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded here dynamically -->
        </tbody>
    </table>
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
$('#searchResultsTable').DataTable({
    "ajax": {
        "url": "/src/api/api.php/getSearchStats",
        "dataSrc": function(json) {
            console.log(json);  // Log the JSON to see what is actually returned
            return json;
        }
    },
    "columns": [
        { "data": "destination" },
        { "data": "country" },
        { "data": "search_count" }
    ]
});


</script>











</body>

</html>