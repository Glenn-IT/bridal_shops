<?php
session_start();

// If already logged in, redirect to their dashboard
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: dashboard_admin.php");
    } else {
        header("Location: dashboard_client.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mae's Bridal Shop — Event Management & Reservation System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #fff;
            color: #333;
            padding-top: 70px;
        }

        /* ── NAVBAR ─────────────────────────────────── */
        .navbar-custom {
            background: linear-gradient(135deg, #d6336c, #e76f51);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            letter-spacing: 1px;
        }
        .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 6px;
            transition: color 0.2s;
        }
        .nav-link:hover { color: #ffd700 !important; }
        .btn-nav-login {
            background: transparent;
            color: white !important;
            border: 2px solid white;
            border-radius: 20px;
            padding: 5px 18px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-nav-login:hover { background: white; color: #d6336c !important; }
        .btn-nav-register {
            background: white;
            color: #d6336c !important;
            border: 2px solid white;
            border-radius: 20px;
            padding: 5px 18px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-nav-register:hover { background: #ffd700; border-color: #ffd700; }

        /* ── HERO ────────────────────────────────────── */
        .hero-section {
            min-height: calc(100vh - 70px);
            background: url('images/bg.jpeg') center center / cover no-repeat;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 3rem 1rem;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.58);
        }
        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
            max-width: 750px;
        }
        .hero-content .badge-top {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.4);
            color: #ffd700;
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 6px 18px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .hero-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .hero-content h1 span { color: #ffd700; }
        .hero-content p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 35px;
            line-height: 1.8;
        }
        .hero-buttons .btn {
            padding: 12px 32px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 30px;
            margin: 6px;
            transition: all 0.25s;
        }
        .btn-hero-primary {
            background: linear-gradient(135deg, #d6336c, #e76f51);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(214,51,108,0.4);
        }
        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(214,51,108,0.5);
            color: white;
        }
        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        .btn-hero-outline:hover {
            background: white;
            color: #d6336c;
            transform: translateY(-2px);
        }
        .scroll-down {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            color: white;
            opacity: 0.7;
            font-size: 1.6rem;
            animation: bounce 1.8s infinite;
            cursor: pointer;
        }
        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50%       { transform: translateX(-50%) translateY(10px); }
        }

        /* ── STATS STRIP ─────────────────────────────── */
        .stats-strip {
            background: linear-gradient(135deg, #d6336c, #e76f51);
            color: white;
            padding: 30px 0;
        }
        .stat-item { text-align: center; padding: 10px 20px; }
        .stat-item .number {
            font-size: 2.2rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
        }
        .stat-item .label { font-size: 0.9rem; opacity: 0.85; }

        /* ── SECTION SHARED ─────────────────────────── */
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #d6336c;
            font-weight: 700;
        }
        .section-subtitle {
            color: #777;
            font-size: 1rem;
            max-width: 560px;
            margin: 0 auto 40px;
        }
        .divider-pink {
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #d6336c, #e76f51);
            margin: 12px auto 16px;
            border-radius: 2px;
        }

        /* ── SERVICES ────────────────────────────────── */
        .services-section { padding: 80px 0; background: #fafafa; }
        .service-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 30px rgba(214,51,108,0.15);
        }
        .service-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .service-card .card-body { padding: 22px; }
        .service-card .card-title {
            font-family: 'Playfair Display', serif;
            color: #d6336c;
            font-size: 1.2rem;
            font-weight: 700;
        }
        .service-card .card-text { color: #666; font-size: 0.93rem; }
        .service-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #d6336c, #e76f51);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            margin-bottom: 14px;
        }

        /* ── GALLERY ─────────────────────────────────── */
        .gallery-section { padding: 80px 0; background: white; }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: 220px 220px;
            gap: 12px;
        }
        .gallery-item {
            overflow: hidden;
            border-radius: 12px;
            cursor: pointer;
            position: relative;
        }
        .gallery-item:first-child {
            grid-column: span 2;
            grid-row: span 2;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }
        .gallery-item:hover img { transform: scale(1.07); }
        .gallery-item .overlay {
            position: absolute;
            inset: 0;
            background: rgba(214,51,108,0);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }
        .gallery-item:hover .overlay { background: rgba(214,51,108,0.3); }
        @media (max-width: 768px) {
            .gallery-grid { grid-template-columns: repeat(2, 1fr); grid-template-rows: auto; }
            .gallery-item:first-child { grid-column: span 2; grid-row: span 1; }
        }

        /* ── HOW IT WORKS ────────────────────────────── */
        .how-section { padding: 80px 0; background: #fafafa; }
        .step-card {
            text-align: center;
            padding: 30px 20px;
        }
        .step-number {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #d6336c, #e76f51);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            margin: 0 auto 18px;
            box-shadow: 0 4px 14px rgba(214,51,108,0.35);
        }
        .step-card h5 {
            font-family: 'Playfair Display', serif;
            color: #333;
            font-size: 1.1rem;
        }
        .step-card p { color: #777; font-size: 0.92rem; }

        /* ── CTA ─────────────────────────────────────── */
        .cta-section {
            padding: 90px 20px;
            background: linear-gradient(135deg, #d6336c, #e76f51);
            color: white;
            text-align: center;
        }
        .cta-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            margin-bottom: 14px;
        }
        .cta-section p { font-size: 1.05rem; opacity: 0.9; margin-bottom: 35px; }
        .btn-cta-white {
            background: white;
            color: #d6336c;
            border: none;
            border-radius: 30px;
            padding: 13px 36px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.25s;
            margin: 6px;
        }
        .btn-cta-white:hover {
            background: #ffd700;
            color: #d6336c;
            transform: translateY(-2px);
        }
        .btn-cta-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
            border-radius: 30px;
            padding: 13px 36px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.25s;
            margin: 6px;
        }
        .btn-cta-outline:hover { background: white; color: #d6336c; }

        /* ── FOOTER ──────────────────────────────────── */
        footer {
            background: #1e1e2f;
            color: #ccc;
            padding: 50px 0 20px;
        }
        footer .brand {
            font-family: 'Playfair Display', serif;
            color: #ffd700;
            font-size: 1.5rem;
            font-weight: 700;
        }
        footer .footer-links a {
            color: #aaa;
            text-decoration: none;
            display: block;
            margin-bottom: 8px;
            font-size: 0.93rem;
            transition: color 0.2s;
        }
        footer .footer-links a:hover { color: #ffd700; }
        footer .footer-links h6 { color: white; font-weight: 600; margin-bottom: 14px; }
        footer .social-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            margin-right: 8px;
            transition: background 0.2s;
            text-decoration: none;
        }
        footer .social-icon:hover { background: #d6336c; color: white; }
        footer .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.08);
            margin-top: 40px;
            padding-top: 20px;
            text-align: center;
            font-size: 0.88rem;
            color: #777;
        }
    </style>
</head>
<body>

<!-- ── NAVBAR ─────────────────────────────────────────────── -->
<nav class="navbar navbar-expand-lg navbar-custom fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-ring me-2"></i>Mae's Bridal Shop
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                style="color:white; filter: invert(1);">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <a href="login.php" class="btn btn-nav-login">Login</a>
                <a href="register.php" class="btn btn-nav-register">Register</a>
            </div>
        </div>
    </div>
</nav>

<!-- ── HERO ───────────────────────────────────────────────── -->
<section class="hero-section" id="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="badge-top"><i class="fas fa-star me-1"></i> Sampaguita, Solana, Cagayan</div>
        <h1>Make Your <span>Special Day</span><br>Truly Unforgettable</h1>
        <p>
            Mae's Bridal Shop offers complete event management and reservation services —<br>
            from elegant bridal gowns to full wedding coordination.
        </p>
        <div class="hero-buttons">
            <a href="register.php" class="btn btn-hero-primary">
                <i class="fas fa-calendar-check me-2"></i>Book Now
            </a>
            <a href="#services" class="btn btn-hero-outline">
                <i class="fas fa-th-large me-2"></i>View Services
            </a>
        </div>
    </div>
    <a href="#stats" class="scroll-down"><i class="fas fa-chevron-down"></i></a>
</section>

<!-- ── STATS STRIP ────────────────────────────────────────── -->
<section class="stats-strip" id="stats">
    <div class="container">
        <div class="row g-0 justify-content-center">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="number">500+</div>
                    <div class="label"><i class="fas fa-heart me-1"></i> Happy Clients</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="number">8+</div>
                    <div class="label"><i class="fas fa-concierge-bell me-1"></i> Services Offered</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="number">10+</div>
                    <div class="label"><i class="fas fa-award me-1"></i> Years Experience</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="number">100%</div>
                    <div class="label"><i class="fas fa-thumbs-up me-1"></i> Satisfaction</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── SERVICES ───────────────────────────────────────────── -->
<section class="services-section" id="services">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Services</h2>
            <div class="divider-pink"></div>
            <p class="section-subtitle">We offer a wide range of event services to make every occasion truly special and memorable.</p>
        </div>
        <div class="row g-4">
            <div class="col-sm-6 col-lg-4">
                <div class="service-card">
                    <img src="images/wedding1.webp" alt="Wedding">
                    <div class="card-body">
                        <div class="service-icon"><i class="fas fa-ring"></i></div>
                        <h5 class="card-title">Wedding Ceremony</h5>
                        <p class="card-text">Complete wedding coordination — from venue setup to full bridal package with gown, makeup, and entourage styling.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="service-card">
                    <img src="images/gown.jpg" alt="Gown">
                    <div class="card-body">
                        <div class="service-icon"><i class="fas fa-user-tie"></i></div>
                        <h5 class="card-title">Bridal Gown & Styling</h5>
                        <p class="card-text">Exquisite bridal gown rentals and custom styling services to make every bride look her absolute best.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="service-card">
                    <img src="images/makeup.webp" alt="Makeup">
                    <div class="card-body">
                        <div class="service-icon"><i class="fas fa-paint-brush"></i></div>
                        <h5 class="card-title">Makeup & Hair</h5>
                        <p class="card-text">Professional makeup artists and hair stylists to ensure a flawless bridal look for your special day.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="service-card">
                    <img src="images/cor.jpg" alt="Debut">
                    <div class="card-body">
                        <div class="service-icon"><i class="fas fa-birthday-cake"></i></div>
                        <h5 class="card-title">Debut & Cotillion</h5>
                        <p class="card-text">Elegant debut packages including cotillion coordination, gown rentals, and full event management.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="service-card">
                    <img src="images/anv.webp" alt="Anniversary">
                    <div class="card-body">
                        <div class="service-icon"><i class="fas fa-heart"></i></div>
                        <h5 class="card-title">Anniversaries</h5>
                        <p class="card-text">Celebrate your milestones with beautifully arranged anniversary events tailored to your style.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="service-card">
                    <img src="images/event.webp" alt="Other Events">
                    <div class="card-body">
                        <div class="service-icon"><i class="fas fa-calendar-alt"></i></div>
                        <h5 class="card-title">Other Events</h5>
                        <p class="card-text">From baptisms and reunions to corporate gatherings — we manage all types of special occasions.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="register.php" class="btn btn-hero-primary px-5 py-3">
                <i class="fas fa-calendar-check me-2"></i>Book a Service Now
            </a>
        </div>
    </div>
</section>

<!-- ── GALLERY ─────────────────────────────────────────────── -->
<section class="gallery-section" id="gallery">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Gallery</h2>
            <div class="divider-pink"></div>
            <p class="section-subtitle">A glimpse of the beautiful events and moments we have helped create.</p>
        </div>
        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="images/wedding1.webp" alt="Wedding 1">
                <div class="overlay"></div>
            </div>
            <div class="gallery-item">
                <img src="images/wedding2.webp" alt="Wedding 2">
                <div class="overlay"></div>
            </div>
            <div class="gallery-item">
                <img src="images/wedding3.webp" alt="Wedding 3">
                <div class="overlay"></div>
            </div>
            <div class="gallery-item">
                <img src="images/wedding4.webp" alt="Wedding 4">
                <div class="overlay"></div>
            </div>
            <div class="gallery-item">
                <img src="images/wedding5.webp" alt="Wedding 5">
                <div class="overlay"></div>
            </div>
            <div class="gallery-item">
                <img src="images/wedding6.webp" alt="Wedding 6">
                <div class="overlay"></div>
            </div>
            <div class="gallery-item">
                <img src="images/wedding7.webp" alt="Wedding 7">
                <div class="overlay"></div>
            </div>
        </div>
    </div>
</section>

<!-- ── HOW IT WORKS ───────────────────────────────────────── -->
<section class="how-section" id="how-it-works">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">How It Works</h2>
            <div class="divider-pink"></div>
            <p class="section-subtitle">Booking your dream event with Mae's Bridal Shop is quick and easy.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-sm-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5>Create an Account</h5>
                    <p>Register for free and set up your profile to get started.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5>Choose a Service</h5>
                    <p>Browse our services and packages and select the one that suits your event.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5>Submit a Booking</h5>
                    <p>Fill in your event details, pick a date, and submit your reservation.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h5>Get Confirmed</h5>
                    <p>Receive email confirmation once the admin approves your booking.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── CTA ────────────────────────────────────────────────── -->
<section class="cta-section">
    <div class="container">
        <h2><i class="fas fa-ring me-2"></i>Ready to Book Your Dream Event?</h2>
        <p>Join hundreds of happy clients who trusted Mae's Bridal Shop for their special day.</p>
        <div>
            <a href="register.php" class="btn btn-cta-white">
                <i class="fas fa-user-plus me-2"></i>Register Now — It's Free
            </a>
            <a href="login.php" class="btn btn-cta-outline">
                <i class="fas fa-sign-in-alt me-2"></i>Already have an account?
            </a>
        </div>
    </div>
</section>

<!-- ── FOOTER ──────────────────────────────────────────────── -->
<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="brand mb-3"><i class="fas fa-ring me-2"></i>Mae's Bridal Shop</div>
                <p style="font-size:0.92rem; line-height:1.8;">
                    Event Management Services and Reservation System.<br>
                    Making your special moments unforgettable since 2014.
                </p>
                <div class="mt-3">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="contact.php" class="social-icon"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
            <div class="col-sm-6 col-lg-2 footer-links">
                <h6>Quick Links</h6>
                <a href="#hero">Home</a>
                <a href="#services">Services</a>
                <a href="#gallery">Gallery</a>
                <a href="about.php">About Us</a>
                <a href="contact.php">Contact</a>
            </div>
            <div class="col-sm-6 col-lg-3 footer-links">
                <h6>Account</h6>
                <a href="login.php"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                <a href="register.php"><i class="fas fa-user-plus me-1"></i> Register</a>
                <a href="forgot_password.php"><i class="fas fa-key me-1"></i> Forgot Password</a>
            </div>
            <div class="col-lg-3 footer-links">
                <h6>Contact Info</h6>
                <p style="font-size:0.9rem; line-height:2;">
                    <i class="fas fa-map-marker-alt me-2" style="color:#d6336c;"></i>Sampaguita, Solana, Cagayan<br>
                    <i class="fas fa-envelope me-2" style="color:#d6336c;"></i>delacruzmelody847@gmail.com<br>
                    <i class="fas fa-clock me-2" style="color:#d6336c;"></i>Mon – Sat: 8:00 AM – 6:00 PM
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="mb-0">
                <i class="fas fa-heart" style="color:#d6336c;"></i>
                &copy; <?= date('Y') ?> Mae's Bridal Shop | Sampaguita, Solana, Cagayan — All Rights Reserved
            </p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
