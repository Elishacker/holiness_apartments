
<?php
session_start();

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

// Function to sanitize and validate inputs
function validate_input($data, $type, $min_length = 1, $max_length = 255) {
    $data = htmlspecialchars(trim($data));
    if (strlen($data) < $min_length || strlen($data) > $max_length) {
        return false;
    }
    if ($type === 'email' && !filter_var($data, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return $data;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $first_name = validate_input($_POST['first_name'], 'string', 1, 100);
    $last_name = validate_input($_POST['last_name'], 'string', 1, 100);
    $address = validate_input($_POST['address'], 'string', 1, 255);
    $phone = validate_input($_POST['phone'], 'string', 10, 15); // Adjust min and max length for phone as needed
    $email = validate_input($_POST['email'], 'email', 5, 100);
    $password = validate_input($_POST['pass'], 'string', 6, 100); // Min length of 6 for password

    // Check if any input is invalid
    if (!$first_name || !$last_name || !$address || !$phone || ($email && !$email) || !$password) {
        $_SESSION['error_message'] = "Invalid input. Please check your entries and try again.";
        header("Location: signup.php");
        exit();
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if user already exists by phone number only
        $sql_check = "SELECT COUNT(*) FROM users WHERE phone = :phone";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':phone', $phone);
        $stmt_check->execute();
        $user_exists = $stmt_check->fetchColumn();

        if ($user_exists) {
            $_SESSION['error_message'] = "User with this phone number already exists.";
            header("Location: signup.php");
            exit();
        } else {
            // Prepare SQL statement
            $sql = "INSERT INTO users (first_name, last_name, address, phone, email, password, role)
                    VALUES (:first_name, :last_name, :address, :phone, :email, :password, 'user')";

            // Execute SQL statement
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->execute();

                // Redirect to login page with success message
                $_SESSION['success_message'] = "Registration successful! Please log in.";
                header("Location: login.php");
                exit();
            } catch (PDOException $e) {
                $_SESSION['error_message'] = "Registration failed: " . $e->getMessage();
                header("Location: signup.php");
                exit();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Holiness | Register Form</title>
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
    .container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 15px;
    }

    .custom-form {
        max-width: 500px;
        width: 100%;
    }

    .custom-form-group {
        margin-bottom: 0.75rem;
    }

    .form-group {
        margin-bottom: 0.5rem;
    }

    .form-control {
        padding: 0.5rem;
    }

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

    .back-to-top {
        position: fixed;
        visibility: hidden;
        opacity: 0;
        right: 15px;
        bottom: 15px;
        z-index: 99999;
        background: #28a745;
        width: 40px;
        height: 40px;
        border-radius: 4px;
        transition: all 0.4s;
    }

    .alert {
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 1rem;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .custom-fieldset {
        border: 1px solid #ddd;
        padding: 1rem;
        border-radius: 5px;
        margin-bottom: 1rem;
    }
  </style>

</head>

<body>

<main id="main">

  <!-- ======= Customer Registration ======= -->
  <section class="contact">
      <div class="container">
              <div class="row mb-3 d-flex align-items-center justify-content-center text-center">
                  <div class="col-md-12 d-flex justify-content-center">
                      <img src="admin/assets/img/log.png" alt="Logo" class="img-fluid" style="max-height: 180px;">
                  </div>
                  <div class="col-md-12 d-flex justify-content-center">
                      <h1 class="m-0"><strong>HOLINESS APARTMENTS</strong></h1>
                  </div>
                  <div class="address col-12 text-center mt-2">
                      <h5>P.O Box 2776, DODOMA | USER REGISTER FORM</h5><br>
                  </div>
              </div><br>

          <?php if (isset($_SESSION['error_message'])): ?>
              <div class="alert alert-danger text-center">
                  <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
              </div>
          <?php endif; ?>

          <?php if (isset($_SESSION['success_message'])): ?>
              <div class="alert alert-success text-center">
                  <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
              </div>
          <?php endif; ?>

          <form action="signup.php" method="post" role="form" class="custom-form">
              <div class="row custom-form-group">
                  <div class="col-md-6 mb-3">
                      <div class="form-group w-100">
                          <input type="text" name="first_name" class="form-control form-control-sm p-2" placeholder="First Name" required>
                      </div>
                  </div>
                  <div class="col-md-6 mb-3">
                      <div class="form-group w-100">
                          <input type="text" name="last_name" class="form-control form-control-sm p-2" placeholder="Last Name" required>
                      </div>
                  </div>
              </div>
              <div class="row custom-form-group">
                  <div class="col-md-6 mb-3">
                      <div class="form-group w-100">
                          <input type="text" name="address" class="form-control form-control-sm p-2" placeholder="Address eg. Dodoma" required>
                      </div>
                  </div>
                  <div class="col-md-6 mb-3">
                      <div class="form-group w-100">
                          <input type="tel" name="phone" class="form-control form-control-sm p-2" placeholder="0753xxxxxx" required>
                      </div>
                  </div>
              </div>

              <div class="row custom-form-group">
                  <div class="col-md-12 mb-3">
                      <div class="form-group w-100">
                          <input type="email" name="email" class="form-control form-control-sm p-2" placeholder="example@gmail.com (optional)">
                      </div>
                  </div>
                  <div class="col-md-12 mb-3">
                      <div class="form-group w-100">
                          <input type="password" name="pass" class="form-control form-control-sm p-2" placeholder="Password" required>
                      </div>
                  </div>
              </div>
              <div class="btn-a text-center">
                  <button type="submit" class="btn btn-sm btn-custom"><strong>Register</strong></button>
              </div>
              <div class="text-center mt-3">
                <p>Already registered? <a href="login.php">Log in here</a></p>
            </div>
          </form>
      </div>
  </section>
  <!-- End Customer Registration -->

</main><!-- End #main -->

<div id="preloader"></div>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>
