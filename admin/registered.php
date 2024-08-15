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
        header("Location: customer_list.php");
        exit();
    } else {
        echo "Error signing out customer: " . $stmt->error;
    }
}

// Get the current page or set a default
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

// Query to get the total number of customers
$total_sql = "SELECT COUNT(*) FROM guestbook";
$total_result = $conn->query($total_sql);
$total_customers = $total_result->fetch_row()[0];

// Query to get customers with limit and offset
$sql = "SELECT * FROM guestbook LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Calculate total pages
$total_pages = ceil($total_customers / $limit);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Guest List</title>

    <link href="assets/img/log.png" rel="icon">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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
        .table-container {
            position: relative;
        }
        .search-bar {
            margin-bottom: 20px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
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

        <!-- Search bar -->
        <div class="search-bar">
            <input type="text" id="searchInput" class="form-control" placeholder="Search for Bookings...">
        </div>

        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Room</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Time Remaining</th>
                    </tr>
                </thead>
                <tbody id="customerTableBody">
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
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No customers found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="customer_list.php?page=<?php echo $page-1; ?>">Previous</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>"><a class="page-link" href="customer_list.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item"><a class="page-link" href="customer_list.php?page=<?php echo $page+1; ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

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
