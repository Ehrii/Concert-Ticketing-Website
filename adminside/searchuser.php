<?php
include "config.php";

if(isset($_POST['query'])) {
    $search_query = $_POST['query'];

    // Use a SELECT query to search for concerts
    $query = "SELECT * FROM user_form WHERE id LIKE '%$search_query%' or username like '%$search_query%' 
            or email like '%$search_query%' or password like '%$search_query%' or fullname like '%$search_query%'
            or dob like '%$search_query%' or phonenum like '%$search_query%' or address like '%$search_query%'
            or accdate like '%$search_query%'";
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