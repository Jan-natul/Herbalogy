
<?php
session_start();
include("connect.php");

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');

    $errors = [];

    if (strlen($username) < 2) $errors[] = "Username too short";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
    if (!preg_match('/^[0-9+\-\s]{7,20}$/', $phone)) $errors[] = "Invalid phone number";

    // duplicate email check
    $chk = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $chk->bind_param("s", $email);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows > 0) $errors[] = "Email already registered";
    $chk->close();

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, phone) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $phone);
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header('Location: thankyou.php'); // success redirect
            exit;
        } else {
            error_log("DB insert error: " . $stmt->error);
            $errors[] = "Internal error, try later.";
            $stmt->close();
        }
    }
}

// generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Herbalogy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section id="home">
    <div class="main">
        <div class="navbar">
            <div class="icon">
                <h2 class="logo">Herbalogy</h2>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="#">HOME</a></li>
                    <li><a href="#">ABOUT</a></li>
                    <li><a href="#">PRODUCT</a></li>
                    <li><a href="#">REVIEW</a></li>
                    <li><a href="#">CONTACT</a></li>
                </ul>
            </div>
        </div>
        <div class="title">
            <h1>Welcome to <br> Herbalogy</h1>
            <p class="par">A place where you can gather knowledge <br>about herbs also buy the herbs</p>
            <button class="cn"><a href="#"> Visit Us </a></button>
        </div>
        <div class="form">
            <h2>Login Here</h2>
            <form action="connect.php" method="post">
                <div class="inputbox">
                    <input type="text" value="" name="username" id="username" required>
                    <span>Username</span>
                </div>
                <div class="inputbox">
                    <input type="email" value="" name="email" id="email" required>
                    <span>E-mail</span>
                </div>
                <div class="inputbox">
                    <input type="text" value="" name="phone" id="phone" required>
                    <span>Phone Number</span>
                </div>
                <input type="submit" value="submit" class="sub" id="submit">
            </form>
        </div>
    </div>
</section>

<section id="about">
    <div class="container">
        <div class="about-content">
            <h1>About Us</h1>
            <p>
                Our motive is to give infomation about herbs <br>also helps to find those herbs digitally.<br> 
                This website helps you to gather knowledge of herds.<br>We can benifited from herbs in many ways.<br> 
                We can eat this herbs which helps our health<br> from many disease.<br>Also, we can apply herbs on skin, hair etc.
            </p>
            <img src="public/img 7.png"/>
        </div>
    </div>
</section>

