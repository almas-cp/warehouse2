-- Drop tables if they exist
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS products;

-- Create products table
CREATE TABLE products (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    category VARCHAR(100) NOT NULL,
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) AUTO_INCREMENT = 1000;

-- Create transactions table
CREATE TABLE transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    transaction_type ENUM('check-in', 'check-out') NOT NULL,
    quantity INT NOT NULL,
    transaction_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    benefactor VARCHAR(255) NOT NULL,
    notes TEXT,
    FOREIGN KEY (item_id) REFERENCES products(item_id) ON DELETE CASCADE
);

-- Add sample products (optional)
INSERT INTO products (name, unit_price, quantity, category) VALUES
('Laptop', 1200.00, 10, 'Electronics'),
('Desk Chair', 150.00, 20, 'Furniture'),
('Whiteboard', 75.50, 15, 'Office Supplies'),
('Projector', 499.99, 5, 'Electronics'),
('Stapler', 8.75, 50, 'Office Supplies'); 