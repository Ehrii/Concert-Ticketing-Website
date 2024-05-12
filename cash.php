<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config.php';
$user_id = $_SESSION['user_id'];
$concertId = $_SESSION['concert_id'];
$select = mysqli_query($conn, "SELECT * FROM `tblpayment` WHERE userid = '$user_id'") or die('query failed');
$select2 = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
$chosenseats = mysqli_query($conn, "SELECT * FROM `chosenseats`") or die('query failed');
$selectedSeatsCount = 0;

$select3 = mysqli_query($conn, "SELECT * FROM `seats`") or die('query failed');
if(mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);
} else {
    $fetch = null;
}

if(mysqli_num_rows($select2) > 0) {
    $fetchuser = mysqli_fetch_assoc($select2);
} else {
    $fetchuser = null;
}

//RETRIEVING ALL SEATS
$select3 = "SELECT * FROM seats";
$allseats = mysqli_query($conn, $select3);


// GET THE CONCERT ID
$id = $_SESSION['concert_id'];
$select4 = mysqli_query($conn, "SELECT * FROM `tblconcert` WHERE concert_id = '$id'") or die('query failed');

if(mysqli_num_rows($select4) > 0) {
    $fetchcon = mysqli_fetch_assoc($select4);
} else {
    $fetchcon = null;
}

function xorEncryptDecrypt($input, $key) {
    $output = '';
    for($i = 0; $i < strlen($input); $i++) {
        $output .= chr(ord($input[$i]) ^ ord($key[$i % strlen($key)]));
    }
    return $output;
}

if(isset($_GET['encrypted_seats'])) {

    $encryptedSeats = urldecode($_GET['encrypted_seats']);
    $encryptionKey = 'RevsjvQoul';
    $decodedData = base64_decode($encryptedSeats);
    $decryptedSeats = xorEncryptDecrypt($decodedData, $encryptionKey);
    $selectedSeatsArray = json_decode($decryptedSeats, true);
    $selectedSeats = implode(', ', $selectedSeatsArray);


} else {
    $selectedSeats = "No seats selected";
}

function generateTransactionNumber() {
    $prefix = 'TXN'; // You can change this prefix
    $randomPart = rand(100000, 999999); // Generate a random 6-digit number

    // Concatenate the prefix and the random part
    $transactionNumber = $prefix.$randomPart;

    return $transactionNumber;
}

$seatNames = array_map('trim', explode(',', $selectedSeats));
$totalPrice = 0;
$selectedSeatIds = [];
$selectedSeatNames = [];
foreach($seatNames as $seatName) {
    $seatParts = array_map('trim', explode('-', $seatName));

    if(isset($seatParts[0], $seatParts[1])) {
        $seat = $seatParts[0];
        $section = $seatParts[1];

        $query = "SELECT price,seatid,seatname,section FROM seats  WHERE section = '$section' AND seatname='$seat'";
        $result = mysqli_query($conn, $query);

        if($result) {
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];
            $seatId = $row['seatid'];
            $chosenseatnames = $row['seatname'].' - '.$row['section'];
            $selectedSeatIds[] = $seatId;
            $selectedSeatNames[] = $chosenseatnames;
            $totalPrice += $price;
            $selectedSeatsCount++;

        } else {
            echo "Error executing query: ".mysqli_error($conn);
        }
    }
}

$VIP = $fetchcon['vip_price'];
$query = "UPDATE seats
SET price = $VIP
WHERE section = 'VIP';";
$changeprice = mysqli_query($conn, $query);

$UB = $fetchcon['ub_price'];
$query = "UPDATE seats
SET price = $UB
WHERE section = 'UB';";
$changeprice = mysqli_query($conn, $query);


$LB = $fetchcon['lb_price'];
$query = "UPDATE seats
SET price = $LB
WHERE section = 'LB';";
$changeprice = mysqli_query($conn, $query);


$GA = $fetchcon['genad_price'];
$query = "UPDATE seats
SET price = $GA
WHERE section = 'GEN AD';";
$changeprice = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash</title>
    <link rel="stylesheet" href="css/seats.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="css/images/logo.png">
