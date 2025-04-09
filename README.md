# Éclisse - Luxury Modest Fashion E-commerce

Éclisse is a PHP and MySQL-based e-commerce website for a luxury modest fashion brand. It features a customer-facing website and a secure admin dashboard.

## 🌸 Website Overview

Éclisse is designed for a luxury modest fashion brand that allows customers to browse products and place orders either through WhatsApp or by filling out a Cash-on-Delivery (COD) form.

### Features

#### 🛍️ Customer Website (Public Access)
- **Homepage**: Brand introduction with highlighted categories (Kimonos, Ensembles, Éditions limitées)
- **Product Listing**: Filterable by category
- **Product Details**: With WhatsApp ordering and COD form options
- **About Page**: Brand story and values
- **Blog**: Articles with list and detail views

#### 🔐 Admin Dashboard (Restricted Access)
- **Dashboard Overview**: Stats on products, orders, and blog posts
- **Product Management**: Add/Edit/Delete products with image uploads
- **Blog Management**: Add/Edit/Delete blog posts
- **Order Management**: View and process COD orders

## 🛠️ Technical Details

### Tech Stack
- PHP (Vanilla with lightweight MVC structure)
- MySQL Database
- Bootstrap 5 for responsive frontend
- Font Awesome icons
- DataTables for admin tables

### Database Structure
- Products table
- Blog posts table
- Orders table
- Admin users table

## 📋 Installation Instructions

### Server Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- mod_rewrite enabled (for .htaccess)

### Installation Steps
1. Clone the repository or upload all files to your web server
2. Create a MySQL database
3. Copy `.env.example` to `.env` and update the database configuration
4. Run `generate-images.php` to create placeholder images (or replace with your own)
5. Access your website domain to initialize the database
6. Access the admin dashboard at `/admin/login.php`
   - Default credentials: admin/admin123 (change immediately)

### Important Directories
- `/config` - Configuration files
- `/models` - Database models
- `/includes` - Header and footer templates
- `/admin` - Admin dashboard files
- `/uploads` - Product and blog images (created automatically)
- `/assets` - CSS, JS, and image files

## ⚙️ Configuration

### Environment Variables
See `.env` file for configurable options:
- Database connection details
- WhatsApp number for direct ordering
- Site URL and brand settings

### Logo and Branding
- Replace the logo at `assets/images/logo.png` with your own logo
- Replace the favicon at `assets/images/favicon.ico` with your own favicon
- Update color variables in `assets/css/style.css` to match your brand colors

### Security
- Password hashing for admin authentication
- Input sanitization to prevent SQL injection
- Protected admin area with session management
- .htaccess rules to protect sensitive directories

## 📝 Customization Guide

### Styling
- Update color variables in `assets/css/style.css` to match your brand colors
- Modify fonts by changing the Google Fonts import in the header

### Product Categories
1. Update the dropdown menu in `includes/header.php`
2. Add new options to the category select in `admin/product-form.php`

## 📱 WhatsApp Integration
The WhatsApp integration uses the official WhatsApp click-to-chat API with the format:
\`\`\`
https://wa.me/PHONE_NUMBER?text=ENCODED_MESSAGE
\`\`\`

## 🌟 Credits
- [Bootstrap](https://getbootstrap.com/) - Frontend framework
- [Font Awesome](https://fontawesome.com/) - Icons
- [DataTables](https://datatables.net/) - Enhanced tables
- [Google Fonts](https://fonts.google.com/) - Playfair Display & Poppins fonts

## 📄 License
This project is licensed under the MIT License - see the LICENSE file for details.
