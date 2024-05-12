<?php
    include('config.php');
    session_start();
    $user_id = $_SESSION['user_id'];

    // Initialize search variable
    $search = '';
    $status = '';

    // Check if the search parameter is set in the URL
    if(isset($_GET['search'])) {
        $search = $_GET['search'];
        $sql = "SELECT * FROM tblbuyer WHERE transaction_no LIKE '%$search%' AND buyer_id=$user_id";
        $result = mysqli_query($conn, $sql);
    } else {
        // If no search parameter, fetch all records
        $sql = "SELECT * FROM tblbuyer WHERE buyer_id=$user_id";
        $result = mysqli_query($conn, $sql);
    }

    // Check if the delete parameter is set in the URL
    if(isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];
        $conid = $_GET['concert_id'];

        $seatNameQuery = "SELECT buyer_chosenseats FROM tblbuyer WHERE transaction_no = '$deleteId' AND buyer_id=$user_id";
        $seatNameResult = mysqli_query($conn, $seatNameQuery);
        $seatNameRow = mysqli_fetch_assoc($seatNameResult);
        $seatNames = explode(',', $seatNameRow['buyer_chosenseats']);
        
        foreach ($seatNames as $seatName) {
            $seatName = trim($seatName);
        
            $deleteOtherTableQuery = "DELETE FROM chosenseats WHERE seatnames = '$seatName'AND concertid='$conid'";
            mysqli_query($conn, $deleteOtherTableQuery);
        }
        
        $deleteQuery = "DELETE FROM tblbuyer WHERE transaction_no = '$deleteId' AND buyer_id=$user_id";
        mysqli_query($conn, $deleteQuery);
        
        header("Location: ticketshistory.php?search=$search");
        exit();
    }

// Check if the status parameter is set in the URL
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

// Check if the status parameter is set in the URL
if ($status === 'all') {
    // If status is 'all', fetch all records
    $sql = "SELECT * FROM tblbuyer WHERE buyer_id = $user_id";
    // Add the search condition if search parameter is present
    if (!empty($search)) {
        $sql .= " AND transaction_no LIKE '%$search%'";
    }
    $result = mysqli_query($conn, $sql);
} else {
    // If status is 'pending' or 'paid', filter records by status
    $sql = "SELECT * FROM tblbuyer WHERE status = '$status' AND buyer_id = $user_id";
    // Add the search condition if search parameter is present
    if (!empty($search)) {
        $sql .= " AND transaction_no LIKE '%$search%'";
    }
    $result = mysqli_query($conn, $sql);
}
mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets History</title>
    <link rel="icon" type="image/x-icon" href="css/images/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/ticketshistory.css">
</head>

<body style="margin: 40px;">

    <div class="container">
        <div class="jumbotron">
            <img src="css/images/ticket.png" class="img-fluid" style="max-width: 200px; height: auto;"
                alt="Small Responsive Image">
            <h1 class="display-4">Tickets History</h1>
            <p class="lead">View and manage ticket transactions.</p>
            <p class="lead"> <b>By Musiverse</b> </p>
            <form method="POST" action="home.php">
                <button type="submit" class="btn btn-danger form-check-inline mr-5 text-white">Back to Home</button>
            </form>

        </div>
        <form method="GET" action="ticketshistory.php" class="form-inline">
            <div class="form-group mr-3">
                <label for="searchInput" class="form-check-inline mr-3 text-dark">Search:</label>
                <input type="text" class="form-control" id="searchInput" name="search" placeholder="Transaction Number"
                    value="<?= $search ?>">
            </div>
            <button type="submit" class="btn btn-primary form-check-inline mr-3 text-white">Search</button>
            <div class="form-check form-check-inline mr-3">
                <input type="radio" class="form-check-input" id="statusAll" name="status" value="all"
                    <?php echo ($status === 'all') ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark" for="statusAll">All</label>
            </div>

            <div class="form-check form-check-inline mr-3">
                <input type="radio" class="form-check-input" id="statusPending" name="status" value="pending"
                    <?php echo ($status === 'pending') ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark" for="statusPending">Pending</label>
            </div>

            <div class="form-check form-check-inline mr-3">
                <input type="radio" class="form-check-input" id="statusPaid" name="status" value="paid"
                    <?php echo ($status === 'paid') ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark" for="statusPaid">Paid</label>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover rounded">
                <thead class="thead-light">
                    <tr>
                        <th>Transaction No.</th>
                        <th>Concert Name</th>
                        <th>Chosen Seats</th>
                        <th>Phone Number</th>
                        <th>Tickets Quantity</th>
                        <th>Payment Mode</th>
                        <th>Concert Date</th>
                        <th>Payment Prices</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
            while ($row = mysqli_fetch_array($result)) {
                // Set background color based on status
                $statusBackgroundColor = ($row['status'] === 'Pending') ? 'bg-warning' : 'bg-success';

                echo "<tr>
                        <td>" . $row['transaction_no'] . "</td>
                        <td>" . $row['concert_name'] . "</td>
                        <td>" . $row['buyer_chosenseats'] . "</td>
                        <td>" . $row['buyer_phonenum'] . "</td>
                        <td>" . $row['tickets_qty'] . "</td>
                        <td>" . $row['payment_mode'] . "</td>
                        <td>" . $row['concert_date'] . "</td>
                        <td>" . number_format($row['payment_price'],2) . "</td>
                        <td class='$statusBackgroundColor'>" . $row['status'] . " </td>";
                echo "<td>";
                // Check if the status is 'Pending' or not 'Paid' before displaying the button
                if ($row['status'] === 'Pending' || $row['status'] !== 'Paid') {
                    echo "<a class='btn btn-danger btn-sm text-white' onclick='cancelPayment(\"" . $row['transaction_no'] . "\", \"" . $row['concert_id'] . "\")'>Cancel Payment</a>";

                }
                echo "&nbsp;&nbsp;";
                echo "<a class='btn btn-primary btn-sm text-white' onclick='generateAndOpenQRCode(\"" . $row['transaction_no'] . "\")'>View QR Code</a>";

                echo "</td>
                      </tr>";
            }
            ?>
                </tbody>
            </table>


        </div>
    </div>

    <style>
    .bg-warning {
        background-color: #ffc107;
    }

    .bg-success {
        background-color: #28a745;
    }
    </style>

    <script>
    function cancelPayment(transactionNo, concertId) {
        var confirmCancel = confirm('Are you sure you want to cancel the payment for Transaction No. ' + transactionNo +
            '?');
        if (confirmCancel) {
            window.location.href = 'ticketshistory.php?delete=' + transactionNo + '&concert_id=' + concertId;
        }

    }

    function generateAndOpenQRCode(transactionNo) {
        const qrData = `Transaction No.: ${transactionNo}`;
        const qrCodeUrl = `https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=${encodeURIComponent(qrData)}`;
        window.open(qrCodeUrl, '_blank');
    }
    </script>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </script>
</body>

</html>