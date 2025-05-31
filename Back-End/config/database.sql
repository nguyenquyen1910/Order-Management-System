CREATE DATABASE 'order_management';
USE 'order_management';

-- Bang khach hang
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    username VARCHAR(50) UNIQUE,    
    password VARCHAR(255),         
    email VARCHAR(100),             
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO customers (name, phone, address, username, password, email, status) VALUES
('Nguyen Van A', '0912345678', '123 Đường Láng, Hà Nội', 'nguyenvana', '$2y$10$z1X2Y3W4Q5R6T7U8I9O0P1A2S3D4F5G6H7J8K9L0Z1X2C3V4B5N6M7', 'nguyenvana@example.com', 1),
('Tran Thi B', '0987654321', '456 Nguyễn Trãi, TP.HCM', 'tranthib', '$2y$10$z1X2Y3W4Q5R6T7U8I9O0P1A2S3D4F5G6H7J8K9L0Z1X2C3V4B5N6M7', 'tranthib@example.com', 1),
('Le Van C', '0934567890', '789 Lê Lợi, Đà Nẵng', 'levanc', '$2y$10$z1X2Y3W4Q5R6T7U8I9O0P1A2S3D4F5G6H7J8K9L0Z1X2C3V4B5N6M7', 'levanc@example.com', 1),
('Pham Thi D', '0945678901', '321 Trần Phú, Nha Trang', 'phamthid', '$2y$10$z1X2Y3W4Q5R6T7U8I9O0P1A2S3D4F5G6H7J8K9L0Z1X2C3V4B5N6M7', 'phamthid@example.com', 1),
('Hoang Van E', '0956789012', '654 Nguyễn Huệ, Huế', 'hoangvane', '$2y$10$z1X2Y3W4Q5R6T7U8I9O0P1A2S3D4F5G6H7J8K9L0Z1X2C3V4B5N6M7', 'hoangvane@example.com', 0);

-- Bang nhan vien
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(100),
    role ENUM('admin', 'manager', 'staff') NOT NULL DEFAULT 'staff',
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO employees (name, username, password, phone, email, role, status) VALUES
('Admin User', 'admin', '$2y$10$z1X2Y3W4Q5R6T7U8I9O0P1A2S3D4F5G6H7J8K9L0Z1X2C3V4B5N6M7', '0901234567', 'admin@example.com', 'admin', 1),
('Manager User', 'manager', '$2y$10$z1X2Y3W4Q5R6T7U8I9O0P1A2S3D4F5G6H7J8K9L0Z1X2C3V4B5N6M7', '0902345678', 'manager@example.com', 'manager', 1),
('Staff User', 'staff', '$2y$10$z1X2Y3W4Q5R6T7U8I9O0P1A2S3D4F5G6H7J8K9L0Z1X2C3V4B5N6M7', '0903456789', 'staff@example.com', 'staff', 1);

-- Bang danh muc san pham
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

insert into categories (name) values
('Món chay'),
('Món mặn'),
('Món lẩu'),
('Món ăn vặt'),
('Món tráng miệng'),
('Nước uống'),
('Món khác');

-- Bang san pham
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category_id INT,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

insert into products (status, name, category_id, image_url, price, description) values
(1, 'Nấm đùi gà xào cháy tỏi', 1, './assets/images/products/nam-dui-ga-chay-toi.jpeg', 60000, 'Một Món chay ngon miệng với nấm đùi gà thái chân hương, xào săn với lửa và thật nhiều tỏi băm, nêm nếm với mắm và nước tương chay, món ngon đưa cơm và rất dễ ăn cả cho người lớn và trẻ nhỏ.'),
(1, 'Rau xào ngũ sắc', 1, './assets/images/products/rau-xao-ngu-sac.png', 60000, 'Rau củ quả theo mùa tươi mới xào với nước mắm chay, gia vị để giữ được hương vị ngọt tươi nguyên thủy của rau củ, một món nhiều vitamin và chất khoáng, rất dễ ăn.'),
(1, 'Bánh lava phô mai nướng', 5, './assets/images/products/banh_lava_pho_mai_nuong.jpeg', 50000, 'Một món tráng miệng ngon miệng với lớp phô mai tan chảy bên trong.'),
(1, 'Set lẩu thái Tomyum', 3, './assets/images/products/lau_thai.jpg', 299000, 'Lẩu Thái là món ăn xuất phát từ món canh chua Tom yum nổi tiếng của Thái Lan. Nước lẩu có hương vị chua chua cay cay đặc trưng. Các món nhúng lẩu gồm thịt bò, hải sản, rau xanh và các loại nấm.'),
(1, 'Cơm chiên cua', 2, './assets/images/products/com_chien_cua.png', 60000, 'Cơm nấu từ gạo ST25 dẻo, hạt cơm tơi ngon, thịt cua tươi chắc nịch, bếp đảo cho săn hạt cơm, rồi đổ cua đã xào thơm vào, xúc miếng cơm chiên cua đầy đặn có thêm hành phi giòn rụm.'),
(1, 'Súp bào ngư hải sâm (1 phần)', 2, './assets/images/products/sup-bao-ngu-hai-sam.jpeg', 540000, 'Súp bào ngư kết hợp cùng sò điệp, tôm tươi, được hầm trong nhiều giờ với rau củ và nấm đông trùng tạo ra vị ngọt tự nhiên.'),
(1, 'Tai cuộn lưỡi', 2, './assets/images/products/tai-cuon-luoi.jpeg', 240000, 'Tai heo được cuộn bên trong cùng phần thịt lưỡi heo. Phần tai giòn dai, phần thịt lưỡi mềm và ngọt tự nhiên, chấm với nước mắm và tiêu đen.'),
(1, 'Xíu mại tôm thịt 10 viên', 2, './assets/images/products/xiu_mai_tom_thit_10_vien.jpg', 100000, 'Xíu mại với phần nhân tôm và thịt heo thơm ngậy, hấp dẫn.'),
(1, 'Trà phô mai kem sữa', 6, './assets/images/products/tra-pho-mai-kem-sua.jpg', 34000, 'Nước uống vừa béo ngậy, chua ngọt đủ cả, mà vẫn có vị thanh của trà.'),
(1, 'Trà đào chanh sả', 6, './assets/images/products/tra-dao-chanh-sa.jpg', 25000, 'Trà đào chanh sả có vị đậm ngọt thanh của đào, vị chua dịu của chanh và hương thơm của sả.'),
(1, 'Bánh chuối nướng', 5, './assets/images/products/banh-chuoi-nuong.jpeg', 60000, 'Bánh chuối nướng béo ngậy mùi nước cốt dừa cùng miếng chuối mềm ngon sẽ là Món tráng miệng phù hợp với mọi người.'),
(1, 'Há cảo sò điệp (10 viên)', 2, './assets/images/products/ha_cao.jpg', 140000, 'Những miếng há cảo, sủi cảo, hoành thánh với phần nhân tôm, sò điệp, hải sản tươi ngon hay nhân thịt heo thơm ngậy chắc chắn sẽ khiến bất kỳ ai thưởng thức đều cảm thấy rất ngon miệng.'),
(1, 'Chả rươi (100gr)', 2, './assets/images/products/thit_nuong.jpg', 60000, 'Chả rươi luôn mang đến hương vị khác biệt và "gây thương nhớ" hơn hẳn so với các loại chả khác. Rươi béo càng ăn càng thấy ngậy. Thịt thơm quyện mùi thì là và vỏ quýt rất đặc sắc. Chắc chắn sẽ là một món ăn rất hao cơm'),
(1, 'Nộm gà Hội An (1 phần)', 2, './assets/images/products/nom_ga_hoi_an.png', 60000, 'Nộm gà làm từ thịt gà ri thả đồi. Thịt gà ngọt, săn được nêm nếm vừa miệng, bóp thấu với các loại rau tạo thành món nộm thơm ngon, đậm đà, giải ngán hiệu quả.'),
(1, 'Set bún cá (1 set 5 bát)', 2, './assets/images/products/set_bun_ca.jpg', 60000, 'Bún cá được làm đặc biệt hơn với cá trắm lọc xương và chiên giòn, miếng cá nhúng vào nước dùng ăn vẫn giòn dai, thơm ngon vô cùng.');

