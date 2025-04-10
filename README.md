# Éclisse E-commerce - Deployment Guide

## Project Overview
- PHP/MySQL e-commerce website for a modest fashion brand
- Customer-facing website with product catalog
- Admin dashboard for managing products, orders, and blog posts

## Server Requirements
- PHP 7.4+
- MySQL 5.7+
- mod_rewrite enabled

## Deployment Steps

### 1. Database Setup
- Create a MySQL database
- Database structure will be auto-created on first run

### 2. Environment Configuration
- `.env`
- Update database credentials in `.env`
- Set correct BASE_URL in `.env`

### 3. File Upload
- Upload all files to web server
- Ensure proper permissions:
  - `uploads/` directory: 755 (writable)
  - `.env` file: 644

### 4. Generate Assets
- Run `php generate-images.php` to create placeholder images
- Run `php setup-blog-posts.php` to create sample blog content

### 5. Admin Access
- Access admin panel at `/admin/login.php`
- Default credentials: admin/admin123
- **Important**: Change admin password immediately after first login

## Directory Structure
- `/config` - Configuration files
- `/models` - Database models
- `/admin` - Admin dashboard
- `/uploads` - Product and blog images
- `/assets` - CSS, JS, and static files

## Post-Deployment Checks
- Verify homepage loads correctly
- Test admin login
- Confirm product images display properly
- Test order submission form

## Support
For deployment issues, contact: [your-email@example.com]
```