<section id="products">
    <div class="container">
        <div class="section-title">
            <h1>Our Products</h1>
        </div>
        <div class="box">
            <div class="product">
                <img src="public/img 1.jpg"/>
                <h1>Basil</h1>
                <p>
                    Basil is a culinary herb of the family Lamiaceae.It is a tender plant, and is used in cuisines worldwide. 
                    The leaves are used fresh or dried to flavour meats, fish, salads, and sauces.Basil tea is a stimulant. 
                    Sweet basil can provide vitamins, minerals, and a range of antioxidants. Its essential oil may also have medicinal benefits.
                </p>
                <a href="https://moslawala.com/product-category/spices_herbs/basil/">Buy</a>
            </div>
            <div class="product">
                <img src="public/img 2.jpg"/>
                <h1>Mint</h1>
                <p>
                    ular herb that people can use fresh or dried in many dishes and infusions. 
                    Mint belongs to the Lamiaceae family, which contains around 15–20 plant species, including peppermint and spearmint. 
                    It is a popUsing fresh mint in cooking can help a person add flavor while reducing their sodium and sugar intake.
                </p>
                <a href="https://moslawala.com/product-category/spices_herbs/mint/">Buy</a>
            </div>
            <div class="product">
                <img src="public/img 3.jpg"/>
                <h1>Tulsi</h1>
                <p>
                    Tulasi is cultivated for religious and traditional medicine purposes, and also for its essential oil. 
                    It is widely used as an herbal tea, commonly used in Ayurveda, and has a place within the Vaishnava tradition of Hinduism, 
                    in which devotees perform worship involving holy basil plants or leaves.
                </p>
                <a href="https://www.daraz.com.bd/products/trinamool-herbal-basil-powder-100gm-tulsi-gura-100gm-i137788930.html?spm=a2a0e.searchlist.list.29.171628ebL5ANM7">Buy</a>
            </div>
            <div class="product">
                <img src="public/img 4.jpg"/>
                <h1>Lemongrass</h1>
                <p>
                    Lemongrass is a plant in the grass family. There are over 100 lemongrass species, including Cymbopogon citratus, which is often used in foods and medicine. 
                    Lemongrass leaf and essential oil contain chemicals that might help prevent some bacteria and yeast from growing.
                </p>
                <a href="https://www.farmersbestbd.com/shop/vegetable-fruit/exotic-veggies/lemon-grass-stalk-250-gm/">Buy</a>
            </div>
            <div class="product">
                <img src="public/img 5.jpg"/>
                <h1>Rosemary</h1>
                <p>
                    Rosemary is an ingredient that adds a fragrant, savory note to dishes. 
                    Some people claim that rosemary can help reduce muscle pain, boost the immune system, and improve memory. 
                    Rosemary oil helps to grow hair and increase hair lenth. Rosemary may have beneficial effects on the skin.
                </p>
                <a href="https://www.google.com/url?sa=t&source=web&rct=j&opi=89978449&url=https://www.poran.com.bd/shop/rosemary-dried-leaves-for-hair-growth-rosemary-leaf-tea-50g/&ved=2ahUKEwjShPWllsWIAxVnyDgGHYqYHfsQFnoECCsQAQ&usg=AOvVaw1JQb6hKw1vISJGz4CwFZ7G">Buy</a>
            </div>
            <div class="product">
                <img src="public/img 6.jpg"/>
                <h1>Parsley</h1>
                <p>
                    parsley hardy biennial herb of the carrot family native to Mediterranean lands. 
                    Parsley leaves were used by the ancient Greeks and Romans as a flavouring and garnish for foods. 
                    The leaves are used fresh or dried, their mildly aromatic flavour being popular with fish, meats, soups, sauces, and salads.
                </p>
                <a href="https://www.google.com/url?sa=t&source=web&rct=j&opi=89978449&url=https://organiconline.com.bd/shop/grocery/spice/parsley-%25E0%25A6%25AA%25E0%25A6%25BE%25E0%25A6%25B0%25E0%25A7%258D%25E0%25A6%25B8%25E0%25A6%25B2%25E0%25A7%2587/&ved=2ahUKEwiGoriMmMWIAxVWxDgGHeDgEDsQFnoECCsQAQ&usg=AOvVaw1TQBJSuRJq4t2IyZfJ1Mrd">Buy</a>
            </div>
        </div>
        <div class="view-more-container">
        <button class="ab"><a href="#"> View More </a></button>
        </div>
    </div>
</section>

<section id="reviews">
    <div class="container">
        <div class="about-review">
            <h1> Visitor's <span> Reviews </span></h1>
        </div>
        <div class="fram">
            <div class="review">
                <h1>Smith.</h1>
                <p>
                    this website is great.<br>It's helps me to gather knowledge about herbs.<br> Also I can parchase lemongrass from this website.
                </p>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
            </div>
            <div class="review">
                <h1>Rosein.</h1>
                <p>
                    this website is great.<br>It's helps me to gather knowledge about herbs.<br> Also I can parchase lemongrass from this website.
                </p>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
            </div>
            <div class="review">
                <h1>Sofia.</h1>
                <p>
                    this website is great.<br>It's helps me to gather knowledge about herbs.<br> Also I can parchase lemongrass from this website.
                </p>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
            </div>
        </div>
    </div>
</section>

<footer>
  <div class="footerContainer">
    <div class="footer-content">
      <a href=""><i class="fa-brands fa-facebook"></i></a>
      <a href=""><i class="fa-brands fa-instagram"></i></a>
      <a href=""><i class="fa-brands fa-twitter"></i></a>
      <a href=""><i class="fa-brands fa-youtube"></i></a>
    </div>
    <div class="footerNav">
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">News</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact Us</a></li>
        <li><a href="#">Our Team</a></li>
      </ul>
    </div>
    <div class="footerBottom">
      <p>copyright &copy;2024; Design by <span class="designer"> Jannatul And Abid</span></p>
    </div>
  </div>
</footer>
</body>
</html>
