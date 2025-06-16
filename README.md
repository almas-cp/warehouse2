# Warehouse Inventory Management System

A comprehensive warehouse inventory management system built with PHP and CodeIgniter 3. This application helps track inventory, process transactions, and generate barcodes for efficient warehouse operations.

## Features

- **Product Management**: Add, edit, and delete products with details like name, price, quantity, and category
- **Transaction Processing**: Check-in and check-out inventory with automatic stock updates
- **Barcode System**: Generate and print product barcodes
- **Barcode Scanning**: Scan barcodes to quickly process transactions
- **Dashboard**: View key metrics and stats at a glance
- **Low Stock Alerts**: Visual indicators for products with low inventory
- **Search Functionality**: Quickly find products
- **Mobile Responsive**: Works on all device sizes

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (optional)

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/warehouse.git
   ```

2. Create a MySQL database and import the SQL schema from `database/schema.sql`

3. Configure the database connection in `application/config/database.php`:
   ```php
   $db['default'] = array(
       'dsn'          => '',
       'hostname'     => 'localhost',
       'username'     => 'your_username',
       'password'     => 'your_password',
       'database'     => 'warehouse',
       'dbdriver'     => 'mysqli',
       // ... other settings
   );
   ```

4. Ensure the `.htaccess` file is correctly configured for your server

5. Set the base URL in `application/config/config.php`:
   ```php
   $config['base_url'] = 'http://your-domain.com/warehouse/';
   ```

6. Make sure the `cache` directory is writable:
   ```
   chmod -R 777 application/cache/
   ```

## Usage

1. **Dashboard**: View key metrics and inventory status
2. **Add Product**: Add new products to inventory
3. **Check-in/Check-out**: Process incoming and outgoing inventory
4. **Generate Barcodes**: Create and print barcode labels
5. **Scan Barcodes**: Use camera to scan product barcodes

## Performance Optimizations

The application includes several performance enhancements:

- **Caching**: Database query results are cached to reduce load
- **Compressed Output**: GZIP compression for smaller response sizes
- **Optimized Queries**: Efficient database queries with proper indexing
- **Throttle/Debounce**: Frontend optimizations for search and UI interactions
- **Asset Caching**: Browser caching for static assets

## Security Features

- **Input Validation**: All form inputs are validated
- **XSS Protection**: Prevents cross-site scripting attacks
- **CSRF Protection**: Guards against cross-site request forgery
- **SQL Injection Prevention**: Parameterized queries for database operations

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgements

- [CodeIgniter](https://codeigniter.com/) - The web framework used
- [Bootstrap](https://getbootstrap.com/) - Frontend framework
- [ZXing](https://github.com/zxing-js/library) - Barcode scanning library
- [BarcodeAPI.org](https://barcodeapi.org/) - Barcode generation service 