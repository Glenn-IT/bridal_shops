<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Mae's Bridal Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fdfdfd;
            color: #333;
            line-height: 1.6;
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

        header p {
            font-size: 18px;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .about-section {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
        }

        .about-image {
            flex: 1 1 300px;
        }

        .about-image img {
            width: 100%;
            border-radius: 10px;
            object-fit: cover;
        }

        .about-text {
            flex: 2 1 400px;
        }

        .about-text h2 {
            font-size: 26px;
            margin-bottom: 15px;
            color: #4b004e;
        }

        .features {
            background-color: #f6f6f6;
            padding: 30px;
            border-radius: 10px;
        }

        .features h3 {
            margin-top: 0;
            color: #4b004e;
        }

        .features ul {
            list-style: none;
            padding-left: 0;
        }

        .features ul li {
            margin-bottom: 10px;
        }

        .features ul li::before {
            content: "✔";
            color: #4b004e;
            margin-right: 10px;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #e3d1e0;
            color: #4b004e;
        }

        @media (max-width: 768px) {
            .about-section {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Mae’s Bridal Shop</h1>
    <p>Event Management Services and Reservation System <br> Sampaguita, Solana</p>
</header>

<div class="container">

    <div class="about-section">
        <div class="about-image">
            <img src="images/logo.webp" alt="Mae’s Bridal Shop">
        </div>
        <div class="about-text">
            <h2>About the System</h2>
            <p>
                The <strong>Event Management Services and Reservation System</strong> of Mae’s Bridal Shop is a
                digital platform designed to streamline the process of managing bridal events, bookings, and service
                reservations. Located in Sampaguita, Solana, the system allows customers to view services, make
                appointments, and reserve packages easily through a user-friendly interface.
            </p>
        </div>
    </div>

    <div class="features">
        <h3>Key Features of the System:</h3>
        <ul>
            <li>Online booking for bridal services and event packages</li>
            <li>Real-time reservation management for clients and admins</li>
            <li>Service catalog with descriptions and pricing</li>
            <li>Profile management for users</li>
            <li>Booking status tracking and notifications</li>
            <li>Admin panel for managing clients, appointments, and services</li>
        </ul>
    </div>

</div>

<footer>
    &copy; <?php echo date("Y"); ?> Mae’s Bridal Shop | Sampaguita, Solana
</footer>

</body>
</html>
