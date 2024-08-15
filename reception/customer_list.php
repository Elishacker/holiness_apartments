<?php
include('db_connect.php');

// Get current page number and search query
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Number of entries to show per page
$offset = ($page - 1) * $limit; // Calculate offset for SQL query

$search_query = isset($_GET['search']) ? '%' . $conn->real_escape_string($_GET['search']) . '%' : '%';

// Query to get total number of signed-out customers with search
$total_query = "SELECT COUNT(*) as total FROM guestbook WHERE status = 'OUT' AND (name LIKE ? OR address LIKE ? OR room LIKE ? OR start_date LIKE ? OR end_date LIKE ?)";
$total_stmt = $conn->prepare($total_query);
$total_stmt->bind_param("sssss", $search_query, $search_query, $search_query, $search_query, $search_query);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_customers = $total_row['total'];
$total_pages = ceil($total_customers / $limit); // Calculate total pages

// Query to get signed-out customers for the current page with search
$sql = "SELECT * FROM guestbook WHERE status = 'OUT' AND (name LIKE ? OR address LIKE ? OR room LIKE ? OR start_date LIKE ? OR end_date LIKE ?) LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $search_query, $search_query, $search_query, $search_query, $search_query);
$stmt->execute();
$result = $stmt->get_result();

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Holiness | Signed Out Customer</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <style>
    .btn-view {
        background-color: #007bff;
        color: white;
        border: none;
    }
    .btn-signout {
        background-color: #dc3545;
        color: white;
        border: none;
    }
    .btn-view, .btn-signout {
        margin-right: 5px;
    }

    /* Center the table */
    .table-centered {
        margin-left: auto;
        margin-right: auto;
    }
    
    .pagination {
        justify-content: center;
    }
  </style>
  <script>
    function searchFunction() {
        const searchValue = document.getElementById('search-input').value;
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchValue);
        url.searchParams.set('page', 1); // Reset to first page on new search
        window.location.href = url.toString();
    }
  </script>
</head>
<body>

  <main id="main">
    <!-- Main Content -->
    <div class="container mt-4">
      <div class="row mb-3 d-flex align-items-center justify-content-center text-center">
        <div class="col-md-12 d-flex justify-content-center">
          <img src="../assets/img/log.png" alt="Logo" class="img-fluid" style="max-height: 180px;">
        </div>
        <div class="col-md-12 d-flex justify-content-center">
          <h1 class="m-0"><strong>HOLINESS APARTMENTS</strong></h1>
        </div>
        <div class="address col-12 text-center mt-2">
          <h5>P.O Box 2776, DODOMA | SIGNED OUT GUESTS</h5><br><br>
        </div>
      </div>

      <!-- Search Bar -->
      <div class="mb-3">
        <input type="text" id="search-input" class="form-control" placeholder="Search by any field..." oninput="searchFunction()" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
      </div>

      <!-- Guest List Table -->
      <table class="table table-bordered table-centered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Room</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo htmlspecialchars($row['room']); ?></td>
                <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center">No signed out customers found</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <nav>
        <ul class="pagination">
          <?php if ($page > 1): ?>
            <li class="page-item"><a class="page-link" href="?search=<?php echo urlencode(isset($_GET['search']) ? $_GET['search'] : ''); ?>&page=<?php echo $page - 1; ?>">Previous</a></li>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>"><a class="page-link" href="?search=<?php echo urlencode(isset($_GET['search']) ? $_GET['search'] : ''); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
          <?php endfor; ?>
          <?php if ($page < $total_pages): ?>
            <li class="page-item"><a class="page-link" href="?search=<?php echo urlencode(isset($_GET['search']) ? $_GET['search'] : ''); ?>&page=<?php echo $page + 1; ?>">Next</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </main><!-- End #main -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>
</html>
