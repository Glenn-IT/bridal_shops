# Guest Access Implementation Guide

## Overview

The dashboard has been updated to allow **guest access** - visitors can view the homepage and packages without logging in, but must log in to book or access protected features.

## What Changed

### ✅ Files Modified for Guest Access:

#### 1. **dashboard_client.php**

- ✅ Now allows guests to view the page
- ✅ Shows "Guest" with Login/Register buttons in navbar for non-logged users
- ✅ Hides "Notifications" and "Booking History" tabs for guests
- ✅ Shows "Login to Book" button instead of "Book Now" for guests
- ✅ Booking form section shows login prompt for guests
- ✅ Logged-in users see the full booking form

#### 2. **view_package.php**

- ✅ Now accessible to guests
- ✅ Guests can view all package details
- ✅ No authentication required to browse packages

#### 3. **fetch_packages.php**

- ✅ Removed authentication requirement
- ✅ Guests can fetch package data (needed for viewing)

#### 4. **check_date_availability.php**

- ✅ Removed authentication requirement
- ✅ Guests can check date availability

#### 5. **submit_booking.php**

- ✅ Requires authentication (client role)
- ✅ Guests cannot submit bookings directly
- ✅ Will redirect to login if accessed without auth

### 🔒 Protected Features (Require Login):

1. **Booking Submission** - `submit_booking.php`
2. **Booking History** - `booking_history.php`
3. **Notifications** - `notifications.php`
4. **Edit Profile** - `edit_profile.php`
5. **Edit/Update/Delete Bookings** - Various booking management files

### 🌐 Public Access (No Login Required):

1. **Homepage** - `dashboard_client.php` (view only)
2. **About Page** - `about.php`
3. **Contact Page** - `contact.php`
4. **View Packages** - `view_package.php`
5. **Login/Register Pages** - `login.php`, `register.php`

## User Experience Flow

### For Guests (Not Logged In):

1. ✅ Can visit `http://localhost/bridal_shops/dashboard_client.php`
2. ✅ Can browse all services and packages
3. ✅ Can view package details
4. ✅ See "Login to Book" buttons instead of "Book Now"
5. ✅ Clicking "Book Now" section shows login prompt
6. ❌ Cannot access Notifications or Booking History tabs
7. ❌ Cannot submit bookings (will be redirected to login)

### For Logged-In Users:

1. ✅ Full access to all features
2. ✅ Can book services
3. ✅ Can view booking history
4. ✅ Can check notifications
5. ✅ Can edit profile
6. ✅ Name displayed in navbar with Logout button

## Testing Instructions

### Test as Guest:

1. **Logout** if currently logged in
2. Visit: `http://localhost/bridal_shops/dashboard_client.php`
3. ✅ Should see the homepage with "Guest" and Login/Register buttons
4. ✅ Browse services - should work fine
5. ✅ Click "View Package" - should open package details
6. ✅ Click "Login to Book" - should redirect to login page
7. ✅ Scroll to booking section - should see login prompt
8. ❌ Try to access `booking_history.php` - should redirect to login
9. ❌ Try to access `notifications.php` - should redirect to login

### Test as Logged-In User:

1. **Login** with valid credentials
2. Visit: `http://localhost/bridal_shops/dashboard_client.php`
3. ✅ Should see your name in navbar
4. ✅ Should see Notifications and Booking History tabs
5. ✅ Click "Book Now" - should show the booking form
6. ✅ Can submit bookings successfully
7. ✅ Can access all protected features

## Security Notes

- ✅ All booking submissions still require authentication
- ✅ Protected pages still check for valid sessions
- ✅ Guests can only **view** public content
- ✅ All form submissions are validated server-side
- ✅ No sensitive user data exposed to guests

## Customization

If you want to further customize what guests can see:

1. **Add more guest-viewable content**: Remove authentication checks
2. **Restrict more content**: Add authentication checks back
3. **Change navbar for guests**: Edit the navbar section in `dashboard_client.php`
4. **Customize login prompt**: Edit the booking section guest view

---

**Last Updated:** <?= date('Y-m-d H:i:s') ?>
