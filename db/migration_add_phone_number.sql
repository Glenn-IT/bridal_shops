-- Migration script to add phone_number column to users table
-- Run this script if you already have the users table created

-- Add phone_number column after lastname
ALTER TABLE `users` 
ADD COLUMN `phone_number` varchar(11) DEFAULT NULL 
AFTER `lastname`;

-- Update the role column to have a default value of 'client'
ALTER TABLE `users` 
MODIFY COLUMN `role` varchar(20) NOT NULL DEFAULT 'client';
