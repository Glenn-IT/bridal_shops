# Customer Registration System Setup

## Overview

This registration system allows new customers to sign up for Mae's Bridal Shop with the following features:

- Full name registration (First, Middle, Last name)
- Philippine phone number validation (11 digits starting with 09)
- Gmail validation
- Username and password authentication
- Security question for password recovery
- Automatic role assignment as 'client'

## Files Created/Modified

### New Files:

1. **register.php** - Customer registration form
2. **db/migration_add_phone_number.sql** - Database migration script

### Modified Files:

1. **login.php** - Added "Create an Account" link
2. **db/bridal_event_system.sql** - Updated users table structure

## Database Setup

### Option 1: Fresh Installation

If you're setting up the database from scratch, simply import the updated SQL file:

```sql
source c:\xampp\htdocs\bridal_shops\db\bridal_event_system.sql
```

### Option 2: Existing Database

If you already have the `users` table, run the migration script to add the phone_number column:

1. Open phpMyAdmin
2. Select your `bridal_event_system` database
3. Go to the SQL tab
4. Run the migration script:

```sql
ALTER TABLE `users`
ADD COLUMN `phone_number` varchar(11) DEFAULT NULL
AFTER `lastname`;

ALTER TABLE `users`
MODIFY COLUMN `role` varchar(20) NOT NULL DEFAULT 'client';
```

Or import the migration file: `db/migration_add_phone_number.sql`

## Updated Users Table Structure

```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) DEFAULT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `phone_number` varchar(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'client',
  `email` varchar(100) NOT NULL,
  `security_question` varchar(255) NOT NULL,
  `security_answer_hash` char(64) NOT NULL,
  `status` varchar(20) DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## Registration Form Fields

### Required Fields:

- **First Name** - Text input
- **Last Name** - Text input
- **Phone Number** - 11 digits, must start with 09 (Philippine format)
- **Gmail** - Must be a valid Gmail address
- **Username** - 4-20 characters, alphanumeric and underscores only
- **Password** - Minimum 6 characters
- **Confirm Password** - Must match password
- **Security Question** - Select from predefined questions
- **Security Answer** - For password recovery

### Optional Fields:

- **Middle Name** - Text input

## Validation Features

### Client-Side Validation (JavaScript):

- Phone number format check (11 digits starting with 09)
- Password match verification
- Password length validation
- Numbers-only input for phone number

### Server-Side Validation (PHP):

- All required fields check
- Phone number regex validation: `/^09\d{9}$/`
- Gmail domain validation
- Username format validation: `/^[a-zA-Z0-9_]{4,20}$/`
- Password length check (minimum 6 characters)
- Password confirmation match
- Duplicate username check
- Duplicate email check

## Security Features

1. **Password Hashing**: Uses PHP's `password_hash()` with PASSWORD_DEFAULT (bcrypt)
2. **Security Answer Hashing**: Uses SHA-256 for security answers
3. **SQL Injection Protection**: PDO prepared statements
4. **XSS Protection**: `htmlspecialchars()` for output
5. **Input Sanitization**: `trim()` for all inputs
6. **Role Assignment**: Automatically set to 'client' (cannot be changed during registration)

## Predefined Security Questions

1. What is your mother's maiden name?
2. What is your favorite color?
3. What is the name of your first pet?
4. What city were you born in?
5. What is your favorite food?

## How to Access

1. **Registration Page**: `http://localhost/bridal_shops/register.php`
2. **Login Page**: `http://localhost/bridal_shops/login.php`

From the login page, users can click "Create an Account" to access the registration form.

## User Flow

1. User visits login page
2. Clicks "Create an Account" link
3. Fills out registration form with all required information
4. System validates all inputs (client-side and server-side)
5. If validation passes:
   - Password is hashed using bcrypt
   - Security answer is hashed using SHA-256
   - User record is created with role='client'
   - Success message shown with link to login
6. User can now login with their credentials

## Error Handling

The system displays user-friendly error messages for:

- Empty required fields
- Invalid phone number format
- Invalid email format
- Non-Gmail addresses
- Invalid username format
- Short passwords
- Password mismatch
- Duplicate username
- Duplicate email
- Database errors

## Testing

### Test Registration:

1. Navigate to `http://localhost/bridal_shops/register.php`
2. Fill in the form with test data:

   - First Name: John
   - Middle Name: Paul (optional)
   - Last Name: Doe
   - Phone Number: 09123456789
   - Gmail: johndoe@gmail.com
   - Username: johndoe
   - Password: password123
   - Confirm Password: password123
   - Security Question: Select one
   - Security Answer: testanswer

3. Submit and verify success message
4. Try logging in with the new credentials

### Test Validations:

- Try invalid phone: 12345678901 (should fail)
- Try non-Gmail: test@yahoo.com (should fail)
- Try short username: abc (should fail)
- Try short password: 12345 (should fail)
- Try mismatched passwords (should fail)
- Try duplicate username (should fail)
- Try duplicate email (should fail)

## Notes

- All new registrations are automatically assigned the role 'client'
- The status is automatically set to 'active'
- Middle name is optional, all other fields are required
- Phone numbers are stored without formatting (just 11 digits)
- Passwords are never stored in plain text
- Security answers are hashed and cannot be retrieved, only verified

## Maintenance

To add more security questions, edit the `$security_questions` array in `register.php`:

```php
$security_questions = [
    "Your new question here",
    // ... existing questions
];
```
