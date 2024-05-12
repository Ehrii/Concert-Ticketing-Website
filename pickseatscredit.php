<?php
 include 'config.php';
session_start();
$concertId = $_SESSION['concert_id'];


 $concert = mysqli_query($conn, "SELECT * FROM `tblconcert` WHERE concert_id = '$concertId'") or die('query failed');

if (mysqli_num_rows($concert) > 0) {
   $fetch = mysqli_fetch_assoc($concert);
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
    <title>Seats</title>
    <link rel="icon" type="image/x-icon" href="css/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/pickseats.css">

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3">
                    <div class="card-header">
                        <h2>Concert Name: <?php echo $fetch['concert_name']; ?><h2>
                                <h5>Concert Date: <?php echo $fetch['concert_date']; ?><h5>
                                        <image src="css/images/concertlayoutwithlabels.png" id="seatimage">
                    </div>
                    <div class="card-header">
                        <h5>Choose a Seat <i class="ri-h-3"></i></h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <form action="" method="GET">
                    <input type="hidden" name="concertid" value="<?php echo $concertId; ?>">
                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <h5>Filter
                                <button type="submit" class="btn btn-primary btn-sm float-end">Search</button>
                            </h5>
                        </div>
                        <div class="card-body">
                            <h6>Seat Zones</h6>
                            <hr>
                            <?php
                        include 'config.php';

                        $brand_query = "SELECT DISTINCT section FROM seats";
                        $brand_query_run  = mysqli_query($conn, $brand_query);

                        if(mysqli_num_rows($brand_query_run) > 0)
                        {
                            foreach($brand_query_run as $seats)
                            {
                                $checked = [];
                                if(isset($_GET['seats']))
                                {
                                    $checked = $_GET['seats'];
                                }
                                ?>
                            <div>
                                <input type="checkbox" name="seats[]" value="<?= $seats['section']; ?>"
                                    <?php if(in_array($seats['section'], $checked)){ echo "checked"; } ?> />
                                <?= $seats['section']; ?>
                            </div>
                            <?php
                            }
                        }
                        else
                        {
                            echo "No Brands Found";
                        }
                        ?>
                        </div>
                    </div>

                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <h5>Search</h5>
                        </div>
                        <div class="card-body">
                            <input type="text" name="search" class="form-control" placeholder="Search...">
                        </div>
                    </div>

                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <h5>Selected Seats</h5>
                        </div>
                        <div class="card-body" id="selected-seats-body">

                        </div>
                    </div>

                    <div class="card shadow mt-3">
                        <div class="card-header" id="total-price">
                        </div>
                        <div class="card-header">
                            <h5>Confirm Seats?</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <a href="" class="cta-button">Proceed</a>
                    </div>
            </div>
            </form>

            <div class="col-md-9 mt-3">
                <div class="card ">
                    <div class="card-body row">
                        <?php
                    if(isset($_GET['seats']))
                    {
                        $branchecked = [];
                        $branchecked = $_GET['seats'];
                        foreach($branchecked as $rowbrand)
                        {
                            $products = "SELECT s.*, cs.* FROM seats s  LEFT JOIN chosenseats cs ON s.seatid = cs.seatid AND cs.concertid ='$concertId' WHERE s.section = '$rowbrand'";
                            if (isset($_GET['search']) && !empty($_GET['search'])) {
                                $search = $_GET['search'];
                                $products .= "AND s.seatname LIKE '%$search%'";
                            }
                            $products_run = mysqli_query($conn, $products);
                            if(mysqli_num_rows($products_run) > 0)
                            {
                                $selectedSeatsCount = 0;
                                foreach($products_run as $proditems) :
                                    ?>
                        <div class="col-md-4 mt-3">
                            <div class="border p-2">
                                <?php
                                    $seatStatus = isset($proditems['status']) ? $proditems['status'] : 'Available';
                                    $statusColor = $seatStatus === 'Available' ? 'green' : ($seatStatus === 'Reserved' ? 'orange' : 'red');
                                $isDisabled = $statusColor !== 'green'; 
                                    ?>
                                <input type="checkbox" name="selected_seats[]"
                                    value="<?= $proditems['seatname'] .' - '. $proditems['section']; ?>"
                                    onclick="limitCheckboxSelection(this, 5)" <?= $isDisabled ? 'disabled' : ''; ?> />
                                <label><?= $proditems['seatname'] .' - '. $proditems['section']; ?>
                                    <span style="color: <?= $statusColor; ?>">
                                        (<?= $seatStatus; ?>)
                                    </span>
                                </label>
                            </div>
                        </div>
                        <?php
                                    $selectedSeatsCount++;
                                endforeach;
                                if ($selectedSeatsCount >= 5) {
                                    echo '<p class="checkbox-limit-message">You can only select up to 5 seats.</p>';
                                }
                            }
                        }
                    }
                    else
                    {
                        $products = "SELECT s.*, cs.* FROM seats s LEFT JOIN chosenseats cs ON s.seatid = cs.seatid AND cs.concertid = '$concertId'";
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                     $search = $_GET['search'];
                    $products .= "WHERE s.seatname LIKE '%$search%' AND cs.seatid IS NULL";
                        }

                        $products_run = mysqli_query($conn, $products);
                        if(mysqli_num_rows($products_run) > 0)
                        {
                            foreach($products_run as $proditems) :
                                ?>
                        <!-- Inside the PHP loop that generates checkboxes -->
                        <div class="col-md-4 mt-3">
                            <div class="border p-2">
                                <?php
                                    $seatStatus = isset($proditems['status']) ? $proditems['status'] : 'Available';
                                    $statusColor = $seatStatus === 'Available' ? 'green' : ($seatStatus === 'Reserved' ? 'orange' : 'red');
                                $isDisabled = $statusColor !== 'green'; 
                                    ?>
                                <input type="checkbox" name="selected_seats[]"
                                    value="<?= $proditems['seatname'] .' - '. $proditems['section']; ?>"
                                    onclick="limitCheckboxSelection(this, 5)" <?= $isDisabled ? 'disabled' : ''; ?> />
                                <label><?= $proditems['seatname'] .' - '. $proditems['section']; ?>
                                    <span style="color: <?= $statusColor; ?>">
                                        (<?= $seatStatus; ?>)
                                    </span>
                                </label>
                            </div>
                        </div>
                        <?php
                            endforeach;
                        }
                        else
                        {
                            echo "No Items Found";
                        }
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/pickseats.js"></script>

    <script>
    function limitCheckboxSelection(checkbox, limit) {
        var selectedCheckboxes = document.querySelectorAll('input[name="selected_seats[]"]:checked');
        if (selectedCheckboxes.length > limit) {
            checkbox.checked = false;
            alert("You can only select up to " + limit + " seats.");
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const checkboxes = document.querySelectorAll('input[name="selected_seats[]"]');
        const selectedSeatsLabel = document.getElementById('selected-seats-body');
        const totalPriceLabel = document.getElementById('total-price');

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateSelectedSeats();
                updateTotalPrice();
            });
        });

        function updateSelectedSeats() {
            const selectedSeats = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value)
                .join(', ');

            selectedSeatsLabel.textContent = selectedSeats;
        }


    });

    function xorEncryptDecrypt(input, key) {
        let output = '';
        for (let i = 0; i < input.length; i++) {
            output += String.fromCharCode(input.charCodeAt(i) ^ key.charCodeAt(i % key.length));
        }
        return output;
    }

    document.querySelector('.cta-button').addEventListener('click', function() {
        var selectedSeats = document.querySelectorAll('input[name="selected_seats[]"]:checked');
        var selectedSeatsArray = Array.from(selectedSeats).map(checkbox => checkbox.value);
        var encryptionKey = 'RevsjvQoul';
        var encryptedData = xorEncryptDecrypt(JSON.stringify(selectedSeatsArray), encryptionKey);
        var base64EncodedData = btoa(encryptedData);
        this.href = 'creditcard.php?concert_id=' + <?php echo json_encode($concertId); ?> +
            '&encrypted_seats=' +
            encodeURIComponent(base64EncodedData);
    });
    </script>
</body>

</html>