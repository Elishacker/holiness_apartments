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

// Function to calculate total price
function calculateTotalPrice($fromDate, $toDate, $category) {
    $startDate = new DateTime($fromDate);
    $endDate = new DateTime($toDate);
    $interval = $startDate->diff($endDate);
    $days = $interval->days;
    $pricePerDay = ($category === 'Custom') ? 80000 : 100000;
    return $days * $pricePerDay;
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $from = $_POST['from'];
    $to = $_POST['to'];
    $block = $_POST['block'];
    $room = $_POST['room'];
    $category = $_POST['category'];
    $phone = $_POST['phone'];
    $emergence = $_POST['emergence'];
    $transactionId = $_POST['transaction_id'];
    $totalAmount = calculateTotalPrice($from, $to, $category);

    try {
        // Begin a transaction
        $pdo->beginTransaction();

        // Insert customer data
        $stmt = $pdo->prepare("INSERT INTO Customers (first_name, last_name, phone, emergency_contact) VALUES (?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $phone, $emergence]);
        $customerId = $pdo->lastInsertId();

        // Insert apartment data (block and room combination)
        $stmt = $pdo->prepare("INSERT INTO Apartments (block, room, category) VALUES (?, ?, ?)");
        $stmt->execute([$block, $room, $category]);
        $apartmentId = $pdo->lastInsertId();

        // Insert booking data
        $stmt = $pdo->prepare("INSERT INTO Bookings (customer_id, apartment_id, date_from, date_to, total_amount) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$customerId, $apartmentId, $from, $to, $totalAmount]);
        $bookingId = $pdo->lastInsertId();

        // Insert payment data
        $stmt = $pdo->prepare("INSERT INTO Payments (booking_id, transaction_id, amount) VALUES (?, ?, ?)");
        $stmt->execute([$bookingId, $transactionId, $totalAmount]);

        // Commit the transaction
        $pdo->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Roll back the transaction if something failed
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    
}
?>
