<?php
include('db_connect.php');

// Get the current page or set a default
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10; // Number of records per page
$offset = ($page - 1) * $limit;

// Query to get the total number of signed-out customers
$total_sql = "SELECT COUNT(*) FROM guestbook WHERE status = 'OUT'";
$total_result = $conn->query($total_sql);
$total_customers = $total_result->fetch_row()[0];

// Query to get signed-out customers with pagination
$sql = "SELECT * FROM guestbook WHERE status = 'OUT' LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total pages
$total_pages = ceil($total_customers / $limit);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Holiness | Signed Out Customers</title>

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
            width: 100%;
        }
        /* Flat table style */
        .table-flat {
            border-collapse: collapse;
            width: 100%;
        }
        .table-flat th, .table-flat td {
            padding: 10px;
            text-align: left;
            border: none; /* Remove borders */
        }
        .table-flat th {
            background-color: #f8f9fa; /* Light background for headers */
            font-weight: bold;
        }
        .table-flat tr:nth-child(even) {
            background-color: #f2f2f2; /* Zebra striping for rows */
        }
        .table-flat tr:hover {
            background-color: #e9ecef; /* Highlight row on hover */
        }
        /* Search bar styling */
        .search-bar {
            margin-bottom: 20px;
            width: 100%;
        }
        /* Pagination style */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 10px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #007bff;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
        }
        .pagination a.disabled {
            color: #ccc;
            pointer-events: none;
        }
    </style>
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
                    <h5>P.O Box 2776, DODOMA | SIGNED GUESTS</h5><br><br>
                </div>
            </div>

            <!-- Search bar -->
            <div class="search-bar">
                <input type="text" id="searchInput" class="form-control" placeholder="Search for Signed out guests...">
            </div>

            <table class="table table-bordered table-flat table-centered">
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
                <tbody id="customerTableBody">
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
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a></li>
                    <?php else: ?>
                        <li class="page-item disabled"><span class="page-link">Previous</span></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a></li>
                    <?php else: ?>
                        <li class="page-item disabled"><span class="page-link">Next</span></li>
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
