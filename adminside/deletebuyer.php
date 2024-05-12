<?php 
include "config.php";
$id = $_GET['id'];
$transactnum = $_GET['transaction_no'];

// Fetch the buyer data
$selectBuyerQuery = "SELECT * FROM tblbuyer WHERE buyer_id = $id AND transaction_no = '$transactnum'";
$resultBuyer = mysqli_query($conn, $selectBuyerQuery);

if (!$resultBuyer) {
    echo "Failed to retrieve buyer data: " . mysqli_error($conn);
} else {
    $row = mysqli_fetch_assoc($resultBuyer);
    
    $conid = $row['concert_id'];
    $seatNames = $row['buyer_chosenseats'];

    $deleteBuyerQuery = "DELETE FROM tblbuyer WHERE buyer_id = $id AND transaction_no = '$transactnum'";
    $resultBuyerDelete = mysqli_query($conn, $deleteBuyerQuery);

    if ($resultBuyerDelete) {
        $seatNamesArray = explode(', ', $seatNames);

        foreach ($seatNamesArray as $seatName) {
            $trimmedSeatName = trim($seatName);

            $deleteSeats = "DELETE FROM `chosenseats` WHERE seatnames LIKE '%$trimmedSeatName%' AND concertid='$conid'";
            $resultSeats = mysqli_query($conn, $deleteSeats);

            if (!$resultSeats) {
                echo "Failed to delete tblseats: " . mysqli_error($conn);
            }
        }

        header("Location: viewbuyer.php?msg=Records have been deleted.");
    } else {
        echo "Deletion Failed: " . mysqli_error($conn);
    }
}
?>
