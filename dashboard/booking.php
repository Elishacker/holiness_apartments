<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Holiness | Booking Form</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/assets/img/log.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
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
  <main id="main">
    <section class="contact">
      <div class="container">
      <div class="row mb-3 d-flex align-items-center justify-content-center text-center">
                <div class="col-md-12 d-flex justify-content-center">
                    <img src="assets/assets/img/log.png" alt="Logo" class="img-fluid" style="max-height: 180px;">
                </div>
                <div class="col-md-12 d-flex justify-content-center">
                    <h1 class="m-0"><strong>HOLINESS APARTMENTS</strong></h1>
                </div>
                <div class="address col-12 text-center mt-2">
                    <h5>P.O Box 2776, DODOMA | BOOKING FORM</h5><br><br>
                </div>
            </div>

        <form id="bookingForm" action="process_payment.php" method="post" role="form" class="custom-form">
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
                <label for="date">From</label>
                <input type="date" name="from" id="fromDate" class="form-control form-control-sm" required>
              </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <div class="form-group w-100">
                <label for="date">To</label>
                <input type="date" name="to" id="toDate" class="form-control form-control-sm" required>
              </div>
            </div>
          </div>
          <div class="row custom-form-group">
            <div class="col-12 col-md-3 mb-3">
              <div class="form-group w-100">
                <select name="block" id="block" class="form-control form-control-sm" required>
                  <option value="" disabled selected>Select Block</option>
                  <option value="HA">HA</option>
                  <option value="HB">HB</option>
                  <option value="HC">HC</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-3 mb-3">
              <div class="form-group w-100">
                <select name="room" id="room" class="form-control form-control-sm" required>
                  <option value="" disabled selected>Select Room</option>
                  <option value="HA-1">HA-1</option>
                  <option value="HA-2">HA-2</option>
                  <option value="HB-1">HB-1</option>
                  <option value="HB-2">HB-2</option>
                  <option value="HC-1">HC-1</option>
                  <option value="HC-2">HC-2</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
              <div class="form-group w-100">
                <select name="category" id="category" class="form-control form-control-sm" required>
                  <option value="" disabled selected>Select Category of Apartment</option>
                  <option value="Custom">Custom</option>
                  <option value="Extra">Extra</option>
                </select>
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
                <input type="tel" name="emergence" class="form-control form-control-sm" placeholder="Emergence Contact" required>
              </div>
            </div>
          </div>

          <div class="form-group mt-3 text-center">
            <button class="btn btn-custom" type="button" onclick="showPaymentModal()">Book</button>
          </div>
        </form>
      </div>
    </section>
  </main>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paymentModalLabel">Payment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p id="paymentDetails"></p>
          <div class="mb-3">
            <label for="transactionId" class="form-label">Transaction ID</label>
            <input type="text" class="form-control" id="transactionId" placeholder="Enter Transaction ID" required>
          </div>
          <div class="alert alert-info">
            <strong>LIPA KWA M-PESA:</strong>
            <p>Go to M-PESA Menu -> LIPA NA M-PESA -> Enter Business Number -> Enter Reference Number -> Enter Amount -> Enter PIN -> Confirm.</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="submitPayment()">Submit Payment</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script>
    function calculateTotalPrice(fromDate, toDate, category) {
      const startDate = new Date(fromDate);
      const endDate = new Date(toDate);
      const days = (endDate - startDate) / (1000 * 60 * 60 * 24);
      const pricePerDay = (category === 'Custom') ? 80000 : 100000;
      return days * pricePerDay;
    }

    function showPaymentModal() {
      const fromDate = document.getElementById('fromDate').value;
      const toDate = document.getElementById('toDate').value;
      const category = document.getElementById('category').value;

      if (fromDate && toDate && category) {
        const totalPrice = calculateTotalPrice(fromDate, toDate, category);
        document.getElementById('paymentDetails').textContent = `Total Price to Pay: ${totalPrice} TZS`;

        const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'), {
          keyboard: false
        });
        paymentModal.show();
      } else {
        alert('Please fill out all required fields.');
      }
    }

    function submitPayment() {
      const transactionId = document.getElementById('transactionId').value;
      const bookingForm = document.getElementById('bookingForm');
      if (transactionId) {
        const formData = new FormData(bookingForm);
        formData.append('transaction_id', transactionId);
        formData.append('amount', document.getElementById('paymentDetails').textContent.split(': ')[1].split(' ')[0]);
        
        fetch('process_payment.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Payment successful!');
            window.location.reload();
          } else {
            alert('Payment failed. Please try again.');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred. Please try again.');
        });
      } else {
        alert('Please enter the transaction ID.');
      }
    }
  </script>
</body>

</html>
