<?php
$title = "About Us"; 
include 'header.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;

        }

        .about-section {
            text-align: center;
            padding: 60px 20px;
            color: white;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('img/background1.jpg');
            background-size: cover;
            background-position: center;
        }

        .about-section h1 {
            font-size: 3rem;
        }

        .about-section p {
            font-size: 1.2rem;
            margin-top: 10px;
        }

        .story {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            background-color: #f8f8f8;
        }

        .story img {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            margin-left: 40px;
        }

        .story-text {
            flex: 1;
            padding: 0 20px;
            max-width: 700px;
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .column {
            flex: 0 0 30%;
            margin: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
            transition: 0.3s;
        }

        .column:hover {
            transform: scale(1.05);
        }

        .card {
            padding: 8px;
            text-align: center;
        }

        .card img {
            width: 300px;
            height: 250px;
            object-fit: cover no-repeat;
        }

        .container {
            padding: 10px;
        }

        .container h2 {
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .button {
            border: none;
            outline: 0;
            display: inline-block;
            padding: 8px;
            color: white;
            background-color: #000;
            text-align: center;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .button:hover {
            background-color: #555;
        }

        .contact-container {
            max-width: 800px;
            background-color: #fff;
            padding: 30px;
            margin: 40px auto;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .contact-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
            color: #333;
        }

        input[type=text], input[type=email], textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type=text]:focus, input[type=email]:focus, textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        input[type=submit] {
            background-color: #007bff;
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type=submit]:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 12px rgba(0, 91, 187, 0.3);
        }

        .map-container {
            max-width: 1000px;
            margin: 40px auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .map iframe {
            width: 100%;
            height: 450px;
            border: 0;
            border-radius: 12px;
        }
    </style>
</head>
<body>

<div class="about-section">
    <h1>About Us</h1>
    <p>Welcome to EventGo, your gateway to campus events.</p>
</div>

<div class="story">
    <div class="story-text">
        <h2>Our Story</h2>
        <p>
            EventGo started with a simple idea: to create a centralized platform for campus students to stay connected with the events that matter most. 
            Whether it’s academic seminars, cultural festivals, sports competitions, or student clubs, we wanted to make event discovery easier and more enjoyable. 
            EventGo is our solution—a platform built by students, for students, to enrich the campus experience.
        </p>
    </div>
    <img src="img/background1.jpg" alt="Our Story">
</div>

<h2 style="text-align:center">Our Team</h2>
<div class="row">
  <!-- Team member 1 -->
  <div class="column">
    <div class="card">
      <img src="img/member1.jpeg" alt="Lim">
      <div class="container">
        <h2>Lim Jia De</h2>
        <p class="title">Lecturer</p>
        <p>Lim is passionate about guiding students and providing the support they need.</p>
        <p>Lim@example.com</p>
        <p><button class="button contactButton">Contact</button></p>
      </div>
    </div>
  </div>

  <!-- Team member 2 -->
  <div class="column">
    <div class="card">
      <img src="img/member2.jpg" alt="Ooh">
      <div class="container">
        <h2>INTI College</h2>
        <p class="title">Lecturer</p>
        <p>INTI enjoys fostering innovation and helping students succeed in tech.</p>
        <p>Inti@example.com</p>
        <p><a href="mailto:Inti@example.com" class="button contactButton">Contact</a></p>
      </div>
    </div>
  </div>

</div>

<div class="contact-container" id="contact">
    <h2>Contact Us</h2>
    <form action="process_contact.php" method="post">
        <input type="text" id="name" name="name" placeholder="Your name..." required>
        <input type="email" id="email" name="email" placeholder="Your email..." required>
        <input type="text" id="title" name="title" placeholder="Your title..." required>
        <textarea id="msg" name="msg" placeholder="Write something..." style="height:150px" required></textarea>
        <input type="submit" class="btn" value="Submit">
    </form>
</div>

<div class="map-container">
    <h2 style="text-align: center; margin-bottom: 20px;">Our Location</h2>
    <div class="map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15889.967954798654!2d100.2818707!3d5.3416038!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304ac048a161f277%3A0x881c46d428b3162c!2sINTI%20International%20College%20Penang!5e0!3m2!1sen!2smy!4v1688752670786!5m2!1sen!2smy"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

</body>
</html>