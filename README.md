# Simple POS Project (PHP + Tailwind CSS)

This is a minimal point-of-sale example using PHP, MySQL, plain PHP pages, Tailwind CSS via CDN, and vanilla JS for realtime calculations. It implements:

- Product CRUD (with image upload)
- Sales/transactions with cart (realtime totals)
- Transaction numbering TRX-YYYYMMDD-####
- Receipt (printable HTML)
- Basic dashboard

Requirements

- PHP 8+ (with PDO MySQL)
- MySQL

Setup

1. Copy project files to your web root or run built-in PHP server:

```powershell
php -S localhost:8000 -t .
```

2. Create database and import schema:

```sql
-- run schema.sql
```

3. Edit `inc/db.php` and set your DB credentials.

Files

- `index.php` - Dashboard
- `products.php` - Product list
- `product_form.php` - Add/Edit product
- `delete_product.php` - Delete product
- `sales.php` - Sales/checkout
- `save_sale.php` - Save transaction
- `receipt.php` - Printable receipt
- `inc/db.php` - PDO connection
- `assets/script.js` - Client-side JS
- `schema.sql` - Database schema

Notes

- Images are stored in `/uploads` folder. Ensure write permission.
- Tailwind is included via CDN for simplicity.

Feel free to ask for further enhancements (authentication, nicer UI, pagination, CSV/PDF exports).
