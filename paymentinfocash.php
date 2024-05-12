<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config.php';
$user_id = $_SESSION['user_id'];
$concert_id = $_GET['concert_id'];
$selectuser = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
$selectconcert = mysqli_query($conn, "SELECT * FROM `tblconcert` WHERE concert_id = '$concert_id'") or die('query failed');

$totalPrice = isset($_GET['total_price']) ? $_GET['total_price'] : 0;
$selectedSeatsCount = isset($_GET['count']) ? $_GET['count'] : 0;
$transactionNumber = isset($_GET['transaction_no']) ? $_GET['transaction_no'] : null;


if(mysqli_num_rows($selectuser) > 0) {
    $fetchuser = mysqli_fetch_assoc($selectuser);
} else {
    $fetchuser = null;
}

if(mysqli_num_rows($selectconcert) > 0) {
    $fetchcon = mysqli_fetch_assoc($selectconcert);
} else {
    $fetchcon = null;
}
if(isset($_GET['selected_seats'])) {
    $selectedSeats = urldecode($_GET['selected_seats']);

} else {
    $selectedSeats = "No seats selected";
}
?>

<!DOCTYPE html>
<html lang="en" title="Coding design">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Payment Information</title>
    <link rel="icon" type="image/x-icon" href="css/images/logo.png">
    <link rel="stylesheet" href="css/paymentinfo.css">
</head>

<body>

    <main class="table">

        <section class="table__header">
            <h1><i class="fas fa-ticket-alt"></i> Ticket Information</h1>
        </section>
        <form id="paymentForm" method="post" action="ticketshistory.php" class="hidden">
            <div class="table-container">

                <section class="table__body">

                    <table>
                        <thead>
                            <tr>
                                <th colspan="2">
                                    <img src="css/images/cash.png" id="seatimage" alt="Concert Map">
                                </th>
                            </tr>
                            <tr>
                                <th><i class="fas fa-music"></i> Concert Information:
                                    <?php echo $fetchcon['concert_name'] ?>
                                </th>

                                <th><i class="far fa-calendar-alt"></i> Concert Date:
                                    <?php echo date('F j, Y', strtotime($fetchcon['concert_date'])); ?><br>
                                    <?php echo date('h:i A', strtotime($fetchcon['concert_time'])); ?>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-user"></i> Ticket Buyer:
                                    <?php echo $fetchuser['fullname'] ?>
                                </td>
                                <td><i class="fas fa-phone"></i> Phone Number: (+63)
                                    <?php echo $fetchuser['phonenum'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-chair"></i> Chosen Seats:
                                    <?php echo $selectedSeats ?>
                                </td>
                                <td><i class="fas fa-sort-numeric-up-alt"></i> Quantity:
                                    <?php echo $selectedSeatsCount ?>
                                </td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-home"></i> User Address:
                                    <?php echo $fetchuser['address'] ?>
                                </td>
                                <td><i class="fas fa-map-marker-alt"></i> Venue:
                                    <?php echo $fetchcon['concert_venue'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td><i class="far fa-clock"></i>
                                    <?php
                                    date_default_timezone_set('Asia/Singapore');
                                    $current_time = date("h:i:s A");
                                    echo "Current Time (Singapore): ".$current_time ?>
                                </td>
                                <td><i class="fas fa-microphone"></i> Artist:
                                    <?php echo $fetchcon['concert_artist'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-money-bill"></i> Total Price: <span id="result">&#8369;
                                        <?php echo number_format($totalPrice, 2) ?>
                                </td>
                                <td><i class="far fa-calendar-alt"></i> Payment Date:
                                    <?php echo date("Y/m/d") ?>
                                </td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-credit-card"></i> Payment Mode: Cash
                                </td>
                                <td><i class="fas fa-credit-card"></i> Transaction Number:
                                    <?php echo $transactionNumber; ?>
                                </td>

                            </tr>

                        </tbody>


                    </table>
                    <div class="qr-code" id="qrCodeContainer">
                        <!-- Placeholder for the QR code image -->
                    </div>

                </section>

                <a href="home.php" class="button">Back To Main Menu</a>
                <input type="submit" name="cancel" class="button" value="Cancel Payment"
                    style="background-color:crimson;">
        </form>
    </main>

    <?php

    ?>
    <script>
        const qrContainer = document.getElementById("qrCodeContainer");
        const qrValue = "<?php echo $transactionNumber; ?>";
        const qrSize = 300; // Set your desired size in pixels
        // Generate QR Code using Google Charts API
        const qrCodeUrl = `https://chart.googleapis.com/chart?chs=${qrSize}x${qrSize}&cht=qr&chl=${qrValue}`;
        const qrCodeImage = document.createElement("img");
        qrCodeImage.src = qrCodeUrl;
        // Append the QR code image to the container
        qrContainer.appendChild(qrCodeImage);
    </script>


</body>

</html>