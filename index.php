<?php
include 'config.php';
$sql = "SELECT * FROM tblconcert order by concert_date";
$all_concert = $conn->query($sql);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title>Musiverse</title>
    <link rel="icon" type="image/x-icon" href="css/images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>
    <div class="hero">
        <video autoplay loop muted plays-inline class="back-video">
            <source src="css/images/concertopener.mp4" type="video/mp4">
        </video>

        <nav>
            <img src="css/images/logo.png" class="logo" width=90px>
            <ul>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> SIGN UP</a></li>
                <li><a href="login.php"><i class="fas fa-user"></i> LOGIN</a></li>

            </ul>
        </nav>
        <div class="content">
            <h1>MUSIVERSE</h1>
            <p>
                <i class="fas fa-music"></i> At Musiverse concerts, every beat tells a story, and every melody invites
                you to dance in the rhythm of the cosmos.
                <br>
                <i class="fas fa-map-marker-alt"></i> Only in Araneta Coliseum, General Roxas Ave, Cubao, Quezon City,
                1109 Metro Manila
            </p>
        </div>
    </div>


    <div class="container">

        <br>
        <h1 class="heading">AVAILABLE UPCOMING CONCERTS</h1>
        <p style="color:cyan; font-weight:bold;">
            <?php echo date('F j, Y') ?> -
            <?php echo date('F j, Y', strtotime(date('Y-m-t'))); ?>
        </p>


        <br>
        <p> Explore our vibrant lineup of upcoming concerts, where every beat tells a unique story. From chart-topping
            artists to emerging talents, our diverse selection offers an unforgettable musical experience. Discover the
            excitement and reserve your spot now!</p> <br>
        <p>Join us at Araneta Coliseum, where every concert becomes a timeless memory.</p><br><br>

        <div class="box-container">
            <?php

            if($all_concert->num_rows > 0) {
                while($row = $all_concert->fetch_assoc()) {
                    ?>
                    <div class="box">
                        <?php echo '<img src="adminside/uploaded_img/'.$row["image"].'" class="concert-image" alt="Concert Image">'; ?>
                        <h3>
                            <?php echo $row['concert_name']; ?>
                        </h3>
                        <p>
                            <?php echo date('F j, Y', strtotime($row['concert_date'])); ?><br>
                        </p>
                        <a href="login.php" class="btn">Buy Tickets</a>
                    </div>
                    <?php
                }
            } else {
                echo "No concerts available.";
            }
            $conn->close();
            ?>

        </div>
    </div>

</body>


<div class="row secondary">
    <div>
        <p>
            <i class="fas fa-phone-alt"></i>
        </p>
        <p>+63 9673411161</p>
    </div>
    <div>
        <p><i class="fas fa-envelope"></i></p>
        <p>amitysociety101@gmail.com</p>
    </div>
    <div>
        <p><i class="fas fa-map-marker-alt"></i></p>
        <p>80 Shaw Blvd, Mandaluyong, 1552 Metro Manila </p>
    </div>
</div>
<div class="row copyright">
    <p>Copyright &copy; 2023 Musiverse | All Rights Reserved</p>
</div>

</html>