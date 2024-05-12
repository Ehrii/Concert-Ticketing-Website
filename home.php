<?php
include 'config.php';
session_start();


if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = null;
}

$sql = "SELECT * FROM tblconcert";
$all_concert = $conn->query($sql);

$select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
if(mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);
} else {
    $fetch = null;
}

if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}


$itemsPerPage = 3;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $itemsPerPage;
$sql = "SELECT * FROM tblconcert LIMIT $offset, $itemsPerPage";
$all_concert = $conn->query($sql);


$totalConcerts = $conn->query("SELECT COUNT(*) FROM tblconcert")->fetch_row()[0];
$totalPages = ceil($totalConcerts / $itemsPerPage);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="icon" type="image/x-icon" href="css/images/logo.png">
    <link rel="stylesheet" href="css/homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>



    <header class="header" style="box-shadow:0 10px 10px rgba(0,0,0,.2);">

        <a href="home.php" class="logo">
            <i class="fas fa-music" style="color: #00ADB5;"></i>
            <span style="color:#00ADB5;">Musi</span>Verse</a>
        <nav class="navbar">
            <a href="#home"></i>Home</a>
            <a href="#concerts">Concerts</a>
            <a href="#about">About Us</a>
            <a href="#contact">Contact Us</a>
            <a href="ticketshistory.php">Tickets</a>
            <a href="update_profile.php"></i> Profile </a>
            <a href="login.php?logout=1 " class="logout">Log-Out</a>
        </nav>
        <div id="menu-bars" class="fas fa-bars">

        </div>


    </header>

    <script src="js/navbar.js"></script>


    <section class="home" id="home">
        <div class="wrapper">
            <div class="box">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>

        <div class="content">
            <img src="css/images/bghome.png" class="musilogo">

            <h3>Your Universe of Music Tickets <span>/ MUSIVERSE.PH</span></h3>
            <a href="ticketshistory.php" class="btn">My Tickets</a>
        </div>

        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="css/images/card1.png" />
                </div>
                <div class="swiper-slide">
                    <img src="css/images/card2.png" />
                </div>
                <div class="swiper-slide">
                    <img src="css/images/card3.png" />
                </div>
                <div class="swiper-slide">
                    <img src="css/images/card4.png" />
                </div>
                <div class="swiper-slide">
                    <img src="css/images/card5.png" />
                </div>
                <div class="swiper-slide">
                    <img src="css/images/card6.png" />
                </div>
                <div class="swiper-slide">
                    <img src="css/images/card7.jpg" />
                </div>
                <div class="swiper-slide">
                    <img src="css/images/card8.png" />
                </div>
                <div class="swiper-slide">
                    <img src="css/images/card9.png" />
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>



        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <section class="con" id="concerts">
            <div class="content2">
                <h3>Upcoming <span>Concerts</span></h3>
                <p class="intro"> Get ready for an unforgettable music journey! We are thrilled to present our upcoming
                    concerts, where the stage will come alive with sensational performances by some of the most renowned
                    artists
                    in the industry. From heart-pounding rock to soulful melodies and electrifying pop beats, our
                    concerts promise
                    an exhilarating fusion of music genres that will leave you spellbound. Join us for a night of
                    rhythm, harmony,
                    and pure musical magic.</p>
            </div>
            <?php
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $startPage + 4);

            // Adjust startPage if necessary to display 5 pages
            if($endPage - $startPage + 1 < 5) {
                $startPage = max(1, $endPage - 4);
            }
            ?>
            <div class="pagination">
                <?php if($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">&lt;</a>
                <?php endif; ?>

                <?php for($i = $startPage; $i <= $endPage; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" <?php if($i == $page)
                           echo 'class="active"'; ?>>
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">&gt;</a>
                <?php endif; ?>
            </div>
            <br>
            <?php
            while($row = $all_concert->fetch_assoc()) {
                $concertId = $row["concert_id"];
                $url = 'concert.php?concert_id='.urlencode($concertId);
                ?>
                <div class="container">
                    <div class="card">
                        <div class="imgBx">
                            <a href="#">
                                <?php echo '<img src="adminside/uploaded_img/'.$row["image"].'">'; ?>
                                <h2><br>
                                    <?php echo $row["concert_name"]; ?>
                                </h2>
                                <div class="desc">
                                    <p><br>Artist Name:
                                        <?php echo $row["concert_artist"]; ?><br>
                                        Concert Date:
                                        <?php echo date('F j, Y', strtotime($row['concert_date'])); ?><br>
                                        Concert ID:
                                        <?php echo $row["concert_id"]; ?><br>
                                        Genre:
                                        <?php echo $row["concert_genre"]; ?> <br>
                                        Venue:
                                        <?php echo $row["concert_venue"]; ?>
                                    </p>
                                </div>
                                <div class="button-container">
                                    <a class="button" href="<?php echo $url; ?>">Learn More</a>
                                </div>
                        </div>
                    </div>
                    <?php
            }
            ?>
            </div>

        </section>
    </section>

    <section class="about" id="about">
        <br><br><br>
        <h3>About Us</h3>
        <p style="margin:50px;"> Get ready for an unforgettable music journey! We are thrilled to present our upcoming
            concerts, where the stage will come alive with sensational performances by some of the most renowned artists
            in the industry. From heart-pounding rock to soulful melodies and electrifying pop beats, our concerts
            promise
            an exhilarating fusion of music genres that will leave you spellbound. Join us for a night of rhythm,
            harmony,
            and pure musical magic.</p>
        </div>
        <div class="card-aboutus">
            <div class="cardcon">
                <img src="css/images/aboutus1.png">
                <div class="card-aboutcon">
                    <h1>Unparalleled Concert Selection</h2>
                        <p>At Musiverse, we pride ourselves on curating a diverse and extensive selection of concerts to
                            cater to
                            every musical taste. Whether you're into chart-topping pop, soulful jazz, or headbanging
                            rock, Musiverse
                            is your one-stop destination for discovering and securing tickets to the hottest live
                            performances.</p>
                        <a href="#contact" class="card-button"> Read More </a>
                </div>
            </div>
            <div class="cardcon">
                <img src="css/images/aboutus2.png">
                <div class="card-aboutcon">
                    <h1>Seamless Ticketing Experience</h2>
                        <p>Our intuitive platform allows you to browse events, select your preferred seats, and complete
                            your
                            purchase with ease. With secure payment options and instant confirmations, we prioritize
                            making your
                            journey from discovery to attendance as smooth as the harmonies you'll hear at our featured
                            concerts.</p>
                        <a href="#contact" class="card-button"> Read More </a>
                </div>
            </div>
            <div class="cardcon">
                <img src="css/images/aboutus3.png">
                <div class="card-aboutcon">
                    <h1>Dedicated to Your Concert Experience</h2>
                        <p>We understand that a concert is more than just an event; it's an experience. Musiverse is
                            dedicated to
                            enhancing your concert journey by providing valuable information about venues, artists, and
                            the overall
                            atmosphere of each event. From tips on the best seats to insider insights on the must-see
                            performances, we
                            go the extra mile to ensure that your concert experience with Musiverse is nothing short of
                            extraordinary.
                        </p>
                        <a href="#contact" class="card-button"> Read More </a>
                </div>
            </div>
            <div class="cardcon">
                <img src="css/images/aboutus4.png ">
                <div class="card-aboutcon">
                    <h1>Unrivaled Artist Lineup</h2>
                        <p>At Musiverse, we take pride in offering an unparalleled array of artists and performers. From
                            global
                            chart-toppers to emerging talents, our carefully curated lineup ensures that you'll always
                            find the
                            musical experiences you crave. We're committed to bringing you the best in live
                            entertainment, making
                            Musiverse your go-to destination for discovering the next big thing or catching your
                            favorite acts in
                            action.</p>
                        <a href="#contact" class="card-button"> Read More </a>
                </div>
            </div>
    </section>

    <section class="service">
        <div class="services">
            <div class="slide-container active">
                <div class="slide">
                    <div class="servicecon">
                        <h3>What payment methods do you accept?</h3>
                        <p>We only accept a variety of payment methods, including major credit cards, debit cards, and
                            cash.</p>
                        <a href="faq.html" class="btn">Learn More</a>
                    </div>
                    <video src="css/images/concert1.mp4" muted autoplay loop></video>
                </div>
            </div>


            <div class="slide-container ">
                <div class="slide">
                    <div class="servicecon">
                        <h3>How can I stay informed about upcoming concerts and events?</h3>
                        <p>Stay in the loop by subscribing to our newsletter or following us on social media.</p>
                        <a href="faq.html" class="btn">Learn More</a>
                    </div>
                    <video src="css/images/concert2.mp4" muted autoplay loop></video>
                </div>
            </div>


            <div class="slide-container ">
                <div class="slide">
                    <div class="servicecon">
                        <h3>Are there any additional fees when purchasing tickets?</h3>
                        <p>None, Your satisfaction is our priority. By forgoing service charges, we prioritize
                            delivering a valuable and enjoyable experience for every user.</p>
                        <a href="faq.html" class="btn">Learn More</a>
                    </div>
                    <video src="css/images/concert3.mp4" muted autoplay loop></video>
                </div>
            </div>

            <div id="next" onclick="next()">></div>
            <div id="prev" onclick="prev()">
                < </div>
    </section>

    <section class="personnel" id="Meet">
        <a href="#Meet">
            <h3>MEET <span>THE TEAM</span></h3>
        </a>
        <p class="persondesc"> At Musiverse, we believe that behind every incredible concert experience is a team of
            passionate individuals dedicated to bringing you the best in live music. Get to know the faces and stories
            behind
            the scenes, each contributing their unique talents to curate unforgettable musical journeys.</p>

        <div id="personcontainer">

            <br><br>
            <div class="teamcon">
            </div>
            <div class="sub-container">
                <img src="css/images/person1.png" alt="Person 1">
            </div>
            <div class="sub-container">
                <img src="css/images/person2.png" alt="Person 1">
            </div>
            <div class="sub-container">
                <img src="css/images/person3.png" alt="Person 1">
            </div>
            <div class="sub-container">
                <img src="css/images/person4.png" alt="Person 1">
            </div>
        </div>
    </section>


    <section class="contact" id="contact">
        <h1 class="heading"><span>CONTACT US</span></h1>

        <p> We value your feedback, inquiries, and the opportunity to connect with our community. Whether you have a
            question about our products, need assistance with an order, or simply want to share your thoughts, we're
            here to help.

            Feel free to reach out to us using the contact form below, and our dedicated team will get back to you as
            soon as possible. We appreciate your interest in Musiverse, and we look forward to assisting you.!</p>

        <div class="contactcon">
            <form action="https://formspree.io/f/mwkdgerp" method="POST">
                <div class="inputBox">
                    <input type="text" placeholder="name" name="Name" value="<?php echo $fetch['fullname'] ?>">
                    <input type="email" value="<?php echo $fetch['email'] ?>" placeholder="email" name="Email" readonly>
                </div>
                <div class="inputBox">
                    <input type="number" placeholder="number" name="Phonenumber"
                        value="<?php echo $fetch['phonenum'] ?>" readonly>
                    <select name="subject" id="subjectSelect">
                        <option value="" disabled selected>Select a subject</option>
                        <option value="Unlinking a Credit Card">Unlink Credit Card</option>
                        <option value="Reporting a Bug">Reporting a Bug</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <textarea name="Message" placeholder="your message" id="" cols="30" rows="10"> </textarea>
                <input type="submit" value="send message" class="btn">
            </form>
            <image src="css/images/contact.png" class="contactimg">
        </div>
    </section>

    <footer>

        <div class="row primary">
            <div class="columncomp">
                <h3>Musiverse.Corp</h3>
                <p>
                    Welcome to Musiverse, your virtual gateway to a world of live music experiences. At Musiverse, we
                    believe in the power of music to connect, inspire, and create unforgettable moments. Our platform is
                    designed to bring the magic of live concerts directly to your screens, wherever you are.
                </p>
            </div>
            <div class="column links">
                <h3>Quick Links</h3>
                <ul>
                    <li>
                        <a href="faq.html">F.A.Q</a>
                    </li>
                    <li>
                        <a href="ticketshistory.php">Tickets</a>
                    </li>
                    <li>
                        <a href="#Meet">Meet the team</a>
                    </li>
                    <li>
                        <a href="#concerts">Upcoming Concerts</a>
                    </li>
                </ul>
            </div>
            <div class="column subscribe">
                <form action="https://formspree.io/f/mwkdgerp" method="POST">
                    <h3>Subscribe</h3>
                    <div>
                        <input type="email" name="email " placeholder="Your email address here" />
                        <button>Subscribe</button>
                    </div>
                </form>
                <div class="social">
                    <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook-square"></i></a>
                    <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram-square"></i></a>
                    <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter-square"></i></a>
                </div>
            </div>
        </div>
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
    </footer>
    <script src="js/home.js"></script>
</body>