# 💍 Bridal Shop Event Management System

> A PHP-based web application for managing bridal shop bookings, packages, and client interactions.

---

## 📁 Project Structure

```
bridal_shops/
│
├── 🔧 CONFIGURATION
│   └── config.php                    → DB connection (PDO + MySQLi → 'bridal_event_system')
│
├── 🔐 AUTHENTICATION
│   ├── login.php                     → Login with lockout (3 attempts / 20s cooldown)
│   ├── register.php                  → New client registration
│   ├── logout.php                    → Session destroy & redirect
│   ├── forgot_password.php           → Password recovery via security question
│   └── verify_answer.php             → Verifies security answer for password reset
│
├── 🏠 DASHBOARDS
│   ├── dashboard_client.php          → Client homepage (guest-accessible, view-only)
│   └── dashboard_admin.php           → Admin control panel
│
├── 📦 PACKAGES & SERVICES
│   ├── packages.php                  → Packages listing page
│   ├── view_package.php              → Package detail view (public/guest access)
│   ├── fetch_packages.php            → AJAX: fetch package data (public)
│   ├── services.php                  → Services overview
│   └── other_services.php            → Additional services
│
├── 📅 BOOKINGS
│   ├── book_now.php                  → Start a booking
│   ├── submit_booking.php            → Process booking submission (login required)
│   ├── edit_booking.php              → Edit an existing booking
│   ├── update_booking.php            → Save booking updates
│   ├── delete_booking.php            → Delete a booking
│   ├── view_bookings.php             → Admin: view all bookings
│   ├── view_booking_client.php       → Client: view own bookings
│   ├── booking_history.php           → Client booking history (login required)
│   ├── get_booking_details.php       → AJAX: fetch booking details
│   ├── check_date_availability.php   → AJAX: check if date is available (public)
│   └── confirmations.php             → Booking confirmation page
│
├── 👤 USER MANAGEMENT
│   ├── profile.php                   → View own profile
│   ├── edit_profile.php              → Edit own profile (login required)
│   ├── admin_edit_profile.php        → Admin edits own profile
│   ├── admin_edit.php                → Admin edits any user
│   ├── edit.php                      → Generic edit helper
│   ├── manage_user.php               → Admin: manage all users
│   └── view_credentials.php          → View user credentials
│
├── 💬 MESSAGING & NOTIFICATIONS
│   ├── messages.php                  → Messaging interface
│   ├── chat_api.php                  → Chat API backend
│   ├── chat_ready.html               → Static chat test page
│   ├── notifications.php             → Notifications (login required)
│   ├── reservation.php               → Single reservation view
│   └── reservations.php              → Reservations management
│
├── 📊 ADMIN TOOLS
│   ├── manage_events.php             → Manage events
│   ├── reports.php                   → Reports & analytics
│   └── fetch_stats.php               → AJAX: fetch dashboard statistics
│
├── ℹ️ PUBLIC PAGES
│   ├── about.php                     → About the bridal shop
│   └── contact.php                   → Contact page
│
├── 🛠️ DEBUG / TEST FILES
│   ├── debug_user.php
│   ├── quick_fix_user.php
│   ├── test_chat.php
│   ├── test_chat_tables.php
│   ├── clear_test_chats.php
│   └── test_registration.php
│
├── 🗄️ DATABASE
│   └── db/bridal_event_system.sql    → Full database dump
│
├── 🖼️ ASSETS
│   ├── images/                       → Wedding/bridal images & logo
│   └── uploads/payment_references/   → Uploaded payment proof images
│
├── 📚 VENDOR (Composer)
│   └── vendor/phpmailer/             → PHPMailer library for email sending
│
└── 📝 DOCUMENTATION
    ├── GUEST_ACCESS_GUIDE.md         → Guest access implementation guide
    ├── QUICK_REFERENCE.txt           → SQL queries & troubleshooting
    └── PROJECT_STRUCTURE.md          → This file
```

---

## 🔗 Quick Access URLs

| Page                   | URL                                                  |
| ---------------------- | ---------------------------------------------------- |
| 🌐 **Guest Homepage**  | `http://localhost/bridal_shops/dashboard_client.php` |
| 🔑 **Login**           | `http://localhost/bridal_shops/login.php`            |
| 📝 **Register**        | `http://localhost/bridal_shops/register.php`         |
| 🔒 **Forgot Password** | `http://localhost/bridal_shops/forgot_password.php`  |
| 👑 **Admin Dashboard** | `http://localhost/bridal_shops/dashboard_admin.php`  |
| 📦 **Packages**        | `http://localhost/bridal_shops/packages.php`         |
| 🗄️ **phpMyAdmin**      | `http://localhost/phpmyadmin`                        |

