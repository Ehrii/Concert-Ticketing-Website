<?php
session_start();
include 'config.php';
$user_id = $_SESSION['user_id'];
$select = mysqli_query($conn, "SELECT * FROM `tblpayment` WHERE userid = '$user_id'") or die('query failed');
if (mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);
} else {
    $fetch = null;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Credit Card</title>
    <link rel="icon" type="image/x-icon" href="css/images/logo.png">
    <link rel="stylesheet" href="css/payment.css">

</head>

<body>

    <div class="container">
        <br>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

        <div class=" card-container">

            <div class="front">
                <div class="image">
                    <img src="css/paymentimg/chip.png" alt="">
                    <img src="css/paymentimg/unknown.png" alt="" id="cardimage">
                </div>

                <div class="card-number-box">
                    <?php

                    if (isset($fetch['cardnum']) && !empty($fetch['cardnum'])) {
                         $cardNumber = $fetch['cardnum'];
                         $splitCardNumber = implode('-', str_split($cardNumber, 4));
                          echo $splitCardNumber;
                                 } else {
                          echo '4444-5555-2222-4444';
                              }

                ?>
                </div>
                <div class="flexbox">
                    <div class="box">
                        <span>Card Holder</span>
                        <div class="card-holder-name">
                            <?php
                        if (isset($fetch['cardholder']) && !empty($fetch['cardholder'])) {
                            echo $fetch['cardholder'];
                        } else {
                            echo 'FULL NAME';
                        }
                        ?>
                        </div>
                    </div>
                    <div class="box">
                        <span>Expires</span>
                        <div class="expiration">
                            <span class="exp-month">
                                <?php
                            if (isset($fetch['monthexp']) && !empty($fetch['monthexp'])) {
                                echo $fetch['monthexp'];
                            } else {
                                echo 'MM';
                            }
                            ?>
                            </span>
                            <span class="exp-year">
                                <?php
                            if (isset($fetch['yearexp']) && !empty($fetch['yearexp'])) {
                                echo $fetch['yearexp'];
                            } else {
                                echo 'YY';
                            }
                            ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="back">
                <div class="stripe"></div>
                <div class="box">
                    <span>
                        <?php
                    if (isset($fetch['cvv']) && !empty($fetch['cvv'])) {
                        echo $fetch['cvv'];
                    } else {
                        echo '1234';
                    }
                    ?>
                    </span>
                    <div class="cvv-box"></div>
                    <img src="css/paymentimg/unknown.png" id="cvvimg" alt="">
                </div>
            </div>

        </div>

        <form action="" method="post" enctype="multipart/form-data">

            <?php
        if (isset($msg)) {
            foreach ($msg as $msg) {
                echo '<span class="error-msg">' . $msg . '</span>';
            }
            ;
        }
        ;

        ?>
            <span class="note">Credit Card Status:
                <?php echo isset($fetch['status']) ? $fetch['status'] : 'Unlinked'; ?>
            </span>
            <div class="radio-inputs">
                <label class="radio">
                    <input type="radio" required name="radio" onclick="detectCardType('css/paymentimg/mastercard.png')"
                        <?php echo (isset($fetch['cardtype']) && $fetch['cardtype'] === 'Mastercard') ? 'checked' : ''; ?>
                        value="Mastercard">
                    <span class="name">MasterCard</span>
                </label>
                <label class="radio">
                    <input type="radio" required name="radio" onclick="detectCardType('css/paymentimg/visa.png')"
                        <?php echo (isset($fetch['cardtype']) && $fetch['cardtype'] === 'Visa') ? 'checked' : ''; ?>
                        value="Visa">
                    <span class="name">Visa</span>
                </label>
                <label class="radio">
                    <input type="radio" required name="radio" onclick="detectCardType('css/paymentimg/americanex.png')"
                        <?php echo (isset($fetch['cardtype']) && $fetch['cardtype'] === 'American Express') ? 'checked' : ''; ?>
                        value="American Express">
                    <span class="name">American Express</span>
                </label>
            </div>

            <div class="inputBox">
                <span>Card Number</span>
                <input type="text" required maxlength="19" class="card-number-input" id="cardnumber" name="cardnumber"
                    placeholder="1234-5678-9012-3456"
                    value="<?php echo isset($fetch['cardnum']) ? $fetch['cardnum'] : ''; ?> "
                    oninput="formatCardNumberInput(this)">


            </div>
            <div class="inputBox">
                <span>Card Holder</span>
                <input type="text" required class="card-holder-input" placeholder="Ex. Juan Dela Cruz" name="cardname"
                    value="<?php echo isset($fetch['cardholder']) ? $fetch['cardholder'] : ''; ?>">
            </div>
            <div class="flexbox">
                <div class="inputBox">
                    <span>Expiration MM</span>
                    <select name="expmonth" required id="" class="month-input">
                        <option value="month" selected disabled>month</option>
                        <?php
                    $selectedMonth = $fetch['monthexp']; // Assuming $fetch['monthexp'] contains the selected month value
                    
                    for ($i = 1; $i <= 12; $i++) {
                        $formattedMonth = sprintf("%02d", $i); // Pad with leading zero if needed
                        $selected = ($selectedMonth == $formattedMonth) ? 'selected' : '';
                        echo "<option value=\"$formattedMonth\" $selected>$formattedMonth</option>";
                    }
                    ?>
                    </select>
                </div>
                <div class="inputBox">
                    <span>Expiration YY</span>
                    <select name="expyear" required id="" class="year-input" value="<?php echo $fetch['yearexp']; ?>">
                        <?php
                    $selectedYear = $fetch['yearexp']; // Assuming $fetch['yearexp'] contains the selected year value
                    
                    $currentYear = date("Y");
                    for ($i = $currentYear; $i <= $currentYear + 10; $i++) {
                        $selected = ($selectedYear == $i) ? 'selected' : '';
                        echo "<option value=\"$i\" $selected>$i</option>";
                    }
                    ?>
                    </select>
                </div>
                <div class="inputBox">
                    <span>CVV</span>
                    <input type="number" maxlength="4" class="cvv-input" placeholder="1234" name="cvv"
                        value="<?php echo isset($fetch['cvv']) ? $fetch['cvv'] : ''; ?>">
                </div>
            </div>

            <input type="submit" name="link" value="Link Card" class="submit-btn"
                <?php echo ($fetch !== null && !empty($fetch)) ? 'hidden' : ''; ?>>
            <br><br>
            <a href="creditcard.php" id="cardlink" class="back-btn"><i class="fas fa-arrow-left"></i> GO BACK</a>


        </form>
    </div>


    <?php
    if (isset($_POST['link'])) {
    $cardNumber = $_POST['cardnumber'];
    $cardHolder = mysqli_real_escape_string($conn, $_POST['cardname']);
    $monthExp = $_POST['expmonth'];
    $yearExp = $_POST['expyear'];
     $cvv = $_POST['cvv'];
    $cardType = mysqli_real_escape_string($conn, $_POST['radio']);
    $user_id = $_SESSION['user_id'];
    $pin = random_int(10000000, 99999999);
    $pinAsString = strval($pin);
    $cardNumber = $_POST["cardnumber"];
    $cardNumberWithoutHyphen = str_replace("-", "", $cardNumber);
    $selectedMonth = intval($_POST['expmonth']);
    $selectedYear = intval($_POST['expyear']);
    $currentYear = date("Y");
    $currentMonth = date("m");

    if ($selectedYear < $currentYear || ($selectedYear == $currentYear && $selectedMonth < $currentMonth)) {
    $errors[] = "The selected card has already expired.";
    }

    if (empty($cardNumberWithoutHyphen) || !preg_match("/^\d{16}$/", $cardNumberWithoutHyphen)) {
        $errors[] = "Please enter a valid 16-digit card number without hyphens";
    }
        
    $cardHolder = $_POST["cardname"];
    if (empty($cardHolder) || !preg_match("/^[a-zA-Z\s]+$/", $cardHolder)) {
        $errors[] = "Please enter a valid card holder name";
    }

    // Validate CVV
    $cvv = $_POST["cvv"];
    if (empty($cvv) || !preg_match("/^\d{3,4}$/", $cvv)) {
        $errors[] = "Please enter a valid CVV (3 or 4 digits)";
    }
   

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p style='background-color: red; color:white;'>$error</p>";
            }
        } else {
            
            $update = mysqli_query($conn, "UPDATE `tblpayment` SET userid = '$user_id', pin='$pinAsString' WHERE cardnum = '$cardNumber' AND cardholder = '$cardHolder' AND monthexp = '$monthExp' AND yearexp = '$yearExp' AND cvv = '$cvv' AND cardtype = '$cardType'") or die('Update query failed');

            if ($update !== false) {
                if (mysqli_affected_rows($conn) > 0) {
                    echo "<p style='background-color: green; color:white;'>Card successfully linked!</p>";
                } else {
                    echo "<p style='background-color: red; color:white;'>Failed to link card. Please try again.</p>";
                }
            } else {
                echo "<p style='background-color: red; color:white;'>Failed to execute update query. Please try again.</p>";
            }
        }
        
     
    }

    ?>

    <script>
    function formatCardNumberInput(input) {
        var cardNumber = input.value.replace(/\D/g, '');
        var formattedCardNumber = cardNumber.replace(/(\d{4})(?=\d)/g, '$1-');
        input.value = formattedCardNumber;
    }

    window.onload = function() {
        var cardNumberInput = document.getElementById('cardnumber');
        formatCardNumberInput(cardNumberInput);
    };

    function detectCardType(imageSrc) {
        var imgElement = document.getElementById('cardimage');
        var cvvimage = document.getElementById('cvvimg');
        imgElement.src = imageSrc;
        cvvimage.src = imageSrc;

    }



    document.querySelector('.card-number-input').oninput = () => {
        document.querySelector('.card-number-box').innerText = document.querySelector('.card-number-input').value;
    }

    document.querySelector('.card-holder-input').oninput = () => {
        document.querySelector('.card-holder-name').innerText = document.querySelector('.card-holder-input').value;
    }

    document.querySelector('.month-input').oninput = () => {
        document.querySelector('.exp-month').innerText = document.querySelector('.month-input').value;
    }

    document.querySelector('.year-input').oninput = () => {
        document.querySelector('.exp-year').innerText = document.querySelector('.year-input').value;
    }

    document.querySelector('.cvv-input').onmouseenter = () => {
        document.querySelector('.front').style.transform = 'perspective(1000px) rotateY(-180deg)';
        document.querySelector('.back').style.transform = 'perspective(1000px) rotateY(0deg)';
    }

    document.querySelector('.cvv-input').onmouseleave = () => {
        document.querySelector('.front').style.transform = 'perspective(1000px) rotateY(0deg)';
        document.querySelector('.back').style.transform = 'perspective(1000px) rotateY(180deg)';
    }

    document.querySelector('.cvv-input').oninput = () => {
        document.querySelector('.cvv-box').innerText = document.querySelector('.cvv-input').value;
    }
    </script>
</body>

</html>