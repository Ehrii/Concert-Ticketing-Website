<?php

session_start();
include 'config.php';
$user_id = $_SESSION['user_id'];

if(isset($user_id)) {
} else {
   echo "User ID is not set in the session.";
}

$query = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
if(mysqli_num_rows($query) > 0) {
   $fetch = mysqli_fetch_assoc($query);
}

if(isset($_POST['update_profile'])) {
   date_default_timezone_set('Asia/Singapore');
   $firstmsg = 'Records Last Updated on '.date('h:i:s A');

   $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
   $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
   $update_number = mysqli_real_escape_string($conn, $_POST['update_number']);
   $update_add = mysqli_real_escape_string($conn, $_POST['update_add']);
   $update_fullname = mysqli_real_escape_string($conn, $_POST['update_fullname']);
   $update_dob = mysqli_real_escape_string($conn, $_POST['update_dob']);

   mysqli_query($conn, "UPDATE `user_form` SET username = '$update_name', email = '$update_email' WHERE id = '$user_id'") or die('query failed');

   $old_pass = $_POST['old_pass'];
   $update_pass = mysqli_real_escape_string($conn, md5($_POST['update_pass']));
   $new_pass = mysqli_real_escape_string($conn, md5($_POST['new_pass']));
   $confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));


   if(!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
      if($update_pass != $old_pass) {
         $message[] = 'Old password not matched!';
      } elseif($new_pass != $confirm_pass) {
         $message[] = 'Confirm password not matched!';
      } elseif(strlen($_POST['new_pass']) < 8) {
         $message[] = 'Password should be at least 8 characters long!';
      } elseif(!preg_match('/[a-z]/', $_POST['new_pass']) || !preg_match('/[A-Z]/', $_POST['new_pass'])) {
         $message[] = 'Password should contain both uppercase and lowercase characters!';
      } elseif(!preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/', $_POST['new_pass'])) {
         $message[] = 'Password should contain at least one special character!';
      } else {

         mysqli_query($conn, "UPDATE `user_form` SET password = '$confirm_pass' WHERE id = '$user_id'") or die('Query failed');
         $message[] = 'Password updated successfully!';
      }
   } else {
      $message[] = 'Note: No password-related fields are filled';
   }

   if(!strtotime($update_dob) || strtotime($update_dob) > time()) {
      $message[] = 'Invalid date of birth or future date not allowed.';
   } else {

      mysqli_query($conn, "UPDATE `user_form` SET dob = '$update_dob' WHERE id = '$user_id'") or die('query failed');
   }

   if(!preg_match('/^\d{11}$/', $update_number)) {
      $message[] = 'Invalid phone number format (only 11 digits required).';
   } else {
      mysqli_query($conn, "UPDATE `user_form` SET phonenum = '$update_number' WHERE id = '$user_id'") or die('query failed');

   }

   if(strlen($update_add) < 10 || strlen($update_add) > 100) {
      $message[] = 'Invalid address length (10-100 characters allowed).';
   } else {
      mysqli_query($conn, "UPDATE `user_form` SET address = '$update_add' WHERE id = '$user_id'") or die('query failed');

   }

   if(!preg_match('/^[a-zA-Z ]+$/', $update_fullname)) {
      $message[] = "Full name should contain only alphabetic characters";
   } else {
      mysqli_query($conn, "UPDATE `user_form` SET fullname = '$update_fullname' WHERE id = '$user_id'") or die('query failed');
   }
   if(!ctype_digit($update_number)) {
      $message[] = "Phone Number should contain only numeric characters";
   } else {
      mysqli_query($conn, "UPDATE `user_form` SET phonenum = '$update_number' WHERE id = '$user_id'") or die('query failed');
   }

   $update_image = $_FILES['update_image']['name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_folder = 'uploaded_img/'.$update_image;

   if(!empty($update_image)) {
      if($update_image_size > 2000000) {
         $message[] = 'image is too large';
      } else {
         $image_update_query = mysqli_query($conn, "UPDATE `user_form` SET image = '$update_image' WHERE id = '$user_id'") or die('query failed');
         if($image_update_query) {
            move_uploaded_file($update_image_tmp_name, $update_image_folder);
         }
         $message[] = 'image updated successfully!';
      }
   }

} else if(isset($_POST['proceed_payment'])) {
   header('location:paymenthome.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="css/profile.css">
   <title>Update Profile</title>
   <link rel="icon" type="image/x-icon" href="css/images/logo.png">
</head>

<body>
   <div class="update-profile">
      <?php
      $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select) > 0) {
         $fetch = mysqli_fetch_assoc($select);
      }
      ?>
      <form action="" method="post" enctype="multipart/form-data">
         <?php
         if($fetch['image'] == '') {
            echo '<img src="css/images/avatar.png">';
         } else {
            echo '<img src="uploaded_img/'.$fetch['image'].'">';
         }

         if(isset($firstmsg)) {
            echo '<div class="firstmsg">'.$firstmsg.'</div>';
         }

         if(isset($message)) {
            foreach($message as $message) {
               echo '<div class="message">'.$message.'</div>';
            }
         }
         ?>
         <div class="flex">
            <div class="inputBox">
               <span>Username :</span>
               <input type="text" name="update_name" value="<?php echo $fetch['username']; ?>" class="box">
               <span>Email :</span>
               <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">
               <span>Phone Number :</span>
               <input type="text" name="update_number" value="<?php echo $fetch['phonenum']; ?>"
                  placeholder="confirm new password" class="box">
               <span>Street Address :</span>
               <input type="text" name="update_add" value="<?php echo $fetch['address']; ?>"
                  placeholder="confirm new address" class="box">
               <span>Full Name :</span>
               <input type="text" name="update_fullname" value="<?php echo $fetch['fullname']; ?>"
                  placeholder="confirm new name" class="box">
               <span>Avatar :</span>
               <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
            </div>
            <div class="inputBox">
               <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>"
                  placeholder="enter old password">
               <span>Old password :</span>
               <input type="password" name="update_pass" placeholder="enter previous password" class="box">
               <span>New password :</span>
               <input type="password" name="new_pass" placeholder="enter new password" class="box">
               <span>Confirm password :</span>
               <input type="password" name="confirm_pass" placeholder="confirm new password" class="box">

               <span>Date of Birth :</span>
               <input type="date" name="update_dob" value="<?php echo $fetch['dob']; ?>" placeholder="" class="box">
            </div>
         </div>
         <input type="submit" value="Update Profile" name="update_profile" class="btn">
         <input type="submit" value="Proceed To Payment" name="proceed_payment" class="btn">
         <a href="home.php" class="delete-btn">Go Back</a>
      </form>
   </div>
</body>

</html>