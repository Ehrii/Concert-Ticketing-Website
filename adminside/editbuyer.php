<?php
include "config.php";
$transaction_no = isset($_GET['transaction_no']) ? mysqli_real_escape_string($conn, $_GET['transaction_no']) : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if(isset($_POST['submit'])) {
    $errors = array();
    $buyer_bname = $_POST['buyer_bname'];
    $buyer_cseats = $_POST['buyer_cseats'];
    $buyer_paymentmode = $_POST['buyer_paymentmode'];
    $buyer_pnum = $_POST['buyer_pnum'];
    $buyer_cname = $_POST['buyer_cname'];
    $buyer_cdate = $_POST['buyer_cdate'];
    $buyer_ticketsqty = $_POST['buyer_ticketsqty'];
    $buyer_pdate = $_POST['buyer_update'];
    $buyer_transno = $_POST['buyer_transno'];
    $buyer_pprice = $_POST['buyer_pprice'];
    $buyer_status = $_POST['buyer_status'];
}
function validateInput($input) {
    return htmlspecialchars(trim($input));
}

$errors = [];

// Check if the form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Buyer Name
    $buyerName = validateInput($_POST["buyer_bname"]);
    if(empty($buyerName)) {
        $errors[] = "Buyer Name is required";
    }

    // Validate Chosen Seats
    $chosenSeats = validateInput($_POST["buyer_cseats"]);
    if(empty($chosenSeats)) {
        $errors[] = "Chosen Seats is required";
    }

    // Validate Payment Mode
    $paymentMode = validateInput($_POST["buyer_paymentmode"]);
    if(empty($paymentMode)) {
        $errors[] = "Payment Mode is required";
    }

    // Validate Phone Number
    $phoneNumber = validateInput($_POST["buyer_pnum"]);
    if(empty($phoneNumber)) {
        $errors[] = "Phone Number is required";
    }

    // Validate Concert Name
    $concertName = validateInput($_POST["buyer_cname"]);
    if(empty($concertName)) {
        $errors[] = "Concert Name is required";
    }

    // Validate Concert Date
    $concertDate = validateInput($_POST["buyer_cdate"]);
    if(empty($concertDate)) {
        $errors[] = "Concert Date is required";
    }

    // Validate Quantity of tickets
    $ticketsQty = validateInput($_POST["buyer_ticketsqty"]);
    if(empty($ticketsQty) || !is_numeric($ticketsQty) || $ticketsQty <= 0) {
        $errors[] = "Quantity of tickets must be a positive number";
    }

    // Validate Payment Date
    $paymentDate = validateInput($_POST["buyer_update"]);
    if(empty($paymentDate)) {
        $errors[] = "Payment Date is required";
    }

    // Validate Transaction Number
    $transNo = validateInput($_POST["buyer_transno"]);
    if(empty($transNo)) {
        $errors[] = "Transaction Number is required";
    }

    // Validate Payment Price
    $paymentPrice = validateInput($_POST["buyer_pprice"]);
    if(empty($paymentPrice) || !is_numeric($paymentPrice) || $paymentPrice <= 0) {
        $errors[] = "Payment Price must be a positive number";
    }


    if(empty($buyer_pnum)) {
        $errors[] = "Phone number is required.";
    } elseif(!preg_match('/^[0-9]{11}$/', $buyer_pnum)) {
        $errors[] = "Invalid phone number. Please enter a valid 11-digit numeric phone number.";
    }

    // Validate Status
    $status = validateInput($_POST["buyer_status"]);
    if(empty($status)) {
        $errors[] = "Status is required";
    }

    // If there are no validation errors, you can proceed with further actions
    if(empty($errors)) {
        // Update tblbuyer

        $updateBuyerQuery = "UPDATE `tblbuyer` SET 
        `buyer_name`='$buyer_bname', `buyer_chosenseats`='$buyer_cseats', 
        `payment_mode`='$buyer_paymentmode', `buyer_phonenum`='$buyer_pnum', 
        `concert_name`='$buyer_cname', `concert_date`='$buyer_cdate', 
        `tickets_qty`='$buyer_ticketsqty', `payment_date`='$buyer_pdate', 
        `payment_price`='$buyer_pprice', `status`='$buyer_status' 
        WHERE `transaction_no`='$transaction_no' AND `buyer_id`='$id'";

        $resultBuyer = mysqli_query($conn, $updateBuyerQuery);
        // Update chosenseats
        if($resultBuyer && ($buyer_status == 'Paid' || $buyer_status == 'Pending')) {
            $seatNamesArray = explode(', ', $buyer_cseats);
            $conid = isset($_GET['concert_id']) ? intval($_GET['concert_id']) : 0;

            foreach($seatNamesArray as $seatName) {

                $trimmedSeatName = trim($seatName);

                $updateSeatsQuery = ($buyer_status == 'Paid') ?
                    "UPDATE `chosenseats` SET `status`='Taken' WHERE seatnames LIKE '%$trimmedSeatName%' AND concertid='$conid'" :
                    "UPDATE `chosenseats` SET `status`='Reserved' WHERE seatnames LIKE '%$trimmedSeatName%' AND concertid='$conid'";

                $resultSeats = mysqli_query($conn, $updateSeatsQuery);

                if(!$resultSeats) {
                    echo "Error updating chosenseats: ".mysqli_error($conn);
                }
            }
            // Redirect after the loop completes
            header("Location: viewbuyer.php?msg=Data has been updated");
        }

    }

}


