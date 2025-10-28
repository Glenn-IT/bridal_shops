<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Mae’s Bridal Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fdfdfd;
            color: #333;
        }

        header {
            background-color: #e3d1e0;
            padding: 30px;
            text-align: center;
            color: #4b004e;
        }

        header h1 {
            margin: 0;
            font-size: 32px;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .contact-info {
            margin-bottom: 30px;
        }

        .contact-info p {
            font-size: 16px;
            margin: 8px 0;
        }

        .map-container {
            margin-bottom: 40px;
        }

        .map-container iframe {
            width: 100%;
            height: 350px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .contact-form h2 {
            margin-bottom: 20px;
            color: #4b004e;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .form-group textarea {
            height: 120px;
            resize: vertical;
        }

        .submit-btn {
            background-color: #4b004e;
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #5f1a60;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #e3d1e0;
            color: #4b004e;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Contact Mae’s Bridal Shop</h1>
</header>

<div class="container">

    <div class="contact-info">
        <h2><i class="fas fa-map-marker-alt"></i> Visit Us</h2>
        <p><strong>Location:</strong> Sampaguita, Solana, Cagayan</p>
        <p><i class="fas fa-phone-alt"></i> <strong>Phone:</strong> 0912-345-6789</p>
        <p><i class="fas fa-envelope"></i> <strong>Email:</strong> maebridalevents@example.com</p>
    </div>

    <!-- Google Map Embed -->
    <div class="map-container">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3895.631821764137!2d121.6625!3d17.6517!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3385513a7d1f5edb%3A0x31d0c0a07b5d6263!2sSampaguita%2C%20Solana%2C%20Cagayan!5e0!3m2!1sen!2sph!4v1629559156164!5m2!1sen!2sph"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

    <div class="contact-form">
        <h2><i class="fas fa-paper-plane"></i> Send Us a Message</h2>

        <form action="contact.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required placeholder="Enter your full name">
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="message">Your Message:</label>
                <textarea id="message" name="message" required placeholder="Write your message here..."></textarea>
            </div>

            <button type="submit" class="submit-btn">Send Message</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $message = htmlspecialchars($_POST['message']);

            echo "<p style='margin-top: 20px; color: green; font-weight: bold;'>Thank you, $name! We’ve received your message.</p>";
        }
        ?>
    </div>
</div>

<footer>
    &copy; <?php echo date("Y"); ?> Mae’s Bridal Shop | Sampaguita, Solana
</footer>

</body>
</html>
