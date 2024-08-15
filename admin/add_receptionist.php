
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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize it
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $address = htmlspecialchars(trim($_POST['address']));
    $email = htmlspecialchars(trim($_POST['email']));
    $id_type = htmlspecialchars(trim($_POST['id_type']));
    $id_no = htmlspecialchars(trim($_POST['id_no']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $next_kin = htmlspecialchars(trim($_POST['next_kin']));

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement
    $sql = "INSERT INTO receptionist (first_name, last_name, address, email, id_type, id_no, phone, next_kin)
            VALUES (:first_name, :last_name, :address, :email, :id_type, :id_no, :phone, :next_kin)";

    // Execute SQL statement
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id_type', $id_type);
        $stmt->bindParam(':id_no', $id_no);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':next_kin', $next_kin);
        $stmt->execute();
        
        // Redirect or give feedback to the user
        header('Location: receptionist.php');
    } catch (PDOException $e) {
        echo "<p>Registration failed: " . $e->getMessage() . "</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Holiness | Register Receptionist</title>
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
  <!-- Custom CSS -->
  <style>
    .btn-custom {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-custom:hover {
      background-color: #218838;
    }

    .custom-fieldset,
    .custom-form {
      border: 1px solid #ccc;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-control:focus {
      border-color: #28a745;
      box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
    }

    .contact {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .contact img {
      max-width: 100%;
      height: auto;
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->

  <!-- End Header -->

  <main id="main">

    <!-- ======= Customer Registration ======= -->
    <section class="contact">
      <div class="container">
        <fieldset class="custom-fieldset">
          <div class="row mb-3 d-flex align-items-center justify-content-center text-center">
            <div class="col-12 col-md-2 d-flex justify-content-center">
              <img src="assets/img/log.png" alt="Logo" class="img-fluid" style="max-width: 180px;">
            </div>
            <div class="col-12 col-md-10 d-flex justify-content-center">
              <h1 class="m-0"><strong>HOLINESS APARTMENTS</strong></h1>
            </div>
            <div class="address col-12 text-center mt-2">
              <h5>P.O Box 2776, DODOMA | RECEPTIONIST REGISTER FORM</h5>
            </div>
          </div>
        </fieldset>

        <form action="add_receptionist.php" method="post" role="form" class="custom-form">
          <div class="row custom-form-group">
            <div class="col-12 col-md-6 mb-3">
              <div class="form-group w-100">
                <input type="text" name="first_name" class="form-control form-control-sm" placeholder="First Name" required>
              </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <div class="form-group w-100">
                <input type="text" name="last_name" class="form-control form-control-sm" placeholder="Last Name" required>
              </div>
            </div>
          </div>
          <div class="row custom-form-group">
            <div class="col-12 col-md-6 mb-3">
              <div class="form-group w-100">
                <input type="text" name="address" class="form-control form-control-sm" placeholder="Address" required>
              </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <div class="form-group w-100">
                <input type="email" name="email" class="form-control form-control-sm" placeholder="Email" required>
              </div>
            </div>
          </div>
          <div class="row custom-form-group">
            <div class="col-12 col-md-6 mb-3">
              <div class="form-group w-100">
                <select name="id_type" class="form-control form-control-sm" required>
                  <option value="" disabled selected>Select ID Type</option>
                  <option value="NIDA">NIDA</option>
                  <option value="Voter">Voter</option>
                  <option value="Driving">Driving</option>
                  <option value="Passport">Passport</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <div class="form-group w-100">
                <input type="text" name="id_no" class="form-control form-control-sm" placeholder="ID Number" required>
              </div>
            </div>
          </div>
          <div class="row custom-form-group">
            <div class="col-12 col-md-6 mb-3">
              <div class="form-group w-100">
                <input type="tel" name="phone" class="form-control form-control-sm" placeholder="Phone Number" required>
              </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <div class="form-group w-100">
                <input type="tel" name="emergence" class="form-control form-control-sm" placeholder="Kin Contact" required>
              </div>
            </div>
          </div>

          <div class="form-group mt-3 text-center">
            <button class="btn btn-custom w-50 w-md-25" type="submit">Register</button>
          </div>
        </form>
      </div>
    </section>
    <!-- End Customer Registration -->
  </main>
  <!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
