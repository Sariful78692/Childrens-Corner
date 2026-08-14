-- Table structure for gallery categories
CREATE TABLE IF NOT EXISTS gallery_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL,
    category_image VARCHAR(255) NULL,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table structure for gallery photos
CREATE TABLE IF NOT EXISTS gallery_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255) NOT NULL,
    photo_date DATE,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES gallery_categories(id) ON DELETE CASCADE
);