-- Bang don hang
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    employee_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    delivery_method VARCHAR(50) NOT NULL,
    delivery_date DATETIME NOT NULL,
    received_name VARCHAR(100) NOT NULL,
    received_phone VARCHAR(15) NOT NULL,
    received_address TEXT NOT NULL,
    note TEXT,
    status int default 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

-- Bang chi tiet don hang
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Bang thong bao
CREATE TABLE notifications (
    id int primary key,
    title varchar(255) not null,
    description text, 
    created_at timestamp default current_timestamp,
    status int default 0,
    type varchar(50) not null
);

-- Bang van chuyen
CREATE TABLE deliveries (
    id int primary key auto_increment,
    order_id int not null,
    delivery_staff_name varchar(100) not null,
    status enum('pending', 'in_progress', 'completed', 'cancelled') default 'pending',
    current_latitude DECIMAL(10,8),
    current_longitude DECIMAL(11,8),
    destination_latitude DECIMAL(10,8),
    destination_longitude DECIMAL(11,8),
    estimated_delivery_time datetime,
    actual_delivery_time datetime,
    created_at timestamp default CURRENT_TIMESTAMP,
    updated_at timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,

    foreign key (order_id) references orders(id),
)


INSERT INTO deliveries (
    order_id, delivery_staff_name, status,
    current_latitude, current_longitude,
    destination_latitude, destination_longitude,
    estimated_delivery_time, actual_delivery_time
) VALUES
(1, 'Nguyễn Văn Giao', 'completed', 21.0081, 105.8201, 21.0081, 105.8201, '2025-01-01 11:00:00', '2025-01-01 11:20:00'),
(2, 'Nguyễn Văn Giao', 'completed', 10.762622, 106.660172, 10.762622, 106.660172, '2025-01-15 14:50:00', '2025-01-15 14:45:00'),
(3, 'Lê Văn Vận', 'completed', 16.047079, 108.206230, 21.0034624, 105.8126477, '2025-02-01 09:50:00', '2025-02-01 09:50:00'),
(5, 'Nguyễn Văn Giao', 'completed', 21.0398397, 105.7673309, 21.0398397, 105.7673309, '2025-03-01 12:20:00', '2025-03-01 12:30:00'),
(128, 'Nguyễn Văn Giao', 'completed', 21.0112005, 105.7903668, 21.0112005, 105.7903668, '2025-04-01 16:30:00', '2025-04-01 16:30:00'),
(129, 'Lê Văn Vận', 'completed', 20.9800038, 105.7875458, 20.9800038, 105.7875458, '2025-04-15 11:45:00', '2025-04-15 11:45:00'),
(130, 'Lê Văn Vận', 'completed', 21.0395627, 105.7672166, 21.0395627, 105.7672166, '2025-05-08 21:30:00', '2025-05-08 21:30:00'),
(131, 'Lê Văn Vận', 'completed', 21.0249167, 105.8407944, 21.0249167, 105.8407944, '2025-05-10 08:15:25', '2025-05-10 08:20:25'),
(132, 'Lê Văn Vận', 'in_progress', 20.9809856, 105.7849596, 21.03965, 105.7586934, '2025-05-13 10:45:20', NULL);