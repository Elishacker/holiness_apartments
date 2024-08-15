<?php
// Database connection parameters
$host = 'localhost'; // or your host
$dbname = 'holiness';
$username = 'root'; // your database username
$password = ''; // your database password

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Pagination settings
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Fetch total number of records
$total = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$pages = ceil($total / $limit);

// Fetch data from the users table with limit
$sql = "SELECT * FROM users LIMIT :start, :limit";
try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':start', $start, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Data retrieval failed: " . $e->getMessage());
}

// Handle role change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id']) && isset($_POST['new_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];
    $update_sql = "UPDATE users SET role = :new_role WHERE id = :user_id";
    try {
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':new_role', $new_role);
        $update_stmt->bindParam(':user_id', $user_id);
        $update_stmt->execute();
        header('Location: ' . $_SERVER['PHP_SELF'] . '?page=' . $page);
        exit;
    } catch (PDOException $e) {
        die("Role update failed: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Holiness | Users</title>

    <link href="assets/img/log.png" rel="icon">

    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Container Styling */
        .container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }

        /* Header Actions Styling */
        .header-actions {
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            margin-bottom: 20px;
            gap: 10px;
        }

        /* Button Styling */
        .btn-add {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn-add:hover {
            background-color: #28a745;
        }

        .btn-role {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-role:hover {
            background-color: #28a745;
        }

        /* Search Bar Styling */
        .search-bar {
            width: 200px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #28a745;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        h1 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #012970;
        }

        /* Pagination Styling */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 10px 20px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #28a745;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }

        .pagination a:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>

<body>


  <main id="main">

    <div class="container">
        <div class="row mb-3 d-flex align-items-center justify-content-center text-center">
            <div class="col-md-12 d-flex justify-content-center">
                <img src="../assets/img/log.png" alt="Logo" class="img-fluid" style="max-height: 180px;">
            </div>
            <div class="col-md-12 d-flex justify-content-center">
                <h1 class="m-0"><strong>HOLINESS APARTMENTS</strong></h1>
            </div>
            <div class="address col-12 text-center mt-2">
                <h5>P.O Box 2776, DODOMA | USERS LIST</h5><br><br>
            </div>
        </div>
        
      <div class="header-actions">
          <a href="#" class="btn-add">ADD</a>
          <input type="text" id="search" class="search-bar form-control" placeholder="Search...">
      </div>

      <table class="table" id="customerTable">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Address</th>
                  <th>Phone</th>
                  <th>Role/Rights</th>
                  <th>Actions</th>
              </tr>
          </thead>
          <tbody>
              <?php if (!empty($customers)): ?>
                  <?php foreach ($customers as $customer): ?>
                      <tr>
                          <td><?php echo htmlspecialchars($customer['id']); ?></td>
                          <td><?php echo htmlspecialchars($customer['first_name']); ?></td>
                          <td><?php echo htmlspecialchars($customer['last_name']); ?></td>
                          <td><?php echo htmlspecialchars($customer['address']); ?></td>
                          <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                          <td><?php echo htmlspecialchars($customer['role']); ?></td>
                          <td>
                              <form method="POST" style="display:inline;">
                                  <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($customer['id']); ?>">
                                  <input type="hidden" name="new_role" value="Receptionist">
                                  <button type="submit" class="btn-role">Receptionist</button>
                              </form>
                              <form method="POST" style="display:inline;">
                                  <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($customer['id']); ?>">
                                  <input type="hidden" name="new_role" value="Admin">
                                  <button type="submit" class="btn-role">Admin</button>
                              </form>
                          </td>
                      </tr>
                  <?php endforeach; ?>
              <?php else: ?>
                  <tr>
                      <td colspan="8" class="text-center">No customers found</td>
                  </tr>
              <?php endif; ?>
          </tbody>
      </table>

      <div class="pagination">
          <?php for ($i = 1; $i <= $pages; $i++): ?>
              <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
          <?php endfor; ?>
      </div>

    </div>

  </main><!-- End #main -->

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
      document.getElementById('search').addEventListener('input', function() {
          var searchTerm = this.value.toLowerCase();
          var rows = document.querySelectorAll('#customerTable tbody tr');

          rows.forEach(function(row) {
              var cells = row.querySelectorAll('td');
              var rowContainsSearchTerm = Array.from(cells).some(function(cell) {
                  return cell.textContent.toLowerCase().includes(searchTerm);
              });
              row.style.display = rowContainsSearchTerm ? '' : 'none';
          });
      });
  </script>

</body>

</html>
