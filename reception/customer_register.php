<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $tribe = $_POST['tribe'];
    $job = $_POST['job'];
    $id_type = $_POST['id_type'];
    $id_no = $_POST['id_no'];
    $from = $_POST['from'];
    $to = $_POST['to'];
    $start_date = $_POST['start'];
    $end_date = $_POST['end'];
    $room = $_POST['room'];
    $number_of_guests = $_POST['number'];
    $phone = $_POST['phone'];
    $emergency_phone = $_POST['emergence'];
    $status = 'IN'; // Default status value

    $sql = "INSERT INTO guestbook (name, address, tribe, job, id_type, id_no, `from`, `to`, start_date, end_date, room, number_of_guests, phone, emergency_phone, status)
            VALUES ('$name', '$address', '$tribe', '$job', '$id_type', '$id_no', '$from', '$to', '$start_date', '$end_date', '$room', '$number_of_guests', '$phone', '$emergency_phone', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Customer recorded successfully'); window.location.href = 'index.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
