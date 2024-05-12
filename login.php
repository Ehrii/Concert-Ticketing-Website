<?php
session_start();
include 'config.php';

if(isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');
    $select2 = mysqli_query($conn, "SELECT * FROM `admin` WHERE admin_email = '$email' AND admin_password = '$pass'") or die('query failed');

    if(mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $_SESSION['user_id'] = $row['id'];
        header('location:home.php');
    } else if(mysqli_num_rows($select2) > 0) {
        $row = mysqli_fetch_assoc($select2);
        $_SESSION['admin_id'] = $row['admin_id'];
        header('location:adminside/dashboard.php');
    } else {
        $message[] = 'incorrect email or password!';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="css/images/logo.png">
    <link rel="stylesheet" href="css/profile.css">

</head>
<body>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3 style="font-size:23px; color: #0D7377; word-spacing:3px; background-color:#333; border-radius: 5px; color: #30E3CA; box-shadow:0 10px 10px rgba(0,0,0,.1);"
                class="headertext">
                <i class="fas fa-music"></i> WELCOME TO THE MUSIVERSE
            </h3>
            <a href="index.php" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>

            <img src="css/images/logo.png" style="width: 300px; " class="concertlogo">
            <h3 style="text-decoration:underline; text-decoration-thickness: 5px; text-decoration-color: #0D7377;">User
                Login</h3>
            <?php
            if(isset($message)) {
                foreach($message as $message)
                    echo '<div class="message">'.$message.'</div>';
            }
            ?>
            <div class="input-container">
                <img src="css/images/mail.png" class="icon" style="width: 45px; vertical-align: middle;">
                <input type="email" name="email" placeholder="enter email" class="box" required>
            </div>
            <div class="input-container">
                <img src="css/images/padlock.png" class="icon" style="width: 45px; vertical-align: middle; ">
                <input type="password" name="password" placeholder="enter password" class="box" required>
            </div>

            <input type="submit" name="submit" value="Login now" class="btn">
            <input type="button" name="forgot" value="Forgot Password?" class="btn" style="background-color:#0D7377"
                onclick="redirectToForgotPage()">
            <p>Don't have an account? <a href="register.php">Register now</a></p>
        </form>

    </div>
</body>
<script>
    function redirectToForgotPage() {
        window.location.href = "forgot.php";
    }
</script>

</html>