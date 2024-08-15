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

// Fetch data from the receptionist table
$sql = "SELECT * FROM users WHERE role='Receptionist'";
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Data retrieval failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Holiness | Receptionist</title>
    
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
    flex-direction: row; /* Align items horizontally */
    justify-content: flex-end; /* Align items to the right */
    margin-bottom: 20px;
    gap: 10px; /* Optional: Adds space between the button and the search bar */
}

/* Button Styling */
.btn-add {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 10px 20px; /* Adjust padding for better appearance */
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.btn-add:hover {
    background-color: #218838;
}

/* Search Bar Styling */
.search-bar {
    width: 200px;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

th, td {
    padding: 12px; /* Increase padding for readability */
    text-align: left;
    border-bottom: 1px solid #ddd; /* Flat border styling */
}

th {
    background-color: #f8f9fa;
    font-weight: 600;
}

tr:nth-child(even) {
    background-color: #f2f2f2; /* Alternate row color */
}

tr:hover {
    background-color: #e9ecef; /* Highlight row on hover */
}

h1 {
    margin-bottom: 20px;
    text-align: center; /* Center align the heading */
    font-size: 24px;
    font-weight: 700;
    color: #333; /* Slightly darker color for better contrast */
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
                <h5>P.O Box 2776, DODOMA | RECEPTIONIST LIST</h5><br><br>
            </div>
        </div>
      <div class="header-actions">
          <a href="#" class="btn-add">ADD</a>
          <input type="text" id="search" class="search-bar form-control" placeholder="Search...">
      </div>

      <table class="table" id="userTable">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Address</th>
                  <th>Phone</th>
                  <th>Role/Rights</th>
              </tr>
          </thead>
          <tbody>
              <?php if (!empty($users)): ?>
                  <?php foreach ($users as $user): ?>
                      <tr>
                          <td><?php echo htmlspecialchars($user['id']); ?></td>
                          <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                          <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                          <td><?php echo htmlspecialchars($user['address']); ?></td>
                          <td><?php echo htmlspecialchars($user['phone']); ?></td>
                          <td><?php echo htmlspecialchars($user['role']); ?></td>
                      </tr>
                  <?php endforeach; ?>
              <?php else: ?>
                  <tr>
                      <td colspan="8" class="text-center">No users found</td>
                  </tr>
              <?php endif; ?>
          </tbody>
      </table>
    </div>

  </main><!-- End #main -->

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
      document.getElementById('search').addEventListener('input', function() {
          var searchTerm = this.value.toLowerCase();
          var rows = document.querySelectorAll('#userTable tbody tr');

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
