<?php
include "config.php";
$id = $_GET['concert_id'];

if(isset($_POST['submit'])) {
    $concert_name = $_POST['concert_name'];
    $concert_date = $_POST['concert_date'];
    $concert_time = $_POST['concert_time'];
    $concert_artist = $_POST['concert_artist'];
    $concert_desc = $_POST['concert_desc'];
    $concert_genre = $_POST['concert_genre'];
    $concert_venue = $_POST['concert_venue'];
    $concert_ubprice = $_POST['concert_ubprice'];
    $concert_lbprice = $_POST['concert_lbprice'];
    $concert_vipprice = $_POST['concert_vipprice'];
    $concert_genadprice = $_POST['concert_genadprice'];
    $concert_contact = $_POST['concert_contact'];
    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'uploaded_img/'.$update_image;

}
$errors = [];


if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty($concert_name) || empty($concert_date) || empty($concert_time) || empty($concert_artist) || empty($concert_desc) || empty($concert_genre) || empty($concert_venue) || empty($concert_ubprice) || empty($concert_lbprice) || empty($concert_vipprice) || empty($concert_genadprice) || empty($concert_contact)) {
        $errors[] = "All fields are required. Except for images";
    }

    if(!empty($update_image)) {
        if($update_image_size > 2000000) {
            $errors[] = "Image size is too large. Please select an image smaller than 2MB.";
        } else {
            move_uploaded_file($update_image_tmp_name, $update_image_folder);

            $image_update_query = mysqli_query($conn, "UPDATE `tblconcert` SET image = '$update_image' WHERE concert_id = '$id'");

            if($image_update_query) {
                echo "Image has been updated successfully.";
            } else {
                $errors[] = "Failed to update image: ".mysqli_error($conn);
            }
        }
    }


    $numericFields = ['concert_ubprice', 'concert_lbprice', 'concert_vipprice', 'concert_genadprice'];

    foreach($numericFields as $field) {
        ${$field} = $_POST[$field];

        if(!is_numeric(${$field})) {
            $errors[] = ucfirst(str_replace('_', ' ', $field))." must be numeric.";
        }
    }
    $letterFields = ['concert_genre', 'concert_venue'];

    foreach($letterFields as $field) {
        ${$field} = $_POST[$field];

        if(!ctype_alpha(str_replace(' ', '', ${$field}))) {
            $errors[] = ucfirst(str_replace('_', ' ', $field))." must contain only letters.";
        }
    }


    if(empty($errors)) {
        $query = "UPDATE `tblconcert` SET 
        `concert_name`='".mysqli_real_escape_string($conn, $concert_name)."', 
        `concert_date`='".mysqli_real_escape_string($conn, $concert_date)."', 
        `concert_time`='".mysqli_real_escape_string($conn, $concert_time)."', 
        `concert_artist`='".mysqli_real_escape_string($conn, $concert_artist)."', 
        `concert_desc`='".mysqli_real_escape_string($conn, $concert_desc)."', 
        `concert_genre`='".mysqli_real_escape_string($conn, $concert_genre)."', 
        `concert_venue`='".mysqli_real_escape_string($conn, $concert_venue)."', 
        `ub_price`='".mysqli_real_escape_string($conn, $concert_ubprice)."', 
        `lb_price`='".mysqli_real_escape_string($conn, $concert_lbprice)."', 
        `vip_price`='".mysqli_real_escape_string($conn, $concert_vipprice)."', 
        `genad_price`='".mysqli_real_escape_string($conn, $concert_genadprice)."', 
        `concert_contact`='".mysqli_real_escape_string($conn, $concert_contact)."' 
        WHERE concert_id = $id";

        $result = mysqli_query($conn, $query);

        if($result) {
            header("Location: viewconcert.php?msg=Data has been updated");
        } else {
            echo "Failed: ".mysqli_error($conn);
        }
    }

    if(!empty($errors)) {
        echo "<div style='background-color: red; color:white; text-align:center;'>";
        foreach($errors as $error) {
            echo $error."<br>";
        }
        echo "</div>";
    }
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

    <title>Edit Concert</title>

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
        Edit Concert
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3> Edit Concert Details </h4>
                <p class="text-muted">If you wish to proceed with the new details, click update</p>
        </div>

        <?php
        $id = $_GET['concert_id'];
        $query = "SELECT * FROM tblconcert WHERE concert_id = $id LIMIT 1";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        ?>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:500vw; min-width:300px;" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-group col">
                        <label class="form-label">Concert Name: </label>
                        <input type="text" class="form-control" maxlength="100" name="concert_name"
                            value="<?php echo $row['concert_name'] ?>">
                    </div>

                    <div class="form-group col mb-3">
                        <label class="form-label">Date: </label>
                        <input type="date" class="form-control" name="concert_date"
                            value="<?php echo $row['concert_date'] ?>">
                    </div>

                    <div class="form-group  mb-3 col">
                        <label class="form-label">Time: </label>
                        <input type="time" class="form-control" name="concert_time"
                            value="<?php echo $row['concert_time'] ?>">
                        <?php
                        $currentTime = $row['concert_time'];
                        if(!empty($currentTime)) {
                            echo '<small class="text-muted">Previous Concert Time: '.$currentTime.'</small>';
                        } else {
                            echo '<small class="text-muted">No time selected</small>';
                        }
                        ?>
                    </div>
                    <div class="form-group mb-3 ">
                        <label clas="form-label">Artist: </label>
                        <input type="text" class="form-control" maxlength="50" name="concert_artist"
                            value="<?php echo $row['concert_artist'] ?>">
                    </div>

                    <div class="form-group mb-3 col">
                        <label class="form-label">Description: </label>
                        <textarea class="form-control" maxlength="2000" name="concert_desc"
                            rows="4"><?php echo $row['concert_desc'] ?></textarea>
                    </div>


                    <div class="row">
                        <div class=" mb-3 col">
                            <label class="form-label">Genre: </label>
                            <input type="text" class="form-control" maxlength="20" name="concert_genre"
                                value="<?php echo $row['concert_genre'] ?> ">
                        </div>

                        <div class=" mb-3 col">
                            <label class="form-label">Venue: </label>
                            <input type="text" class="form-control" maxlength="100" name="concert_venue"
                                value="<?php echo $row['concert_venue'] ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group mb-3 col">
                            <label class="form-label">UB Price: </label>
                            <input type="text" class="form-control" name="concert_ubprice"
                                value="<?php echo $row['ub_price'] ?>">
                        </div>

                        <div class="form-group mb-3 col">
                            <label class="form-label">LB Price: </label>
                            <input type="text" class="form-control" name="concert_lbprice"
                                value="<?php echo $row['lb_price'] ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group mb-3 col">
                            <label class="form-label">VIP Price: </label>
                            <input type="text" class="form-control" name="concert_vipprice"
                                value="<?php echo $row['vip_price'] ?>">
                        </div>

                        <div class="form-group mb-3 col">
                            <label class="form-label">GenAd Price: </label>
                            <input type="text" class="form-control" name="concert_genadprice"
                                value="<?php echo $row['genad_price'] ?>">
                        </div>
                    </div>

                    <div class="form-group mb-3 col">
                        <label class="form-label">Contact: </label>
                        <input type="text" class="form-control" maxlength="250" name="concert_contact"
                            value="<?php echo $row['concert_contact'] ?>">
                    </div>

                    <div>
                        <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png"
                            class="form-control" value="<?php echo $row['image'] ?>">
                        <?php
                        $currentImageName = $row['image'];
                        if(!empty($currentImageName)) {
                            echo 'Current Image: '.$currentImageName;
                        } else {
                            echo 'No image uploaded';
                        }
                        ?>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-success" name="submit">Update</button>
                        <a href="viewconcert.php" class="btn btn-danger">Cancel</a>
                    </div>
            </form>
        </div>
    </div>

    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>
</body>

</html>