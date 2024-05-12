<?php
include "config.php";
$id = $_GET['id'];
$query = "DELETE FROM user_form WHERE id =$id";
$result = mysqli_query($conn, $query);
if($result) {
    header("Location: viewusers.php?msg=Record has been deleted.");
} else {
    echo "Deletion Failed...".mysqli_error($conn);
}
?>