if(!empty($errors)) {
    echo "<div style='background-color: red; color:white; text-align:center;'>";
    foreach($errors as $error) {
        echo $error."<br>";
    }
    echo "</div>";
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Edit Buyer</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }
    </style>


</head>

<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ADB5;">
        Edit Buyer
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3> Edit Buyer Ticket Details </h4>
                <p class="text-muted">If you wish to proceed with the new details, click update</p>
        </div>

        <?php

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $concert_id = isset($_GET['concert_id']) ? intval($_GET['concert_id']) : 0;
        $transaction_no = isset($_GET['transaction_no']) ? mysqli_real_escape_string($conn, $_GET['transaction_no']) : '';

        if($id > 0 && $concert_id > 0 && !empty($transaction_no)) {

            $query = "SELECT * FROM tblbuyer WHERE buyer_id = $id AND concert_id = $concert_id AND transaction_no = '$transaction_no' LIMIT 1";
            $result = mysqli_query($conn, $query);

            if($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);

            } else {
                echo "Selected row not found!";
            }
        } else {
            echo "Invalid ID, concert ID, or transaction number!";
        }
        ?>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:500vw; min-width:300px;">
                <div class="row">
                    <div class="form-group col">
                        <label class="form-label">Buyer Name: </label>
                        <input type="text" maxlength="50" class="form-control" name="buyer_bname"
                            value="<?php echo $row['buyer_name'] ?>" required>
                    </div>

                    <div class="form-group col mb-3">
                        <label class="form-label">Chosen Seats: </label>
                        <input type="text" maxlength="250" class="form-control" name="buyer_cseats"
                            value="<?php echo $row['buyer_chosenseats'] ?>" required>
                    </div>

                    <div class="form-group  mb-3 col">
                        <label class="form-label">Payment Mode: </label>
                        <input type="text" maxlength="50" class="form-control" name="buyer_paymentmode"
                            value="<?php echo $row['payment_mode'] ?>" required>
                    </div>


                    <div class="form-group mb-3 ">
                        <label clas="form-label">Phone Number: </label>
                        <input type="number" class="form-control" maxlength="11" name="buyer_pnum"
                            value="<?php echo $row['buyer_phonenum'] ?>" required>
                    </div>

                    <div class="form-group mb-3 col">
                        <label class="form-label">Concert Name: </label>
                        <input type="text" maxlength="50" class="form-control" name="buyer_cname"
                            value="<?php echo $row['concert_name'] ?>" required>
                    </div>


                    <div class="row">
                        <div class=" mb-3 col">
                            <label class="form-label">Concert Date: </label>
                            <input type="date" class="form-control" name="buyer_cdate"
                                value="<?php echo $row['concert_date'] ?>" required>
                        </div>

                        <div class=" mb-3 col">
                            <label class="form-label">Quantity of tickets: </label>
                            <input type="number" class="form-control" name="buyer_ticketsqty"
                                value="<?php echo $row['tickets_qty'] ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group mb-3 col">
                            <label class="form-label">Payment Date: </label>
                            <input type="date" class="form-control" name="buyer_update"
                                value="<?php echo $row['payment_date'] ?>" required>
                        </div>

                        <div class="form-group mb-3 col">
                            <label class="form-label">Transaction Number: </label>
                            <input type="text" maxlength="50" class="form-control" name="buyer_transno" maxlength="10"
                                value="<?php echo $row['transaction_no'] ?>" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group mb-3 col">
                            <label class="form-label">Payment Price: </label>
                            <input type="text" class="form-control" name="buyer_pprice"
                                value="<?php echo $row['payment_price'] ?>" required>
                        </div>

                        <div class="form-group mb-3 col">
                            <label class="form-label">Status: </label>
                            <select class="form-control" name="buyer_status">
                                <option value="Paid" <?php echo ($row['status'] == 'Paid') ? 'selected' : ''; ?>>Paid
                                </option>
                                <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>
                                    Pending</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success col" name="submit">Update</button>
                    <a href="viewbuyer.php" class="btn btn-danger col">Cancel</a>
            </form>
        </div>
    </div>
    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>
</body>

</html>