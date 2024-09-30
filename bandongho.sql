-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 30, 2024 lúc 08:09 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `bandongho`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`) VALUES
(1, 'Áo thun', 1),
(2, 'Áo sơ mi\r\n', 1),
(3, 'Quần shorts', 1),
(4, 'Quần jeans', 1),
(5, 'Phụ kiện nam', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_address` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone_number` varchar(20) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Đang xử lý',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `delivery_at` date DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `pay_method` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_address`, `customer_email`, `customer_phone_number`, `total_price`, `status`, `created_at`, `user_id`, `delivery_at`, `city`, `district`, `pay_method`) VALUES
(21, 'test', 'okok', 'test@gmail.com', '0912345678', 945000.00, 'Đã hoàn thành', '2024-09-29 14:16:35', 1, '2024-09-29', 'Tỉnh Hoà Bình', 'Huyện Lạc Thủy', 'cash_on_delivery');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `total_price`) VALUES
(22, 21, 55, 1, 318000.00, 318000.00),
(23, 21, 50, 3, 209000.00, 627000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `isshow` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image_url`, `price`, `quantity`, `category_id`, `isshow`) VALUES
(43, 'Áo thun thể thao Mesh', 'Đặc điểm nổi bật\r\n100% Polyester.\r\nCông nghệ Exdry, thấm hút và nhanh khô.\r\nThoải mái và co giãn tự nhiên.\r\nKiểu dệt Mesh thoáng khí.\r\nNgười mẫu: 181cm - 76kg, mặc áo 2XL.\r\nTự hào sản xuất tại Nhà máy Scavi, Việt Nam', 'Áo thun thể thao Mesh.jpg', 159000.00, 999, 1, 0),
(44, 'Áo thun nam Cotton Compact', 'Đặc điểm nổi bật\r\n76% Polyester 19% Tencel 5% Spandex.\r\nCông nghệ Exdry, thấm hút và nhanh khô.\r\nThoải mái và co giãn tự nhiên.\r\nKiểu dệt Welf Knit (Dệt kim đang ngang).\r\nNgười mẫu: 181cm - 76kg, mặc áo 2XL.\r\nTự hào sản xuất tại Nhà máy Scavi, Việt Nam.', 'Áo thun nam Cotton Compact.jpg', 219000.00, 999, 1, 0),
(45, 'Áo thun Training Comfort', 'Đặc điểm nổi bật\r\n76% Polyester 19% Tencel 5% Spandex.\r\nCông nghệ Exdry, thấm hút và nhanh khô.\r\nThoải mái và co giãn tự nhiên.\r\nKiểu dệt Welf Knit (Dệt kim đang ngang).\r\nNgười mẫu: 181cm - 76kg, mặc áo 2XL.\r\nTự hào sản xuất tại Nhà máy Scavi, Việt Nam. ', 'Áo thun Training Comfort.jpg', 259000.00, 88, 1, 0),
(46, 'Áo Sơ Mi Dài Tay Premium Poplin', 'Thông tin sản phẩm\r\nChất liệu 100% Cotton mềm mại, chống nhăn, kiểu dệt Dobby thoáng khí\r\nVải có khả năng trượt nước, chống bám bụi\r\nVải ứng dụng công nghệ nano giúp loại bỏ hơn 70% vi khuẩn và khử mùi hiệu quả\r\nKiểu dáng: Slim Fit thanh lịch\r\nNgười mẫu: 186cm - 77kg, mặc áo 2XL\r\nTự hào sản xuất tại Việt Nam', 'Áo Sơ Mi Dài Tay Premium Poplin.jpg', 599000.00, 22, 2, 0),
(47, 'Áo Sơ Mi Dài Tay Premium Dobby', 'Thông tin sản phẩm\r\nChất liệu 100% Cotton mềm mại, chống nhăn, kiểu dệt Dobby thoáng khí\r\nVải có khả năng trượt nước, chống bám bụi\r\nVải ứng dụng công nghệ nano giúp loại bỏ hơn 70% vi khuẩn và khử mùi hiệu quả\r\nKiểu dáng: Slim Fit thanh lịch\r\nNgười mẫu: 186cm - 77kg, mặc áo 2XL\r\nTự hào sản xuất tại Việt Nam', 'Áo Sơ Mi Dài Tay Premium Dobby.jpg', 599000.00, 22, 2, 0),
(48, 'Quần Shorts thể thao 7 inch đa năng', 'Đặc điểm nổi bật\r\nChất liệu: 86% Polyester + 14% Spandex, định lượng vải 125gsm\r\nLớp vải mesh: 90% Polyamide + 10% Spandex\r\nVải dệt Woven Plain\r\nCông nghệ ExDry thấm hút tốt, nhanh khô, thoáng khí\r\nQuần co giãn thoải mái\r\nĐộ dài quần: 7 inch\r\nTự hào sản xuất tại Việt Nam*\r\nNgười mẫu: 182cm - 76kg, mặc quần XL', 'Quần Shorts thể thao 7 inch đa năng.jpg', 179000.00, 999, 3, 0),
(49, 'Quần Shorts Chạy Bộ 2 Lớp Fast & Free Run II', 'Thông tin sản phẩm\r\nVải chính: 90% Polyester + 10% Spandex\r\nLớp trong: 86% Polyester + 14% Spandex\r\nKiểu dệt Plain giúp vải mịn, nhẹ và co giãn hơn\r\nLớp trong co giãn 4 chiều mang lại sự thoải mái để bạn vận động hết mình\r\nChất liệu nhẹ và co giãn tốt hơn\r\nXử lý hoàn thiện vải: Nhanh khô (Ex-Dry) + Thấm hút (Wicking)\r\nLớp trong hạn chế tối đa ma sát khi vận động nhờ các đường may tối giản ứng dụng Công nghệ Chafe-Free\r\nSản phẩm được đánh giá phù hợp với hoạt động chạy bộ bởi các Runner\r\nTự hào sản xuất tại Việt Nam', 'Quần Shorts Chạy Bộ 2 Lớp Fast & Free Run II.jpg', 119000.00, 999, 3, 0),
(50, 'Quần Shorts chạy bộ Advanced Vent Tech', 'Đặc điểm nổi bật\r\nVải Recycle Polyester với nhiều tính năng ưu việt.\r\nCông nghệ ExDry thấm hút và nhanh khô.\r\nĐịnh lượng 95gsm.\r\nPhối vải Mesh vạt sau giúp thông thoáng.\r\nGiặt máy nước lạnh, giặt nhẹ, không giặt khô, sấy khô ở mức thấp, không tẩy.\r\nNgười mẫu: 175cm - 69kg, mặc quần XL.\r\nTự hào sản xuất tại Nhà máy Scavi, Việt Nam', 'Quần Shorts chạy bộ Advanced Vent Tech.jpg', 209000.00, 74, 3, 0),
(51, 'Quần Jeans Nam Basics dáng Straight', 'Đặc điểm nổi bật\r\nChất liệu: Denim\r\nThành phần: 100% Cotton\r\nCông nghệ Laser Marking tạo các vệt hiệu ứng chuẩn xác trên sản phẩm\r\nBề mặt quần không thô ráp\r\nCo giãn tốt giúp quần ôm vừa vặn, thoải mái\r\nDáng Regular Straight suông rộng, thoải mái, không thùng thình\r\nNgười mẫu: 179 cm - 75 kg, mặc quần size 32\r\nTự hào sản xuất tại Việt Nam\r\nLưu ý:Sản phẩm vẫn sẽ bạc màu sau một thời gian dài sử dụng theo tính chất tự nhiên', 'Quần Jeans Nam Basics dáng Straight.jpg', 499000.00, 77, 4, 0),
(52, 'Quần Jeans Nam Basics dáng Slim fit', 'Đặc điểm nổi bật\r\nChất liệu: Denim\r\nThành phần: 98% Cotton + 2% Spandex\r\nCông nghệ Laser Marking tạo các vệt hiệu ứng chuẩn xác trên sản phẩm\r\nVải Denim được wash trước khi may nên không rút và hạn chế ra màu sau khi giặt\r\nBề mặt quần không thô ráp\r\nCo giãn tốt giúp quần ôm vừa vặn, thoải mái\r\nDáng Slim Fit ôm tôn dáng, giúp bạn \"hack\" đôi chân dài và gọn đẹp\r\nNgười mẫu: 179 cm - 75 kg, mặc quần size 32\r\nTự hào sản xuất tại Việt Nam\r\nLưu ý:Sản phẩm vẫn sẽ b', 'Quần Jeans Nam Basics dáng Slim fit.jpg', 499000.00, 77, 4, 0),
(53, 'Đai Đeo Bụng Chạy Bộ Fast & Free', 'Đặc điểm nổi bật\r\nChất liệu: Vải knit Mesh\r\nThành phần: 85% Polyamide + 15% Elasthan\r\nBề mặt lưới 2 lớp thoáng khí, nhanh khô\r\nLớp lưới mặt trong giúp hạn chế ma sát, thoải mái vận động\r\nĐai gồm nhiều ngăn túi, đựng vừa ví, điện thoại, chai nước\r\nDây treo gậy đi trail\r\nLogo phản quang tăng sự an toàn khi tập luyện trời tối\r\nĐai đeo ôm sát bụng\r\nTự hào sản xuất tại Việt Nam', 'Đai Đeo Bụng Chạy Bộ Fast & Free.jpg', 359000.00, 77, 5, 0),
(54, 'Túi UT Duffle size vừa 18L', 'Thông tin sản phẩm\r\nChất liệu Polyester tái chế có độ bền bỉ cao, thân thiện với môi trường.\r\nChống thấm nước, giúp hạn chế hư hỏng.\r\nLớp vải lót bên trong túi được làm từ chất liệu mềm mịn, được xử lý kháng khuẩn nghiêm ngặt, chống nấm mốc.\r\nKích thước của túi: Dài - 43cm; Rộng - 21cm; Cao - 26cm', 'Túi UT Duffle size vừa 18Lg', 359000.00, 77, 5, 0),
(55, 'Combo 2 Cặp Gaiter Chạy Bộ Bọc Chân', 'Đặc điểm nổi bật\r\nThành phần: 88% Recycled Polyester + 12% Spandex\r\nKiểu dệt Wrap Knit\r\nTrọng lượng siêu nhẹ\r\nDây chun vòng qua đế giày, giữ cố đinh khi chạy\r\nLogo phản quang tăng sự an toàn khi tập luyện trời tối\r\nTự hào sản xuất tại Việt Nam', 'Combo 2 Cặp Gaiter Chạy Bộ Bọc Chân.jpg', 318000.00, 76, 5, 0),
(56, 'Túi Tote 84RISING Denim', 'Đặc điểm nổi bật\r\nChất liệu: 100% Jeans Cotton cao cấp\r\nKích thước: 40cm x 37cm x 7cm\r\nCông nghệ wash bạc màu hiện đại\r\nThiết kế túi tote tối giản gồm 2 phần: thân túi thường là hình chữ nhật và dây xách\r\nMàu sắc: Xanh Navy\r\nTự hào sản xuất tại Việt Nam', 'Túi Tote 84RISING Denim.jpg', 199000.00, 77, 5, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `is_admin`, `status`) VALUES
(1, 'nguyen1', 'nguyen1', 'dnnguyen@gmail.com', 1, 0),
(2, 'nguyen2', 'nguyen2', 'nguyen2@gmail.com', 0, 0),
(5, 'hoang', 'hoangvuive', 'hoangvuive@gmail.com', 0, 1),
(6, 'nguyen5', 'nguyen5', 'nguyen5@gmail.com', 0, 1),
(7, 'minhson', 'minhson', 'minhson@gmail.com', 0, 0),
(9, 'hoang1', 'hoang1', 'hodanghoang@gmail.com', 1, 0),
(10, 'minhson2', '123', 'son@gmail.com', 0, 0),
(11, 'sonw', '123', 'son@gmail.com', 0, 0),
(12, 'son2321', '123', 'son@gmail.com', 0, 0),
(13, 'son23213', '123', 'son@gmail.com', 0, 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
