<?php
session_start();
include 'config.php';


if(isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmpassword']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['newpassword']);
    $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email'") or die('query failed');

    if(isset($_POST['submit'])) {
        if(mysqli_num_rows($select) > 0) {
            if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}$/', $newPassword)) {
                $message[] = "New password must contain at least 1 lowercase letter, 1 uppercase letter, 1 digit, 1 special character, and be at least 8 characters long.";
            } elseif($newPassword != $confirmPassword) {
                $message[] = "New password and confirm password do not match.";
            } else {
                $hashedPassword = md5($newPassword);
                $updateQuery = "UPDATE `user_form` SET password = '$hashedPassword' WHERE email = '$email'";
                $result = mysqli_query($conn, $updateQuery);
                if($result) {
                    $message[] = 'Password Updated';
                } else {
                    $message[] = 'Error updating password!';
                }
            }
        } else {
            $message[] = 'Incorrect email!';
        }
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
    <title>Forgot Password</title>
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
            <img src="css/images/logo.png" style="width: 300px; ">
            <h3 style="text-decoration:underline; text-decoration-thickness: 5px; text-decoration-color: #0D7377;">
                Forgot Password</h3>
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
                <input type="password" name="newpassword" placeholder="new password" class="box" required>
            </div>

            <div class="input-container">
                <img src="css/images/padlock.png" class="icon" style="width: 45px; vertical-align: middle; ">
                <input type="password" name="confirmpassword" placeholder="confirm new password" class="box" required>
            </div>

            <input type="submit" name="submit" value="Update Password" class="btn">
            <input type="button" name="forgot" value="Back to Login" class="btn" style="background-color:#444;"
                onclick="redirectToLoginPage()">
        </form>
    </div>
    <script>
        function redirectToLoginPage() {
            window.location.href = "login.php";
        }
    </script>

</body>

</html>