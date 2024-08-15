<?php
include('db_connect.php');

// Get the current page number from the query string
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Number of entries to show in a page.
$offset = ($page - 1) * $limit; // Calculate offset for SQL query

// Query to get the total number of customers
$total_query = "SELECT COUNT(*) as total FROM Customers";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_customers = $total_row['total'];
$total_pages = ceil($total_customers / $limit); // Calculate total pages

// Query to get customers for the current page
$sql = "SELECT * FROM Customers LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Holiness | Customers</title>
  <meta content="" name="description">
  <meta content="" name="keywords">


  <link href="assets/img/log.png" rel="icon">

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  
  <style>
    .btn-view {
        background-color: #007bff;
        color: white;
        border: none;
    }
    .btn-signout {
        background-color: #28a745;
        color: white;
        border: none;
    }
    .btn-view, .btn-signout {
        margin-right: 5px;
    }
    .table-centered {
        margin-left: auto;
        margin-right: auto;
    }
    /* Search bar styling */
    .search-bar {
        margin-bottom: 20px;
        width: 100%;
        text-align: right;
    }
    /* Centering the input field */
    .search-bar input {
        width: 200px;
    }
  </style>
</head>
<body>

  <main id="main">
    <div class="container mt-4">
        <div class="row mb-3 d-flex align-items-center justify-content-center text-center">
            <div class="col-md-12 d-flex justify-content-center">
                <img src="../assets/img/log.png" alt="Logo" class="img-fluid" style="max-height: 180px;">
            </div>
            <div class="col-md-12 d-flex justify-content-center">
                <h1 class="m-0"><strong>HOLINESS APARTMENTS</strong></h1>
            </div>
            <div class="address col-12 text-center mt-2">
                <h5>P.O Box 2776, DODOMA | CUSTOMERS LIST</h5><br><br>
            </div>
        </div>
        
        <!-- Search bar -->
        <div class="search-bar">
            <input type="text" id="searchInput" class="form-control" placeholder="Search for customers...">
        </div>
        
        <table class="table table-bordered table-centered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Emergency Contact</th>
                </tr>
            </thead>
            <tbody id="customerTableBody">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['customer_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['emergency_contact']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No customers found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php if($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
  </main>

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="assets/js/main.js"></script>
  
  <script>
    document.getElementById('searchInput').addEventListener('input', function() {
        let input = this.value.toLowerCase();
        let rows = document.querySelectorAll('#customerTableBody tr');
        
        rows.forEach(row => {
            let cells = row.querySelectorAll('td');
            let match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(input));
            row.style.display = match ? '' : 'none';
        });
    });
  </script>
</body>
</html>
