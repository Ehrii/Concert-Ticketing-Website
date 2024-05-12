<?php
include "config.php";
$id = $_GET['concert_id'];
$query = "DELETE FROM tblconcert WHERE concert_id =$id";
$result = mysqli_query($conn, $query);
if($result) {
    header("Location: viewconcert.php?msg=Record has been deleted.");
} else {
    echo "Deletion Failed...".mysqli_error($conn);
}
?>