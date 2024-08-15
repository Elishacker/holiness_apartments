<?php
include('db_connect.php');

// Get the current page number from the query string, default to 1 if not set
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$records_per_page = 5;
$offset = ($page - 1) * $records_per_page;

// Query to get total number of records
$total_sql = "SELECT COUNT(*) FROM Payments";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_row();
$total_records = $total_row[0];
$total_pages = ceil($total_records / $records_per_page);

// Query to get signed-out customers with limit and offset
$sql = "SELECT * FROM Payments LIMIT $records_per_page OFFSET $offset";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Holiness | Customers' Payments</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/log.png" rel="icon">

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

        /* Flat table style */
        .table-flat {
            border-collapse: collapse;
            width: 100%;
        }
        .table-flat th, .table-flat td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .table-flat th {
            background-color: #f2f2f2;
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

        /* Search bar style */
        .search-bar {
            margin-bottom: 20px;
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
                <input type="text" id="searchInput" class="form-control" placeholder="Search for Payments...">
            </div>

            <table class="table table-bordered table-flat table-centered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>BookingID</th>
                        <th>TransactionID</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody id="paymentTableBody">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['payment_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['amount']); ?></td>
                                <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No payments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
                <?php else: ?>
                    <a href="#" class="disabled">&laquo; Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                <?php else: ?>
                    <a href="#" class="disabled">Next &raquo;</a>
                <?php endif; ?>
            </div>
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

    <!-- Real-time Search Functionality -->
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            let input = this.value.toLowerCase();
            let rows = document.querySelectorAll('#paymentTableBody tr');
            
            rows.forEach(row => {
                let cells = row.querySelectorAll('td');
                let match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(input));
                row.style.display = match ? '' : 'none';
            });
        });
    </script>

</body>

</html>