---

## 🔒 Access Level Matrix

| Feature                  | 👤 Guest | 🧑 Client | 👑 Admin |
| ------------------------ | :------: | :-------: | :------: |
| View homepage & packages |    ✅    |    ✅     |    ✅    |
| View package details     |    ✅    |    ✅     |    ✅    |
| Check date availability  |    ✅    |    ✅     |    ✅    |
| About & Contact pages    |    ✅    |    ✅     |    ✅    |
| Submit bookings          |    ❌    |    ✅     |    ✅    |
| Booking history          |    ❌    |    ✅     |    ✅    |
| Notifications            |    ❌    |    ✅     |    ✅    |
| Edit profile             |    ❌    |    ✅     |    ✅    |
| Manage all users         |    ❌    |    ❌     |    ✅    |
| View all bookings        |    ❌    |    ❌     |    ✅    |
| Manage events            |    ❌    |    ❌     |    ✅    |
| Reports & analytics      |    ❌    |    ❌     |    ✅    |

---

## 🗄️ Database Schema

**Database:** `bridal_event_system`

### `users` table

| Column                 | Type         | Notes                 |
| ---------------------- | ------------ | --------------------- |
| `id`                   | INT (PK)     | Auto increment        |
| `firstname`            | VARCHAR(100) |                       |
| `middlename`           | VARCHAR(100) | Nullable              |
| `lastname`             | VARCHAR(100) |                       |
| `phone_number`         | VARCHAR(11)  | Starts with 09        |
| `username`             | VARCHAR(100) | UNIQUE                |
| `password`             | VARCHAR(255) | Hashed (bcrypt)       |
| `role`                 | VARCHAR(50)  | `client` or `admin`   |
| `email`                | VARCHAR(100) | UNIQUE, Gmail only    |
| `security_question`    | TEXT         | For password recovery |
| `security_answer_hash` | VARCHAR(255) | Hashed answer         |
| `status`               | VARCHAR(50)  | Default: `active`     |

### `bookings` table

| Column           | Type         | Notes                        |
| ---------------- | ------------ | ---------------------------- |
| `id`             | INT (PK)     | Auto increment               |
| `firstname`      | VARCHAR(100) |                              |
| `middlename`     | VARCHAR(100) | Nullable                     |
| `lastname`       | VARCHAR(100) |                              |
| `email`          | VARCHAR(100) |                              |
| `phone_number`   | VARCHAR(11)  |                              |
| `service_type`   | VARCHAR(100) |                              |
| `event_name`     | VARCHAR(255) |                              |
| `event_datetime` | DATETIME     | Indexed                      |
| `location`       | TEXT         |                              |
| `status`         | VARCHAR(50)  | Default: `Pending` (Indexed) |
| `created_at`     | TIMESTAMP    | Auto-set on insert           |

---

## ✅ Implemented Features

- 🔐 **Registration** — Duplicate email/name prevention, phone validation (11-digit, starts with 09), Gmail-only, password strength check, security question
- 🔒 **Login Security** — 3-attempt lockout with 20-second cooldown
- 🔑 **Password Recovery** — Via security question & answer
- 📋 **Auto-populated Booking Form** — Personal info pre-filled from session (readonly)
- 👁️ **Guest Access** — Browse homepage and packages without logging in
- 📦 **Package Viewer** — Publicly accessible package details
- 📅 **Date Availability Check** — Prevent double-booking
- 💬 **Chat / Messaging** — Real-time messaging interface
- 🔔 **Notifications** — For logged-in clients
- 📊 **Admin Reports** — Booking analytics and statistics
- 💳 **Payment References** — Upload and store payment proof images

---

## 🛠️ Tech Stack

| Layer         | Technology               |
| ------------- | ------------------------ |
| **Backend**   | PHP 8+                   |
| **Database**  | MySQL (via XAMPP)        |
| **DB Access** | PDO + MySQLi             |
| **Email**     | PHPMailer (via Composer) |
| **Frontend**  | HTML, CSS, JavaScript    |
| **Server**    | Apache (XAMPP)           |

---

## 🔧 Setup & Installation

1. **Clone / copy** the project into `C:\xampp\htdocs\bridal_shops\`
2. **Start** Apache & MySQL in XAMPP Control Panel
3. **Import the database** — Open `http://localhost/phpmyadmin`, create database `bridal_event_system`, then import `db/bridal_event_system.sql`
4. **Configure DB** in `config.php` (default: `root` / no password)
5. **Visit** `http://localhost/bridal_shops/login.php`

---

_Last updated: April 8, 2026_