</head>

<body>
    <div class="form-container">
        <form action="" method="post">
            <a href="home.php" class="cta-button"> <i class="fas fa-home"></i> Back to Main Menu</a>
            <h1><br>CONCERT MAP: </h1><br>
            <p>Concert ID:
                <?php echo $id; ?>
            </p>
            <p>Concert Name:
                <?php echo $fetchcon['concert_name']; ?>
            </p><br>
            <label for="text1" class="labeltext">Customer Name:
                <?php echo $fetchuser['fullname']; ?>
            </label>
            <image src="css/images/concertmap.png" id="seatimage">
                <div class="wrapper">
                    Payment Mode:
                    <div class="radio-inputs">
                        <label class="radio">
                            <input type="radio" name="radio" checked onclick="handlePaymentSelection('Cash') "
                                value="Cash">
                            <span class="name">Cash</span>
                        </label>
                        <label class="radio">
                            <input type="radio" name="radio" onclick="handlePaymentSelection('Credit Card')"
                                value="Credit Card">
                            <span class="name">Credit Card</span>
                        </label>
                    </div>

                    <div class="wrapper">
                        <div class="card">
                            <input class="inputrad" type="radio" name="card" value="vip"
                                onclick="changeImage('css/images/VIP.png' , '<?php echo number_format($fetchcon['vip_price'], 2); ?>'); "
                                id="radioinput">
                            <span class="check"></span>
                            <label class="labelrad">
                                <div class="title">VIP</div>
                                <?php echo number_format($fetchcon['vip_price'], 2); ?>
                            </label>
                        </div>

                        <div class="card">
                            <input class="inputrad" type="radio" name="card" value="lb"
                                onclick="changeImage('css/images/LB.png','<?php echo number_format($fetchcon['lb_price'], 2); ?>'); "
                                id="radioinput">
                            <span class="check"></span>
                            <label class="labelrad">
                                <div class="title">LOWER BOX</div>
                                <?php echo number_format($fetchcon['lb_price'], 2); ?>


                            </label>
                        </div>
                        <div class="card">
                            <input class="inputrad" type="radio" name="card" value="ub"
                                onclick="changeImage('css/images/UB.png','<?php echo number_format($fetchcon['ub_price'], 2); ?>'); "
                                id="radioinput">
                            <span class="check"></span>
                            <label class="labelrad">
                                <div class="title">UPPER BOX</div>
                                <?php echo number_format($fetchcon['ub_price'], 2); ?>
                            </label>
                        </div>
                        <div class="card">
                            <input class="inputrad" type="radio" name="card" value="ga"
                                onclick="changeImage('css/images/GA.png','<?php echo number_format($fetchcon['genad_price'], 2); ?>'); "
                                id="radioinput">
                            <span class="check"></span>
                            <label class="labelrad">
                                <div class="title">GEN AD</div>
                                <?php echo number_format($fetchcon['genad_price'], 2); ?>

                            </label>
                        </div>
                        <div class="card" onclick="resetRadio()"
                            style="background-color: crimson; color: white; font-weight: bolder; font-size: 20px; height: 7vh; opacity: 0.7;">
                            <div class="title">Reset</div>
                        </div>
                    </div>

                    <br>

                    <a href="pickseatscash.php" class="cta-button">Choose a Seat</a>
                    <?php $selectedPayment = "Credit Card" ?>
                    <label for="text1" class="labeltext" id="seatprice">Seat Zone Price:</label>
                    <label for="text1" class="labeltext" id="paymentmode">Payment Mode:
                        <?php echo $selectedPayment; ?>
                    </label>

                    <label for="text1" class="labeltext" id="labeltext">Chosen Seats:
                        <?php echo $selectedSeats; ?>
                    </label>

                    <p id="total">Total Amount: <span id="result">
                            <?php echo number_format($totalPrice, 2); ?>
                        </span></p>

                    <input type="submit" name="pinconfirm" id="pinconfirm" value="Confirm Payment" class="form-btn">
        </form>

        <?php
        if(isset($_POST['pinconfirm'])) {
            try {
                if(!is_null($selectedSeatIds) && !empty($selectedSeatIds)) {
                    $Cashmsg = " $selectedSeatsCount seats were successfuly reserved.";
                    $Cash = true;
                    $count = count($selectedSeatIds);

                    for($i = 0; $i < $count; $i++) {
                        $seatId = $selectedSeatIds[$i];
                        $seatNames = $selectedSeatNames[$i];
                        $query = "INSERT INTO `chosenseats` (concertid, seatid, seatnames, status) VALUES ('$concertId', '$seatId', '$seatNames  ', 'Reserved')";
                        $insert = mysqli_query($conn, $query);

                        if($insert) {
                            $transactionNumber = generateTransactionNumber();
                            echo "<br>Selected seat with ID $seatId successfully reserved.<br>";
                        } else {
                            echo "Error executing query for seat ID $seatId: ".mysqli_error($conn)."<br>";
                        }
                    }


                    $insertuser = "INSERT INTO `tblbuyer` (buyer_id, buyer_name, buyer_chosenseats, payment_mode, buyer_phonenum, concert_name, concert_id, concert_date, tickets_qty, payment_date, transaction_no,payment_price, status)
                                  VALUES ('$user_id', '{$fetchuser['fullname']}', '$selectedSeats', 'Cash', '{$fetchuser['phonenum']}', '{$fetchcon['concert_name']}', '{$fetchcon['concert_id']}','{$fetchcon['concert_date']}', '$selectedSeatsCount', NOW(), '$transactionNumber',$totalPrice, 'Pending')";

                    $insertBuyer = mysqli_query($conn, $insertuser);

                } else {
                    $Cashmsg = "No seats selected. Please choose seats before confirming payment.";
                    $Cash = false;

                }
            } catch (Exception $e) {
                echo '(Seat Now Reserved) Please return to main menu since ', $e->getMessage(), "\n";
            }

        }
        ?>
        <?php if(!empty($Cashmsg)): ?>
            <p class="pin-message">
                <?php echo $Cashmsg; ?>
            </p>
        <?php endif; ?>
        <div>
            <script src="js/seats.js"></script>

            <input type="button" name="paymentcash" class="form-btn" id="paymentconfirmcash"
                value="Proceed To Payment Information (Cash)"
                onclick="redirectToAnotherPageCash('<?php echo $id ?>','<?php echo $selectedSeats; ?>', '<?php echo $totalPrice; ?>' ,'<?php echo $selectedSeatsCount; ?>','<?php echo $transactionNumber; ?>')">


</body>


<script>
    var Cash = <?php echo json_encode($Cash); ?>;
    function redirectToAnotherPageCash(concertId, seats, quantity, seatscount, transactionNumber) {

        if (!isValidSeats(seats)) {
            alert('Please choose seats before confirming payment.');
            return;
        }

        // Sanitize inputs
        concertId = encodeURIComponent(concertId);
        seats = encodeURIComponent(seats);
        quantity = encodeURIComponent(quantity);
        seatscount = encodeURIComponent(seatscount);
        transactionNumber = encodeURIComponent(transactionNumber);

        if (Cash) {
            var url = 'paymentinfocash.php?concert_id=' + concertId + '&selected_seats=' + seats +
                '&total_price=' + quantity + '&count=' + seatscount + '&transaction_no=' + transactionNumber
            window.location.href = url;
        } else {
            alert('Please enter a correct PIN before confirming payment.');
        }
    }

    function disableButton() {
        var paymentButton = document.getElementById('pinconfirm');
        if (Cash) {
            paymentButton.disabled = true;
        } else {
            paymentButton.disabled = false;
        }
    }

    window.onload = disableButton;

    function isValidSeats(seats) {
        return seats.trim() !== '' && seats.trim() !== 'No seats selected';
    }
</script>

</html>