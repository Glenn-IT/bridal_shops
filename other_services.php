<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Other Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        .title {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 40px;
            padding: 20px;
            background-color: #fff;
            border: 2px solid #333;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .service-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }

        .service-box {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            width: 320px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .service-box:hover {
            transform: translateY(-5px);
        }

        .service-box img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .service-content {
            padding: 15px 20px;
        }

        .service-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .service-desc {
            font-size: 15px;
            color: #555;
            margin-bottom: 15px;
        }

        .book-btn {
            display: inline-block;
            padding: 10px 25px;
            background-color: #000;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .book-btn:hover {
            background-color: #444;
        }

        a.service-link {
            color: #007BFF;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .service-box {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<div class="title">OTHER SERVICES</div>

<div class="service-container">

    <div class="service-box">
        <img src="images/gown.jpg" alt="Gown Rentals">
        <div class="service-content">
            <div class="service-title">Gowns Rentals</div>
            <div class="service-desc">Bridal gowns, bridesmaid</div>
            <a href="book_now.php?service=gown_rental" class="book-btn">BOOK NOW</a>
        </div>
    </div>

    <div class="service-box">
        <img src="images/makeup.webp" alt="Makeup and Hair Styling">
        <div class="service-content">
            <div class="service-title">Makeup and Hair Styling</div>
            <div class="service-desc">On-the-day <a href="#" class="service-link">service</a>, <a href="#" class="service-link">trial</a></div>
            <a href="book_now.php?service=makeup" class="book-btn">BOOK NOW</a>
        </div>
    </div>

    <div class="service-box">
        <img src="images/event.webp" alt="Event Styling and Decorations">
        <div class="service-content">
            <div class="service-title">Decorations</div>
            <div class="service-desc">Floral arrangements, theme</div>
            <a href="book_now.php?service=event_styling" class="book-btn">BOOK NOW</a>
        </div>
    </div>

</div>

</body>
</html>
