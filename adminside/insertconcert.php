<?php
include "config.php";
$conid = $_GET['concert_id'];


if(isset($_POST['submit'])) {
    $concert_name = $_POST['concert_name'];
    $concert_date = $_POST['concert_date'];
    $concert_time = $_POST['concert_time'];
    $concert_artist = $_POST['concert_artist'];
    $concert_desc = $_POST['concert_desc'];
    $concert_desc = mysqli_real_escape_string($conn, $concert_desc);
    $concert_genre = $_POST['concert_genre'];
    $concert_venue = $_POST['concert_venue'];
    $concert_ubprice = $_POST['concert_ubprice'];
    $concert_lbprice = $_POST['concert_lbprice'];
    $concert_vipprice = $_POST['concert_vipprice'];
    $concert_genadprice = $_POST['concert_genadprice'];
    $concert_contact = $_POST['concert_contact'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/'.$image;


    if(empty($concert_name) || empty($concert_date) || empty($concert_time) || empty($concert_artist) || empty($concert_desc) || empty($concert_genre) || empty($concert_venue) || empty($concert_ubprice) || empty($concert_lbprice) || empty($concert_vipprice) || empty($concert_genadprice) || empty($concert_contact) || empty($image)) {
        $errors[] = "All fields are required.";
    }

    if(!empty($image)) {
        if($image_size > 2000000) {
            $errors[] = "Image size is too large. Please select an image smaller than 2MB.";
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
        date_default_timezone_set('Asia/Singapore');
        $formatted_date = date("Y-m-d", strtotime($concert_date));
        $formatted_time = date("H:i", strtotime($concert_time));

        $query = "INSERT INTO tblconcert (concert_name, concert_date, concert_time, concert_artist, concert_desc, concert_genre, concert_venue, ub_price, lb_price, vip_price, genad_price, concert_contact, image)
        VALUES ('$concert_name', '$formatted_date', '$formatted_time', '$concert_artist', '$concert_desc', '$concert_genre', '$concert_venue', '$concert_ubprice', '$concert_lbprice', '$concert_vipprice', '$concert_genadprice', '$concert_contact', '$image')";


        $result = mysqli_query($conn, $query);
        move_uploaded_file($image_tmp_name, $image_folder);

        if($result) {
            header("Location: viewconcert.php?msg=New concert has been added");
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
    <title>Insert Concert</title>
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
        Insert Concert
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3> Add New Concert </h4>
                <p class="text-muted">Please complete the form below</p>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="container d-flex justify-content-center">
                <form action="" method="post" style="width:500vw; min-width:300px;">
                    <div class="row">
                        <div class="form-group col">
                            <label class="form-label">Concert Name: </label>
                            <input type="text" class="form-control" maxlength=100 name="concert_name"
                                placeholder="Concert Name" required>
                        </div>

                        <div class="form-group col mb-3">
                            <label class="form-label">Date: </label>
                            <input type="date" class="form-control" name="concert_date" required>
                        </div>

                        <div class="form-group  mb-3 col">
                            <label class="form-label">Time: </label>
                            <input type="time" class="form-control" name="concert_time" required>
                        </div>


                        <div class="form-group mb-3 ">
                            <label clas="form-label">Artist: </label>
                            <input type="text" class="form-control" maxlength=50 name="concert_artist"
                                placeholder="Artist Name" required>
                        </div>

                        <div class="form-group mb-3 col">
                            <label class="form-label">Description: </label>
                            <textarea class="form-control" maxlength="2000" name="concert_desc" rows="4"></textarea>
                        </div>

                        <div class="row">
                            <div class=" mb-3 col">
                                <label class="form-label">Genre: </label>
                                <input type="text" class="form-control" maxlength=20 name="concert_genre"
                                    placeholder="Concert Genre" required>
                            </div>

                            <div class=" mb-3 col">
                                <label class="form-label">Venue: </label>
                                <input type="text" class="form-control" maxlength=100 name="concert_venue"
                                    placeholder="Concert Venue" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group mb-3 col">
                                <label class="form-label">UB Price: </label>
                                <input type="number" class="form-control" name="concert_ubprice"
                                    placeholder="Upper Box Price" required>
                            </div>

                            <div class="form-group mb-3 col">
                                <label class="form-label">LB Price: </label>
                                <input type="number" class="form-control" name="concert_lbprice"
                                    placeholder="Lower Box Price" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group mb-3 col">
                                <label class="form-label">VIP Price: </label>
                                <input type="number" class="form-control" name="concert_vipprice"
                                    placeholder="VIP Price" required>
                            </div>

                            <div class="form-group mb-3 col">
                                <label class="form-label">GenAd Price: </label>
                                <input type="number" class="form-control" name="concert_genadprice"
                                    placeholder="GenAd Price" required>
                            </div>
                        </div>

                        <div class="form-group mb-3 col">
                            <label class="form-label">Contact: </label>
                            <input type="text" class="form-control" maxlength=250 name="concert_contact"
                                placeholder="Contact Email" required>
                        </div>

                        <div class="form-group mb-3 col">
                            <label class="form-label" for="image">Concert Poster Picture:</label>
                            <input type="file" id="image" name="image" class="form-control"
                                accept="image/jpg, image/jpeg, image/png">
                        </div>

                        <div>
                            <button type="submit" class="btn btn-success col" name="submit">Save</button>
                            <a href="viewconcert.php" class="btn btn-danger col">Cancel</a>
                        </div>


                </form>
                <br><br><br>
            </div>
    </div>
    </form>
    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>
</body>

</html>