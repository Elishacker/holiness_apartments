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

// Function to sanitize and validate inputs
function validate_input($data, $type) {
    $data = htmlspecialchars(trim($data));
    if ($type === 'phone' && !preg_match('/^[0-9]{10,15}$/', $data)) {
        return false;
    }
    return $data;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = validate_input($_POST['phone'], 'phone');
    $password = htmlspecialchars(trim($_POST['pass']));

    // Check if inputs are valid
    if (!$phone || !$password) {
        $error = "Invalid input. Please check your entries and try again.";
    } else {
        // Check if user exists
        $sql_check = "SELECT * FROM users WHERE phone = :phone";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':phone', $phone);
        $stmt_check->execute();
        $user = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Start session and set user role
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            switch ($user['role']) {
                case 'Admin':
                    header('Location: admin/index.php');
                    break;
                case 'Receptionist':
                    header('Location: reception/index.php');
                    break;
                case 'user':
                    header('Location: dashboard/index.php');
                    break;
                default:
                    header('Location: login.php?status=error');
                    break;
            }
            exit;
        } else {
            $error = "Invalid phone number or password.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Holiness | Login</title>
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

    .error-message {
        color: red;
        margin-bottom: 1rem;
    }

    .success-message {
        color: green;
        margin-bottom: 1rem;
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
                    <h5>P.O Box 2776, DODOMA | LOGIN FORM</h5>
                </div>
            </div>
        
        <form action="login.php" method="post" role="form" class="custom-form">
            <?php if (isset($error)) : ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <div class="address col-12 text-center mt-2"><br>
                <h1>LOGIN HERE</h1>
            </div><br>

            <div class="row custom-form-group">
                <div class="col-md-12 mb-3">
                    <div class="form-group w-100">
                        <input type="tel" name="phone" class="form-control form-control-sm p-2" placeholder="0753xxxxxx" required>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="form-group w-100">
                        <input type="password" name="pass" class="form-control form-control-sm p-2" placeholder="Password" required>
                    </div>
                </div>
            </div>
            <div class="btn-a text-center">
                <button type="submit" class="btn btn-sm btn-custom"><strong>Login</strong></button>
            </div>
            <div class="text-center mt-3">
                <p>Not registered yet? <a href="signup.php">Register here</a></p>
            </div>
        </form>
    </div>
  </section>
</main>

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
