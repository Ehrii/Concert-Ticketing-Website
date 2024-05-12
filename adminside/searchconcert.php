<?php
include "config.php";

if(isset($_POST['query'])) {
    $search_query = $_POST['query'];

    $query = "SELECT * FROM tblconcert 
    WHERE concert_name LIKE '%$search_query%' OR concert_id LIKE '%$search_query%' 
    OR concert_date LIKE '%$search_query%' OR concert_time LIKE '%$search_query%' 
    OR concert_artist LIKE '%$search_query%' OR concert_desc LIKE '%$search_query%' 
    OR concert_genre LIKE '%$search_query%' OR concert_venue LIKE '%$search_query%' 
    OR ub_price LIKE '%$search_query%' OR lb_price LIKE '%$search_query%' 
    OR vip_price LIKE '%$search_query%' OR genad_price LIKE '%$search_query%' 
    OR concert_contact LIKE '%$search_query%'";
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