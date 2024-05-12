<?php
include "config.php";

if(isset($_POST['query'])) {
    $search_query = $_POST['query'];

    $query = "SELECT * FROM tblbuyer WHERE buyer_id LIKE '%$search_query%' or buyer_name like '%$search_query%' 
            or buyer_chosenseats like '%$search_query%' or payment_mode like '%$search_query%' or buyer_phonenum like '%$search_query%'
            or concert_name like '%$search_query%' or concert_date like '%$search_query%' or tickets_qty like '%$search_query%'
            or payment_date like '%$search_query%' or transaction_no like '%$search_query%' or payment_price like '%$search_query%'
            or status like '%$search_query%'";
    $result = mysqli_query($conn, $query);

    $data = array();

    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

mysqli_close($conn);
?>