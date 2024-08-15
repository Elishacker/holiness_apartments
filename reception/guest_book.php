<?php
include('db_connect.php');

// Define current time globally for use in functions
$current_time = new DateTime();

// Function to get time remaining from start date to end date
function timeRemaining($start_time, $end_time, $current_time) {
    $start = new DateTime($start_time);
    $end = new DateTime($end_time);

    if ($end > $current_time) {
        // Calculate time remaining from now to end date
        $total_remaining_seconds = ($end->getTimestamp() - $current_time->getTimestamp());
    } else {
        // Calculate time elapsed from start date to end date
        $total_remaining_seconds = 0; // Time has elapsed, no remaining time
    }

    $remaining_days = floor($total_remaining_seconds / 86400);
    $remaining_hours = floor(($total_remaining_seconds % 86400) / 3600);
    $remaining_minutes = floor(($total_remaining_seconds % 3600) / 60);
    return array('days' => $remaining_days, 'hours' => $remaining_hours, 'minutes' => $remaining_minutes);
}

// Handle sign out request
if (isset($_GET['signout']) && isset($_GET['id'])) {
    $customer_id = intval($_GET['id']);
    
    // Prepare SQL statement to update status to 'OUT'
    $signout_sql = "UPDATE guestbook SET status = 'OUT' WHERE id = ?";
    $stmt = $conn->prepare($signout_sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    
    $stmt->bind_param("i", $customer_id);
    if ($stmt->execute()) {
        header("Location: guest_book.php");
        exit();
    } else {
        echo "Error signing out customer: " . $stmt->error;
    }
}

// Get current page number and search query
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Number of entries to show per page
$offset = ($page - 1) * $limit; // Calculate offset for SQL query

$search_query = isset($_GET['search']) ? '%' . $conn->real_escape_string($_GET['search']) . '%' : '%';

// Query to get total number of customers with search
$total_query = "SELECT COUNT(*) as total FROM guestbook WHERE name LIKE ? OR address LIKE ? OR room LIKE ? OR start_date LIKE ? OR end_date LIKE ?";
$total_stmt = $conn->prepare($total_query);
$total_stmt->bind_param("sssss", $search_query, $search_query, $search_query, $search_query, $search_query);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_customers = $total_row['total'];
$total_pages = ceil($total_customers / $limit); // Calculate total pages

// Query to get customers for the current page with search
$sql = "SELECT * FROM guestbook WHERE name LIKE ? OR address LIKE ? OR room LIKE ? OR start_date LIKE ? OR end_date LIKE ? LIMIT $limit OFFSET $offset";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="assets/img/log.png" rel="icon" height="40px" width="40px">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

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


  <div class="container mt-4"><br><br><br>

        <div class="row mb-3 d-flex align-items-center justify-content-center text-center">
            <div class="col-md-12 d-flex justify-content-center">
                <img src="../assets/img/log.png" alt="Logo" class="img-fluid" style="max-height: 180px;">
            </div>
            <div class="col-md-12 d-flex justify-content-center">
                <h1 class="m-0"><strong>HOLINESS APARTMENTS</strong></h1>
            </div>
            <div class="address col-12 text-center mt-2">
                <h5>P.O Box 2776, DODOMA | REGISTERED GUESTS</h5><br><br>
            </div>
        </div>
        <!-- Search Bar -->
        <div class="mb-3">
            <input type="text" id="search-input" class="form-control" placeholder="Search by any field..." oninput="searchFunction()" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </div>

        <!-- Guest List Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Room</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Time Remaining</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <?php
                        $start_date = $row['start_date'];
                        $end_date = $row['end_date'];
                        $status = isset($row['status']) ? $row['status'] : 'IN';
                        $time_remaining = ($status !== 'OUT') ? timeRemaining($start_date, $end_date, $current_time) : array('days' => 0, 'hours' => 0, 'minutes' => 0);
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['room']); ?></td>
                            <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                            <td>
                                <?php echo $time_remaining['days'] . ' days ' . $time_remaining['hours'] . ' hrs ' . $time_remaining['minutes'] . ' min'; ?>
                            </td>
                            <td>
                                <?php if ($status !== 'OUT'): ?>
                                    <?php if ($time_remaining['days'] == 0 && $time_remaining['hours'] == 0 && $time_remaining['minutes'] == 0): ?>
                                        <a href="guest_book.php?signout=<?php echo htmlspecialchars($row['id']); ?>&id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-signout">Sign Out</a>
                                    <?php else: ?>
                                        Not Yet Eligible for Sign Out
                                    <?php endif; ?>
                                <?php else: ?>
                                    OUT
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No customers found</td>
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
</body>
</html>
