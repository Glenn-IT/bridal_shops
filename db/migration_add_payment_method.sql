-- Migration: Add payment method and payment screenshot columns to bookings table
-- Date: 2025-10-28

ALTER TABLE bookings 
ADD COLUMN payment_method VARCHAR(50) DEFAULT 'Cash' AFTER location,
ADD COLUMN payment_screenshot VARCHAR(255) NULL AFTER payment_method;
