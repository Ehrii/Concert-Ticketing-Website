<?php
include "config.php";
$id = $_GET['id'];

if(isset($_POST['submit'])) {
    $errors = array();
    $cust_username = $_POST['username'];
    $cust_email = $_POST['email'];
    $cust_password = $_POST['password'];
    if(!empty($cust_password)) {
        $newHash = md5($cust_password);
    }
    $cust_fullname = $_POST['fullname'];
    $cust_dob = $_POST['dob'];
    $cust_phonenum = $_POST['phonenum'];
    $cust_address = $_POST['address'];
    $cust_accdate = $_POST['accdate'];
    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'uploaded_img/'.$update_image;
}
$errors = [];


if($_SERVER["REQUEST_METHOD"] == "POST") {


    if(!empty($update_image)) {
        if($update_image_size > 2000000) {
            $errors[] = "Image size is too large. Please select an image smaller than 2MB.";
        } else {
            $image_update_query = mysqli_query($conn, "UPDATE `user_form` SET image = '$update_image' WHERE id = '$id'") or die('query failed');
            if($image_update_query) {
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
            }
        }
    }

    if(strlen($cust_address) < 11 || strlen($cust_address) > 100) {
        $errors[] = "Address must be between 11 and 100 characters.";
    }

    if(preg_match('/[0-9]/', $cust_fullname)) {
        $errors[] = "Fullname must not consist of numbers.";
    }

    if(empty($cust_phonenum)) {
        $errors[] = "Phone number is required.";
    } elseif(!preg_match('/^[0-9]{11}$/', $cust_phonenum)) {
        $errors[] = "Invalid phone number. Please enter a valid 11-digit numeric phone number.";
    }


    if(!empty($cust_password) && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $cust_password)) {
        $errors[] = "Password must contain at least 1 lowercase letter, 1 uppercase letter, 1 digit, 1 special character, and be at least 8 characters long.";
    }

    if(empty($errors)) {
        $passwordUpdate = !empty($cust_password) ? "`password`='$newHash'," : "";

        $query = "UPDATE `user_form` SET `username`='$cust_username', `email`='$cust_email', $passwordUpdate `phonenum`='$phoneNumber',
                `fullname`='$cust_fullname', `dob`='$cust_dob', `phonenum`='$cust_phonenum',
                `address`='$cust_address', `accdate`='$cust_accdate' WHERE id = $id";

        $result = mysqli_query($conn, $query);

        if($result) {
            header("Location:viewusers.php?msg=Data has been updated");
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
    <title>Edit User</title>
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
        Edit User
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3> Edit User Details </h4>
                <p class="text-muted">If you wish to proceed with the new details, click update</p>
        </div>

        <?php
        $id = $_GET['id'];
        $query = "SELECT * FROM user_form where id =$id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        ?>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:500vw; min-width:300px;" enctype="multipart/form-data">
                <div class="row">
                    <div class="form-group col">
                        <label class="form-label">Username: </label>
                        <input type="text" class="form-control" maxlength="100" name="username"
                            value="<?php echo $row['username'] ?>" required>
                    </div>

                    <div class="form-group col mb-3">
                        <label class="form-label">Email: </label>
                        <input type="email" class="form-control" maxlength="100" name="email"
                            value="<?php echo $row['email'] ?>" required>
                    </div>

                    <div class="form-group  mb-3 col">
                        <label class="form-label">Password: </label>
                        <input type="password" maxlength="100" class="form-control" name="password">
                    </div>


                    <div class="form-group mb-3 ">
                        <label clas="form-label">Full Name: </label>
                        <input type="text" class="form-control" maxlength="100" name="fullname"
                            value="<?php echo $row['fullname'] ?>" required>
                    </div>

                    <div class="form-group mb-3 col">
                        <label class="form-label">Date of birth: </label>
                        <input type="date" class="form-control" name="dob" value="<?php echo $row['dob'] ?>" required>
                    </div>


                    <div class="row">
                        <div class=" mb-3 col">
                            <label class="form-label">Phone Number: </label>
                            <input type="number" class="form-control" name="phonenum" maxlength="11"
                                value="<?php echo $row['phonenum'] ?>" required>
                        </div>

                        <div class=" mb-3 col">
                            <label class="form-label">Address: </label>
                            <input type="text" maxlength="100" class="form-control" name="address"
                                value="<?php echo $row['address'] ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group mb-3 col">
                            <label class="form-label">Account date created: </label>
                            <input type="date" class="form-control" name="accdate" value="<?php echo $row['accdate'] ?>"
                                required>
                        </div>
                    </div>
                    <br><br><br><br>
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
                    <br><br><br>

                    <div>
                        <button type="submit" class="btn btn-success col" name="submit">Update</button>
                        <a href="viewusers.php" class="btn btn-danger col">Cancel</a>
                    </div>


            </form>
        </div>
    </div>

    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>

</html>