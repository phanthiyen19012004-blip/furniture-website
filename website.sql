-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 05, 2025 lúc 07:35 AM
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
-- Cơ sở dữ liệu: `website`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`, `level`, `created`) VALUES
(3, 'nhan', 'nhan', '202cb962ac59075b964b07152d234b70', 1, '2025-05-22 14:42:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `catalog`
--

CREATE TABLE `catalog` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `catalog`
--

INSERT INTO `catalog` (`id`, `name`, `description`, `created`) VALUES
(4, 'Sofa vải', 'Sofa vải', '2025-05-23 03:15:03'),
(5, 'Sofa da', 'Sofa da', '2025-05-23 03:15:28'),
(6, 'Sofa góc', 'Sofa góc', '2025-05-23 03:18:53'),
(7, 'Bàn Sofa ', 'Bàn Sofa ', '2025-05-23 03:19:31'),
(8, 'Bàn ăn ', 'Bàn ăn', '2025-05-23 03:19:52'),
(9, 'Tủ giày', 'Tủ giày', '2025-05-23 03:20:10'),
(10, 'Tủ trang trí', 'Trang trí', '2025-05-23 03:20:21'),
(11, 'Ghế ăn', 'Ghế ăn', '2025-05-23 03:20:55'),
(12, 'Ghế thư giãn', 'Ghế thư giãn', '2025-05-23 03:21:05'),
(13, 'Ghế quầy bar', 'Ghế quầy bar', '2025-05-23 03:21:26'),
(14, 'Giường gỗ', 'Giường gỗ', '2025-05-23 03:21:41'),
(15, 'Giường vải', 'Giường vải', '2025-05-23 03:21:57'),
(16, 'Nệm', 'Nệm', '2025-05-23 03:22:06'),
(17, 'Ghế văn phòng ', 'Ghế văn phòng ', '2025-05-23 03:22:22'),
(18, 'Bàn văn phòng', 'Bàn văn phòng', '2025-05-23 03:22:33'),
(19, 'Tủ văn phòng', 'Tủ văn phòng hiện đại', '2025-05-24 03:08:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image_link` varchar(255) DEFAULT NULL,
  `rate` int(11) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment_reply`
--

CREATE TABLE `comment_reply` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact_info`
--

CREATE TABLE `contact_info` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `zalo` varchar(20) DEFAULT NULL,
  `address` text NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contact_info`
--

INSERT INTO `contact_info` (`id`, `phone`, `zalo`, `address`, `updated_at`) VALUES
(1, '0328104037', '0328104037', 'https://maps.app.goo.gl/mGo5ndxu8RAh5DD37', '2025-06-02 18:46:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `phone`, `message`, `created_at`) VALUES
(1, 'da', 'nhanmit120604@gmail.com', '0328104038', 'ftui', '2025-04-19 11:51:38'),
(2, 'da', 'nhanmit120604@gmail.com', '0328104037', 'đấ', '2025-05-22 09:06:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL COMMENT 'Mã giảm giá',
  `discount_percent` int(11) NOT NULL COMMENT 'Phần trăm giảm giá, ví dụ 10 nghĩa là 10%',
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái kích hoạt: 1 = hoạt động, 0 = không hoạt động',
  `expiry_date` date NOT NULL COMMENT 'Ngày hết hạn mã giảm giá',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Ngày tạo',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Bảng lưu mã giảm giá';

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_percent`, `active`, `expiry_date`, `created_at`, `updated_at`) VALUES
(1, 'GIAM10', 10, 1, '2025-12-31', '2025-06-01 21:51:42', '2025-06-01 21:51:42'),
(2, 'GIAM20', 20, 1, '2025-06-30', '2025-06-01 22:03:35', '2025-06-01 22:03:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `footer_column`
--

CREATE TABLE `footer_column` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `footer_column`
--

INSERT INTO `footer_column` (`id`, `title`, `sort_order`) VALUES
(1, 'THÀNH VIÊN THỰC HIỆN', 1),
(2, 'HỖ TRỢ KHÁCH HÀNG', 2),
(3, 'LIÊN HỆ', 3),
(4, 'CÔNG TY TNHH THƯƠNG MẠI VÀ XÂY DỰNG DỊCH VỤ VÀ TRANG TRÍ NỘI NGOẠI THẤT HomeStyle', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `footer_link`
--

CREATE TABLE `footer_link` (
  `id` int(11) NOT NULL,
  `column_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `footer_link`
--

INSERT INTO `footer_link` (`id`, `column_id`, `name`, `link`) VALUES
(1, 1, 'Nguyễn Đức Chính Nhân – 12/06/2004', NULL),
(2, 1, 'Đỗ Đức Ngân – 26/10/2004', NULL),
(3, 1, 'Trần Chí Mười – 02/03/2004', NULL),
(4, 1, 'Phan Thị Yến – 19/11/2004', NULL),
(5, 2, 'Chính sách đổi trả', '#'),
(6, 2, 'Chính sách bảo mật', '#'),
(7, 2, 'Điều khoản dịch vụ', '#'),
(8, 2, 'Chính sách bán hàng', '#'),
(9, 2, 'Giao hàng & Nhận hàng', '#'),
(10, 2, 'Phương thức thanh toán', '#'),
(11, 2, 'Chính sách bảo hành', '#'),
(12, 3, 'Email: support@homestyle.vn', NULL),
(13, 3, 'Hotline: 1800 6868 – 1800 6969', NULL),
(14, 4, '243 Trường Chinh, P. Quang Trung, TP.Nam Định', NULL),
(15, 4, 'GPKD: 0302202186 – Sở KHĐT TP.Nam Định', NULL),
(16, 4, 'Ngày cấp: 14/11/2019', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `content`) VALUES
(1, 'Chào mừng bạn đến với website bán hàng nội thất HomeStyle');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `models`
--

CREATE TABLE `models` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `models`
--

INSERT INTO `models` (`id`, `name`, `file_path`, `created_at`) VALUES
(1, 'Wooden Table/Chair', 'wooden_tablechair__low-poly__game-ready.glb', '2025-06-02 19:51:16'),
(2, 'soviet chair', 'soviet_chair.glb', '2025-06-02 20:59:41'),
(3, 'soviet table', 'soviet_table.glb', '2025-06-02 21:03:50'),
(4, 'Table from a gothic library', 'table_from_a_gothic_library.glb', '2025-06-02 21:04:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `transaction_id` int(100) DEFAULT NULL,
  `product_id` int(100) DEFAULT NULL,
  `qty` int(100) DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order`
--

INSERT INTO `order` (`id`, `transaction_id`, `product_id`, `qty`, `amount`, `status`) VALUES
(64, 51, 142, 1, 6490000.00, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `id` int(255) NOT NULL,
  `catalog_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `discount` decimal(15,2) DEFAULT NULL,
  `image_link` varchar(100) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `image_list` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`id`, `catalog_id`, `name`, `content`, `price`, `discount`, `image_link`, `created`, `image_list`) VALUES
(91, 4, 'Ghế Sofa vải MOHO HALDEN 801', 'Kích thước: Dài 180cm x Rộng 85cm x Cao 82cm\r\n\r\nChất liệu:\r\n\r\n- Gỗ cao su tự nhiên\r\n\r\n- Vải sợi tổng hợp có khả năng chống thấm nước và dầu\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn sức khỏe.', 12000000.00, 6000000.00, NULL, '2025-06-03 06:10:07', 'pro_nau_noi_that_moho_halden_3_b8ebe007869e421c8e2a5399459468b3_master.jpg,pro_nau_noi_that_moho_ghesofa_a_5eece5b4358f4d4eb80fe060663e4869_master.jpg'),
(92, 4, 'Ghế Sofa Vải MOHO LYNGBY 601', 'Kích thước: Dài 160cm x Rộng 79cm x Cao 72cm\r\n\r\nChất liệu:\r\n\r\n- Gỗ cao su tự nhiên\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn sức khỏe.', 10790000.00, 6499000.00, NULL, '2025-06-03 06:17:34', 'pro_be_noi_that_moho_sofa_lyngby_05de7af88c3440a5a9549911f7d68ef1_master.jpg,pro_be_noi_that_moho_sofa_lyngby_7_a_4bb79b11eaac479588926aaa9179cce7_master.jpg'),
(93, 4, 'GHẾ SOFA Vải HOBRO 301 (180) BÊN PHẢI', '(Đi kèm 3 gối tựa lưng) \r\n\r\nKích thước: Rộng 900 x Dài 1800 x Cao 700\r\n\r\nChất liệu: \r\n\r\n- Gỗ cao su tự nhiên\r\n\r\n- Vải sợi tổng hợp chống nhăn, kháng bụi bẩn và nấm mốc\r\n\r\n- Tấm phản: Gỗ công nghiệp Plywood chuẩn CARB-P2 (*) \r\n\r\n*Vải chống cháy, chống bám bẩn theo tiêu chuẩn quốc tế OEKO TEX (Standard 100).\r\n\r\n', 13990000.00, 10490000.00, NULL, '2025-06-03 06:20:00', 'pro_mau_nau_xam_noi_that_moho_sofa_trai_1_5bcd6d7b2dec49ae87cff11d0acb8474_master.webp,pro_mau_nau_xam_noi_that_moho_sofa_trai_2a_17e4134d6a3f4d6da67504ed41c424d6_master.webp'),
(94, 6, 'Ghế Sofa Góc HOBRO 301 (160) Bên Trái', '(Đi kèm 1 gối tựa lưng) \r\n\r\nKích thước: Rộng 900 x Dài 1600 x Cao 700\r\n\r\nChất liệu: \r\n\r\n- Gỗ cao su tự nhiên\r\n\r\n- Vải sợi tổng hợp chống nhăn, kháng bụi bẩn và nấm mốc\r\n\r\n- Tấm phản: Gỗ công nghiệp Plywood chuẩn CARB-P2 (*) \r\n\r\n*Vải chống cháy, chống bám bẩn theo tiêu chuẩn quốc tế OEKO TEX (Standard 100).', 11990000.00, 8990000.00, NULL, '2025-06-03 06:22:19', 'pro_mau_nau_xam_noi_that_moho_sofa_goc_phai___5__229f3cbd5cd748e4a9f20d81a1fd5539_master.jpg,pro_mau_nau_xam_noi_that_moho_sofa_goc_phai___3__f54bc175ab754f899aa1ec04b3573589_master.jpg'),
(95, 4, 'Ghế Sofa Vải Tự Nhiên MOHO VLINE 601 Màu Xám Đậm', 'Kích thước: Dài 180cm x Rộng 84cm x Cao 69cm\r\n\r\nChất liệu:\r\n\r\n- Gỗ cao su tự nhiên\r\n\r\n- Vải sợi tổng hợp có khả năng chống thấm nước và dầu\r\n\r\n- Tấm phản: Gỗ công nghiệp Plywood chuẩn CARB-P2 (*) \r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn sức khỏe', 11490000.00, 7499000.00, NULL, '2025-06-03 06:24:40', 'pro_mau_tu_nhien_noi_that_moho_ghe_sofa_go_vline_3_817dfe1cdb604b489290bd084f8505c9_master.jpg,pro_mau_tu_nhien_noi_that_moho_ghe_sofa_go_vline_1_4fd0cd2ed09a4e368d6324d884bfce99_master.jpg'),
(96, 6, 'Ghế Sofa Góc Chữ L Gỗ Cao Su Tự Nhiên MOHO VLINE 601 Kèm Đôn', 'Kích thước:\r\n\r\n- Ghế sofa: Dài: 180cm x Rộng 85cm x Cao 69cm\r\n\r\n- Ghế sofa góc: Dài 140cm x Rộng 60cm x Cao 69cm\r\n\r\n- Đôn sofa: Dài 60cm x Rộng 60cm x Cao 37cm\r\n\r\nChất liệu:\r\n\r\n- Thân ghế: Gỗ tràm tự nhiên (Ghế sofa Nâu)\r\n\r\n- Thân ghế: Gỗ cao su tự nhiên (Ghế sofa Màu tự nhiên)\r\n\r\n- Chân ghế: Gỗ cao su tự nhiên\r\n\r\n- Vải sợi tổng hợp chống nhăn, kháng bụi bẩn và nấm mốc\r\n\r\n- Tấm phản: Gỗ công nghiệp Plywood chuẩn CARB-P2 (*) \r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn sức khỏe.', 21570000.00, 0.00, NULL, '2025-06-03 06:28:00', 'pro_nau_noi_that_moho_combo_ghe_sofa_vline_be_2_805e3aec75d4487483494eff55ccbb3b_master.jpg,pro_nau_noi_that_moho_combo_ghe_sofa_nau_kem_don_nem_xam_8d66f8aea67545d0885f3ea8bd53c132_master.jfif'),
(97, 6, 'Ghế Sofa Góc Gỗ Cao Su Tự Nhiên MOHO VLINE 601', 'Kích thước: Rộng 140cm x Dài 60cm x Cao 69cm\r\n\r\nChất liệu:\r\n\r\n- Gỗ cao su tự nhiên\r\n\r\n- Vải sợi tổng hợp chống nhăn, kháng bụi bẩn và nấm mốc\r\n\r\n- Tấm phản: Gỗ công nghiệp Plywood chuẩn CARB-P2 (*) \r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn sức khỏe.\r\n\r\nChống thấm, cong vênh, trầy xước, mối mọt', 7490000.00, 0.00, NULL, '2025-06-03 06:30:01', 'pro_nau_noi_that_moho_ghe_sofa_goc_vline_dem_be_3_4798af049ae141dc9ad7fb8cc40fe461_master.jpg,pro_nau_noi_that_moho_ghe_sofa_goc_vline_dem_be_2_22f7622e011b4761851383379179f03d_master.jpg'),
(98, 6, 'Ghế Sofa Góc Chữ L Gỗ Cao Su Tự Nhiên MOHO VLINE 601', 'Kích thước:\r\n\r\n- Ghế sofa: Dài: 180cm x Rộng 85cm x Cao 69cm\r\n\r\n- Ghế sofa góc: Rộng 140cm x Dài 60cm x Cao 69cm\r\n\r\nChất liệu:\r\n\r\n- Thân ghế: Gỗ tràm tự nhiên (Ghế sofa Nâu)\r\n\r\n- Thân ghế: Gỗ cao su tự nhiên (Ghế sofa Màu tự nhiên)\r\n\r\n- Chân ghế: Gỗ cao su tự nhiên\r\n\r\n- Vải sợi tổng hợp có khả năng chống thấm nước và dầu\r\n\r\n- Tấm phản: Gỗ công nghiệp Plywood chuẩn CARB-P2 (*) \r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn sức khỏe.\r\n\r\nChống thấm, cong vênh, trầy xước, mối mọt', 18980000.00, 12290000.00, NULL, '2025-06-03 06:31:38', 'pro_nau_xam_noi_that_moho_sofa_vline_3_d288dd44e1994ee68a7ec4b9a9925918_master.jpg,pro_nau_pk_vline_be_f8cfcf2da6634287a0e1848a53f7e6e3_master.jpg'),
(99, 7, 'Bàn Sofa MOHO KOSTER Màu Nâu', 'Kích thước:\r\n\r\nBàn Sofa: Dài 90 X Rộng 50 X H40 (cm)\r\n\r\nChất liệu chính:\r\n\r\nGỗ cao su, gỗ MFC/ MDF chuẩn CARB P2 (*)\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 2090000.00, 1490000.00, NULL, '2025-06-03 06:33:11', 'pro_nau_noi_that_moho_ban_sofa_koster_mau_nau_4_246a595956e34970b209324185807d8c_master.webp,pro_nau_noi_that_moho_ban_sofa_koster_mau_nau_2_2e9b0e60494541dc8f65b0efcf70298a_master.webp'),
(100, 7, 'Set Bàn Sofa - Bàn Trà - Bàn Cafe Gỗ KLINE', 'Kích thước:\r\n\r\nBàn Oval - Thấp: Dài 75cm x Rộng 40cm x Cao 38cm\r\nBàn Tròn Cao:  Rộng 50cm x Cao 48 cm\r\n\r\nChất liệu:\r\n\r\n- Mặt bàn: Gỗ công nghiệp MFC chuẩn CARB-P2 (*), Sơn phủ UV\r\n\r\n- Chân bàn: Gỗ cao su tự nhiên\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 3490000.00, 1790000.00, NULL, '2025-06-03 06:34:43', 'pro_mau_trang_ban_sofa_kline_4b081d432a98411f8e45677cceac98da_master.webp,pro_mau_trang_ban_sofa_kline__3__fd8b995b9431476890de2dc742aacb75_master.webp,pro_mau_trang_ban_sofa_kline__2__c66b166ceb344dab99cbbdd5fddc6a87_master.webp'),
(101, 7, 'Bàn Sofa – Bàn Cafe – Bàn Trà Gỗ Cao Su MOHO MILAN 601 Xám', 'Kích thước: Dài 70cm x Rộng 60cm x Cao 38cm\r\n\r\nChất liệu:\r\n\r\n- Mặt bàn: Gỗ công nghiệp MDF chuẩn CARB-P2 (*), Sơn phủ UV\r\n\r\n- Chân bàn: Gỗ cao su tự nhiên\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 649000.00, 0.00, NULL, '2025-06-03 06:36:13', 'pro_xam_noi_that_moho_ban_sofa_verona_b75234d3d65b4cea988e64b695b8aae5_master.webp,pro_xam_noi_that_moho_ban_sofa_verona_07_b86fcc1afedf431187f5dac3e56fb449_master.jpg'),
(102, 7, 'Bàn Sofa - Bàn Cafe - Bàn Trà Gỗ MOHO UBEDA 201', 'Kích thước: Dài 600 X Rộng 400 X Cao 350\r\n\r\nChất liệu chính: Gỗ tràm và gỗ MFC chuẩn CARB P2 (*) (*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 1690000.00, 899000.00, NULL, '2025-06-03 06:37:26', 'pro_mau_tu_nhien_noi_that_moho_ban_cafe_go_ubeda___4__63a09e673a92495b956c01ca1be46527_master.jpg,pro_mau_tu_nhien_noi_that_moho_ban_cafe_go_ubeda___1__fd2fe0a0c3ce475ca70111bb838e9460_master.webp'),
(103, 8, 'Bàn Ăn Gỗ Cao Su Tự Nhiên MOHO VLINE 601 1m6', 'Kích thước: Dài 160cm x Rộng 75cm x Cao 65cm\r\n\r\nChất liệu: Gỗ cao su tự nhiên\r\n\r\nChống thấm, cong vênh, trầy xước, mối mọt', 4590000.00, 0.00, NULL, '2025-06-03 06:38:54', 'pro_nau_noi_that_moho_ban_an_go_vlne_3_30e8339ae5484bc080f98197a7bb3bc8_master.webp,pro_nau_noi_that_moho_ban_an_go_vlne_1_02c484a7c1914fd18e85938012442389_master.webp'),
(104, 8, 'Bàn Ăn Gỗ Cao Su Tự Nhiên MOHO VLINE 601 90cm', 'Kích thước: Dài 90cm x Rộng 75cm x Cao 65cm\r\n\r\nChất liệu: Gỗ cao su tự nhiên\r\n\r\nChống thấm, cong vênh, trầy xước, mối mọt', 2990000.00, 2199000.00, NULL, '2025-06-03 06:39:44', 'pro_nau_noi_that_moho_ban_an_go_vline_1_2070a5751a624ebcb4ff1a0f726b74c2_master.jpg,pro_nau_noi_that_moho_ban_an_go_vlne_3_30e8339ae5484bc080f98197a7bb3bc8_master.webp'),
(105, 8, 'Bộ Bàn Ăn 4 Ghế - 6 Ghế Gỗ Tự Nhiên SCANIA (Màu Nâu - Mặt Vân Đá)', 'Kích thước: \r\n\r\n- Bàn Ăn: Chiều dài 140cm x Chiều rộng 70cm x Chiều cao 75cm \r\n\r\n- Ghế Ăn: Chiều dài 45cm x Chiều rộng 50cm x Chiều cao 79cm\r\n\r\nChất liệu:\r\n\r\n- Mặt bàn: Gỗ công nghiệp phủ Melamine vân đá\r\n\r\n- Chân bàn: Gỗ cao su tự nhiên\r\n\r\n- Ghế ăn: Gỗ cao su tự nhiên, vải bọc chống thấm nước, chống nhăn, kháng bụi bẩn nấm mốc\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe\r\n\r\nChống thấm, cong vênh, trầy xước, mối mọt', 18830000.00, 12790000.00, NULL, '2025-06-03 06:40:58', 'pro_bo_ban_an_6_ghe_noi_that_moho_combo___2__bc8702bdcaff4e4292b22db58e646cc0_master.webp,pro_bo_ban_an_6_ghe_noi_that_moho_combo___1__da6e9ffaa4654bf4a48d83cd5c7cfbd2_master.webp'),
(106, 8, 'Bàn Ăn Gỗ MOHO UBEDA 201', 'Kích thước: Dài 1m2 X Rộng 75 X Cao 75 (cm)\r\n\r\n \r\n\r\nChất liệu chính: Gỗ cao su và gỗ MFC chuẩn CARB P2 (*) (*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 2590000.00, 0.00, NULL, '2025-06-03 06:42:06', 'pro_mau_tu_nhien_noi_that_moho_ban_an_go_ubeda___7__d1746e34666145c8921430152c992ffd_master.jpg,pro_mau_tu_nhien_noi_that_moho_ban_an_go_ubeda___1__c6a8a230fe514718a45c050e0f529ed8_master.webp'),
(107, 9, 'Tủ Giày - Tủ Trang Trí Gỗ MOHO VLINE 601', 'Màu Tự Nhiên\r\nKích thước: Dài 80cm x Rộng 41cm x Cao 75cm\r\n\r\nChất liệu:\r\n\r\n- Thân tủ: Gỗ công nghiệp PB chuẩn CARB-P2 (*), Sơn phủ Melamine, Veneer gỗ sồi tự nhiên\r\n\r\n- Chân tủ: Gỗ cao su tự nhiên\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 3890000.00, 2990000.00, NULL, '2025-06-03 06:44:20', 'pro_mau_tu_nhien_noi_that_moho_tu_giay_vline_601_2_c8bf1133b8ae450fb7f283c1b561f169_master.jpg,pro_mau_tu_nhien_noi_that_moho_tu_giay_vline_601_66124fd051e24faa9d0de1521365691c_master.webp'),
(108, 9, 'Tủ Giày – Tủ Trang Trí Gỗ MOHO OSLO 901', 'Kích thước: Dài 80cm x Rộng 36cm x Cao 92cm\r\n\r\nChất liệu:\r\n\r\n- Mặt tủ: Gỗ công nghiệp PB chuẩn CARB-P2 (*), Sơn phủ Melamine\r\n\r\n- Thân tủ: Gỗ công nghiệp MDF chuẩn CARB-P2 (*), Sơn phủ Melamine\r\n\r\n- Chân tủ: Gỗ cao su tự nhiên\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 3490000.00, 0.00, NULL, '2025-06-03 06:45:07', 'pro_nau_noi_that_moho_tu_giay_oslo_4_73f44ecda3b44e44928df3b9c0d1e88a_master.webp,pro_nau_noi_that_moho_tu_giay_oslo_2_f4a5582392894355ad021e266921143d_master.webp'),
(109, 9, 'Tủ Giày – Tủ Trang Trí Gỗ MOHO VIENNA', 'Kích thước: Dài 80cm x Rộng 32cm x Cao 100cm\r\n\r\nChất liệu:\r\n\r\n- Mặt tủ: Gỗ công nghiệp PB chuẩn CARB-P2 (*), Sơn phủ Melamine\r\n\r\n- Thân tủ: Gỗ công nghiệp MDF chuẩn CARB-P2 (*), Sơn phủ Melamine\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 4490000.00, 0.00, NULL, '2025-06-03 06:46:31', 'pro_go_phoi_trang_noi_that_moho_tu_giay_trang_tri_vienna_204_9_b9e1762b3b104e2b95e3297cb6bd83da_master.webp,pro_go_phoi_trang_noi_that_moho_tu_giay_trang_tri_vienna_204_878b1deccc9c409481404d310e8fb045_master.webp'),
(110, 9, 'Tủ Giày – Tủ Trang Trí Gỗ MOHO VIENNA 202', 'Kích thước: Dài 60cm x Rộng 32cm x Cao 180cm\r\n\r\nChất liệu:\r\n\r\n- Mặt tủ: Gỗ công nghiệp PB chuẩn CARB-P2 (*), Sơn phủ Melamine\r\n\r\n- Thân tủ: Gỗ công nghiệp MDF chuẩn CARB-P2 (*), Sơn phủ Melamine\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe\r\n\r\n ', 3990000.00, 0.00, NULL, '2025-06-03 06:47:32', 'pro_nau_noi_that_moho_tu_giay_trang_tri_vienna_202_3_31e18f534725412cb3befb1d45a62775_master.jpg,pro_nau_noi_that_moho_tu_giay_trang_tri_vienna_202_1_1d1568b75fee44d5af7a0dc4d862dea7_master.jpg'),
(111, 11, 'Ghế Ăn Gỗ Cao Su Tự Nhiên MOHO VLINE 601', 'Kích thước:\r\n\r\n- MOHO Signature: Dài 50cm x Rộng 56cm x Cao đến đệm ngồi/lưng tựa 37cm/70cm\r\n\r\n- Cao thông thường: Dài 50cm x Rộng 56cm x Cao đến đệm ngồi/lưng tựa 42cm/80cm\r\n\r\nVới sản phẩm MOHO Signature, khách hàng chỉ có thể kết hợp với bàn ăn V-line 601. Với sản phẩm cao thông thường, khách hàng có thể ngồi với bất kì loại bàn ăn nào MOHO hiện có.\r\n\r\nChất liệu: \r\n\r\n- Gỗ cao su tự nhiên\r\n\r\n- Vải bọc polyester chống nhăn, kháng bụi bẩn, nấm mốc\r\n\r\nChống thấm, cong vênh, trầy xước, mối mọt', 2190000.00, 0.00, NULL, '2025-06-03 06:53:02', 'pro_mau_tu_nhien_noi_that_moho_ghe_an___5__2de4a9714dcb43578e40881eb1de9bc9_master.webp,pro_mau_tu_nhien_noi_that_moho_ghe_an_vline_1_e4a9936f38fe4b67aebf70c35f804e25_master.jpg'),
(112, 11, 'Ghế Ăn Gỗ Cao Su Tự Nhiên MOHO OSLO 601', 'Kích thước: Dài 50cm x Rộng 51cm x Cao đến đệm ngồi/lưng tựa 41cm/81cm\r\n\r\nChất liệu:\r\n\r\n- Gỗ cao su tự nhiên\r\n\r\n- Vải bọc polyester chống nhăn, kháng bụi bẩn và nấm mốc\r\n\r\nChống thấm, cong vênh, trầy xước, mối mọt', 1990000.00, 1490000.00, NULL, '2025-06-03 06:55:37', 'pro_nau_noi_that_moho_ghe_an_oslo_dem_xam_6_36acda7243a3467dbb942e97778700a3_master.webp,pro_nau_noi_that_moho_ghe_an_oslo_dem_xam_2_f5b0642e92364d8fad2406ea45711404_master.jpg'),
(113, 11, 'Ghế Ăn Gỗ Cao Su Tự Nhiên MOHO FYN', 'Kích thước: Dài 45cm x Rộng 50cm x Cao đến đệm ngồi/lưng tựa: 45cm/79cm\r\n\r\nChất liệu: Gỗ cao su tự nhiên vải bọc chống thấm nước - chống nhăn, kháng bụi bẩn, nấm mốc\r\n\r\nChống thấm, cong vênh, trầy xước, mối mọt', 2190000.00, 0.00, NULL, '2025-06-03 06:56:33', 'pro_nau_noi_that_moho_ghe_an_go_fyn_6_579b7e7ecea24c9d83b41eae8855d946_95b4fc2bfe1e477597cd8e7e6ae75a06_master.jpg,pro_nau_noi_that_moho_ghe_an_go_fyn_1_ce95b219df3e41cca26fcc19204802e5_master.jpg'),
(114, 11, 'Ghế Ăn Gỗ Cao Su Tự Nhiên MOHO MILAN 601 Màu Gỗ', 'Kích thước: Dài 52cm x Rộng 49cm x Cao đến đệm ngồi/lưng tựa 46cm/74cm\r\n\r\nChất liệu:\r\n\r\n- Gỗ cao su tự nhiên\r\n\r\n- Vải bọc polyester chống nhăn, kháng bụi bẩn và nấm mốc\r\n\r\nChống thấm, cong vênh, trầy xước, mối mọt', 1990000.00, 0.00, NULL, '2025-06-03 06:57:34', 'pro_mau_tu_nhien_noi_that_moho_ghe___3__33dca849599642b89093927e1555bc26_master.webp,pro_mau_tu_nhien_noi_that_moho_ghe___10__d8106284ebae47bb84fad597231816cb_master.webp'),
(115, 15, 'Giường Vải MOHO NARVIK 201 1m6 2 Màu', 'Kích thước: Dài 165 x Rộng 205 x Cao 85 (cm)\r\n\r\nChất liệu chính: Gỗ MFC và plywood chuẩn CARB P2 (*)\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 5990000.00, 0.00, NULL, '2025-06-03 07:00:22', 'pro_mau_tu_nhien_noi_that_moho_giuong_narvik_1m6__7__4e16ec3523e242478c614bf35dcc1e44_master.jpg,pro_noi_that_moho_mau_tu_nhien_giuong_ngu_go_narvik_2_a7590f871e0a49238c39da4705fcf5f8_master.webp'),
(116, 15, 'Giường Vải Tràm MOHO DALUMD 301 Ver 2 Màu Nâu Hạnh Nhân', 'Kích thước phủ bì:\r\n\r\n- Dài 209 x Rộng 167/ 187 x Cao 90 cm (phù hợp với nệm 160/ 180 x 200 cm)\r\n- Gầm giường cao: 14 cm\r\nChất liệu:\r\n\r\n- Gỗ tràm tự nhiên/ Veneer tràm\r\n- Gỗ công nghiệp phủ Melamine & plywood chuẩn CARB-P2 (*)\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe\r\n\r\n ', 9990000.00, 8290000.00, NULL, '2025-06-03 07:02:08', 'pro_nau_noi_that_moho_giuong_ngu_dalumd_2_1b3fba045dfe48cd8514bba2ddcb95b6_master.jpg,pro_nau_noi_that_moho_giuong_ngu_dalumd_270a004a3fd04a96aa21728633bdb6c0_master.webp'),
(117, 15, 'Giường Vải MOHO HOBRO 301', 'Kích thước phủ bì:\r\n\r\n- Dài 210cm x Rộng 171/191cm\r\n\r\n- Cao đến đầu giường 90 cm\r\n\r\n- Gầm giường cao 16cm\r\n\r\nChất liệu:\r\n\r\n- Thân giường: Gỗ tràm tự nhiên/ MDF veneer tràm\r\n\r\n- Tấm phản: Gỗ plywood chuẩn CARB-P2 (*)\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 11990000.00, 9490000.00, NULL, '2025-06-03 07:03:43', 'pro_nau_noi_that_moho_giuong_ngu_go_hobro_c946f9fbdef1414cb2774847f0e83c2e_master.webp,pro_nau_noi_that_moho_giuong_ngu_go_hobro_ef1dcd9b065f4f579919b1f897acbf68_master.jpg'),
(118, 15, 'Giường Vải MOHO MALAGA 301 V2', 'Kích thước phủ bì:\r\n\r\n- Dài 206cm x Rộng 186cm (Phù hợp với nệm 180cm x 2m)\r\n\r\n- Cao đến đầu giường 95cm\r\n\r\nKhoảng trống đầu giường cao 17cm\r\n\r\nChất liệu:\r\n\r\n- Thân giường: Gỗ tràm tự nhiên\r\n\r\n- Tấm phản: Gỗ plywood chuẩn CARB-P2 (*)\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 9990000.00, 0.00, NULL, '2025-06-03 07:05:00', 'pro_nau_noi_that_moho_giuong_ngu_go_malaga_301_1m6_2_3e3410f2038b4e0f8e4780a2e646024a_master.webp,pro_nau_noi_that_moho_giuong_ngu_go_malaga_301_1m6_982931c1cf7e47eb9de385e5f6cd92b4_master.webp'),
(119, 16, 'Nệm Cao Su Nhân Tạo Mát Lạnh TATANA', 'Kích thước	\r\n160×200cm\r\n\r\n180×200cm\r\n\r\nĐộ dày	\r\n 15cm ; 20cm\r\n\r\nLoại nệm	\r\nNệm cao su tự nhiên \r\n\r\nBảo hành	10 năm', 7870000.00, 5903000.00, NULL, '2025-06-03 07:10:28', 'pro_nem_cao_su_tatana__6__d099e8905b364973af8a1d6d4be3a3bb_master.webp,pro_nem_cao_su_tatana_75cfcf32a63b4a9ca9af1b0e1b2c6ca7_master.webp'),
(120, 16, 'Nệm Cao Su Thiên Nhiên TATANA COOL GREY', 'Kích thước	\r\n160×200cm\r\n\r\n180×200cm\r\n\r\nĐộ dày	\r\n 10cm; 15cm\r\n\r\nLoại nệm	\r\nNệm cao su thiên nhiên; than hoạt tính \r\n\r\nBảo hành	10 năm', 14270000.00, 0.00, NULL, '2025-06-03 07:11:25', 'pro_nem_cao_su_thien_nhien_than_hoat_ttinh_tatana_cool_grey_1f3993bddc254b2e9cef3f0a829337f3_master.webp'),
(121, 16, 'Nệm Lò Xo Túi TATANA HANA LUXURY', 'Kích thước	\r\n160×200cm\r\n\r\n180×200cm\r\n\r\nPhân loại	\r\nNệm lò xo túi\r\n\r\nĐộ dày	\r\n 28cm\r\n\r\nBảo hành	10 năm', 10910000.00, 8990000.00, NULL, '2025-06-03 07:12:31', 'pro_nem_lo_xo_tui_tatana_hana_luxury_3_9ac4ffbd81e246f68a5e5b66006dab13_master.webp,pro_nem_lo_xo_tui_tatana_hana_luxury_2cbd354b80924badb67b5632bb801d1a_master.webp'),
(122, 16, 'Nệm lò xo Dunlopillo Audrey Premium', 'Kích thước	\r\n160×200 cm\r\n\r\n180×200 cm\r\n\r\nĐộ dày	\r\n25cm\r\n\r\nCấu tạo các lớp	Lò xo túi 1 sợi – NORMABLOCK\r\nChứng chỉ	ISO 9001:2008, ISO 9001:2015, ISPA – Mỹ, OEKO – TEX – Thụy Sĩ, THE BRAND LAUREATE\r\nCông nghệ	\r\nCHỐNG TĨNH ĐIỆN, Công nghệ NORMABLOCK, Công nghệ SILURAN', 11712000.00, 9990000.00, NULL, '2025-06-03 07:13:59', 'pro_nem_lo_xo_dunlopillo_audrey_premium_e122c683e94546d4affe82e981a080b6_master.jpg,pro_nem_lo_xo_dunlopillo_audrey_premium_1_2d593f3314d94e21873bd5e57312533c_master.webp'),
(123, 17, 'Ghế Văn Phòng Tay Gập Thông Minh MOHO RIGA 701', 'Kích thước: Dài 52cm x Rộng 65cm x Cao 94-101cm\r\n\r\nChất liệu:\r\n\r\n- Khung ghế: nhựa cao cấp\r\n\r\nTựa lưng và nệm ghế: vải lưới mềm mại thoáng khí\r\n*Sản phẩm của chương trình Outlet không áp dụng bảo hành và bảo trì.', 1690000.00, 0.00, NULL, '2025-06-03 07:16:28', 'pro_den_noi_that_moho_ghe_van_phong_riga_3_4722911408574dbf8513ef8d4aeefe0e_master.webp,pro_den_noi_that_moho_ghe_van_phong_riga_1_36e7043208564abbb45f8bd6f68f998d_master.jpg'),
(124, 17, 'Ghế Xoay Văn Phòng Ngả Lưng MOHO JEFE 701', 'Kích thước: Dài 47cm x Rộng 65cm x Cao 108-126cm\r\n\r\nChất liệu:\r\n\r\n- Khung ghế: nhựa cao cấp\r\n\r\n- Tựa lưng và nệm ghế: vải lưới mềm mại thoáng khí\r\n\r\n- Chân xoay: inox\r\n\r\nGhế có yếu tố công thái học - Ergonomic\r\n\r\n*Sản phẩm của chương trình Outlet không áp dụng bảo hành và bảo trì.', 2990000.00, 1999000.00, NULL, '2025-06-03 07:17:40', 'pro_xam_noi_that_moho_ghe_van_phong_jefe_1_48604ad742f84db3b64168507767a9d0_master.webp,pro_xam_noi_that_moho_ghe_van_phong_jefe_2_64ce1e0a7a75460b98b3fe07f064a5f8_master.webp'),
(125, 17, 'Ghế Văn Phòng Chân Xoay MOHO MAJOR 701', 'Kích thước: Dài 56cm x Rộng 42cm x Cao 92-106cm\r\n\r\nChất liệu:\r\n\r\n- Khung ghế: nhựa cao cấp\r\n\r\n- Tựa lưng và nệm ghế: vải lưới mềm mại thoáng khí\r\n\r\nChân ghế: chân xoay inox cao cấp\r\n*Sản phẩm của chương trình Outlet không áp dụng bảo hành và bảo trì.', 1890000.00, 1399000.00, NULL, '2025-06-03 07:18:48', 'pro_xam_noi_that_moho_ghe_van_phong_major_1_c42a19b3b8634ec09327363bb4491bfa_master.jpg,pro_xam_noi_that_moho_ghe_van_phong_major_2_22aa5d7ffceb4374afcf0ce37cb3f141_master.webp'),
(126, 17, 'Ghế Văn Phòng Chân Quỳ MOHO CALLOSO 701', 'Kích thước: Dài 50cm x Rộng 47,5cm x Cao 91cm\r\n\r\nChất liệu:\r\n\r\n- Khung ghế: nhựa cao cấp \r\n\r\n- Tựa lưng và nệm ghế: vải lưới mềm mại thoáng khí \r\n\r\n- Chân ghế: thép sơn tĩnh điện', 1390000.00, 0.00, NULL, '2025-06-03 07:19:40', 'pro_den_noi_that_moho_ghe_van_phong_chan_quy_1_e2e0f1d592c34753b5c755af1444ecc2_master.jpg,pro_den_noi_that_moho_ghe_van_phong_chan_quy_2_847c0e08233e4c02bb7aef01493dacfe_master.webp'),
(127, 18, 'Bàn văn phòng Gỗ MOHO VLINE 601 Màu Nâu', 'Kích thước: Dài 110cm x Rộng 55cm x Cao 74cm\r\n\r\nChất liệu:\r\n\r\n- Mặt bàn: Gỗ công nghiệp MDF chuẩn CARB-P2 (*), Veneer gỗ tràm tự nhiên\r\n\r\n- Chân bàn: Gỗ tràm tự nhiên\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 2990000.00, 0.00, NULL, '2025-06-03 07:21:17', 'pro_nau_noi_that_moho_ban_lam_viec_vline_601_45d5b92f9a2b464e8382610013e531d7_master.webp,pro_nau_noi_that_moho_ban_lam_viec_vline_601_a_e60e2f8b72854311ae12424eed3cb88a_master.webp'),
(128, 18, 'Bàn văn phòng Gỗ MOHO VLINE 601 Màu Tự Nhiên', 'Kích thước: Dài 110cm x Rộng 55cm x Cao 74cm\r\n\r\nChất liệu:\r\n\r\n- Mặt bàn: Gỗ công nghiệp PB chuẩn CARB-P2 (*), Veneer gỗ sồi tự nhiên\r\n\r\n- Chân bàn: Gỗ cao su tự nhiên\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 2990000.00, 0.00, NULL, '2025-06-03 07:22:35', 'pro_mau_tu_nhien_noi_that_moho_ban_lam_viec_vline_1_92060b73c393469181d9d24218c38851_master.webp,pro_mau_tu_nhien_noi_that_moho_ban_lam_viec_vline_5_70a8ab5d00c6463ea5eee815b34800b3_master.webp'),
(129, 18, 'Bàn văn phòng Gỗ Có Kệ MOHO VLINE 602 Màu Tự Nhiên', 'Kích thước:Dài 116cm x Rộng 30/51cm x Cao 74/136cm \r\n\r\nChất liệu:\r\n\r\n- Mặt bàn: Gỗ công nghiệp MDF chuẩn CARB-P2 (*)\r\n\r\n- Chân bàn: Gỗ cao su tự nhiên\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 2990000.00, 1699000.00, NULL, '2025-06-03 07:23:50', 'pro_mau_tu_nhien_ban_lam_viec_go_co_ke_vline_5_060b7838dfe1480a93f886abaf067bc5_master.jpg,pro_mau_tu_nhien_ban_lam_viec_go_co_ke_vline_4_b433c72c14024fb4b5929c24ce13509e_master.webp'),
(130, 18, 'Bàn văn phòng Gỗ MOHO WORKS 701', 'Kích thước: Dài 120/140cm x Rộng 60cm x Cao 72cm \r\n\r\nTrọng lượng chịu tải: 50~70 kg, tối đa 100kg khi phân phối đều khối lượng trên mặt bàn.\r\n\r\nChất liệu:\r\n\r\n- Mặt bàn: Gỗ công nghiệp MFC cao cấp chuẩn CARB-P2 (*)\r\n\r\n- Chân bàn: Chân sắt sơn tĩnh điện\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe\r\n\r\n*Sản phẩm của chương trình Outlet  không áp dụng bảo hành và bảo trì.', 2490000.00, 0.00, NULL, '2025-06-03 07:30:12', 'pro_trang_noi_that_moho_ban_lam_viec_work_701_1m4_5_5defdced630e4f2e9016abe77a1ea1e5_master.webp,pro_trang_noi_that_moho_ban_lam_viec_work_701_1m4_7_23b1a861fece43e3a8649de1f799eb2d_master.webp'),
(131, 19, 'Hộc Tủ văn phòng 3 Ngăn Lưu Trữ Tài Liệu Có Khóa MOHO WORKS 201', 'Kích thước: Dài 35cm x Rộng 42cm x Cao 63cm \r\n\r\nChất liệu: Gỗ công nghiệp MFC/ MDF cao cấp chuẩn CARB-P2 (*)\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 1890000.00, 0.00, NULL, '2025-06-03 07:31:42', 'pro_den_noi_that_moho_hoc_tu_luu_tru_4_75db774fc7a64955a12823efcc286cef_master.webp,pro_den_noi_that_moho_hoc_tu_luu_tru_1_a1957b7e4e7f4fedadc622910fcfca87_master.webp'),
(132, 5, 'SOFA DA MINOTTI', 'Kích thước:\r\n\r\nSofa Văng (3500 x 950-1700 x 730) mm\r\nChất liệu:\r\n\r\nDa công nghiệp: Đang trưng bày, với độ bền cao và khả năng chống thấm nước, dễ bảo trì và làm sạch.', 36500000.00, 0.00, NULL, '2025-06-03 07:42:09', 'sofa-dv126s_1__2dfab64e98dc49db9b1ff300444eca3f.jpg'),
(133, 5, 'SOFA DA HULK', 'Kích thước:\r\n\r\nSofa Văng 1: 3200 x 1100 x 700 mm\r\nDa công nghiệp cao cấp: Mẫu sofa đang trưng bày hiện sử dụng da công nghiệp chất lượng cao, mang lại vẻ đẹp hiện đại và bền bỉ, dễ dàng bảo trì. Đây là sự lựa chọn phổ biến cho các không gian sống đòi hỏi tính thẩm mỹ và tiện ích.\r\nLựa chọn chất liệu tùy chỉnh: Khi đặt sản xuất mới, khách hàng có thể lựa chọn từ nhiều chất liệu khác nhau để phù hợp với nhu cầu và sở thích cá nhân, bao gồm:\r\nDa công nghiệp: Với hơn 100 màu sắc đa dạng, da công nghiệp là lựa chọn linh hoạt và dễ dàng bảo dưỡng, phù hợp với nhiều phong cách nội thất.\r\nNỉ: Mang lại cảm giác ấm cúng và mềm mại, chất liệu nỉ thích hợp cho không gian gia đình.\r\nMirofiber: Chất liệu bền bỉ và dễ vệ sinh, Mirofiber thích hợp cho những gia đình có trẻ nhỏ hoặc nuôi thú cưng.\r\nCarola: Với độ bền cao và khả năng chống thấm nước, Carola là lựa chọn hoàn hảo cho những người yêu thích sự tiện lợi và sang trọng.\r\nDa bò Italia: Lựa chọn cao cấp nhất với hơn 30 màu sắc, da bò thật từ Italia mang đến sự sang trọng, mềm mại và độ bền vượt trội.\r\n', 14000000.00, 0.00, NULL, '2025-06-03 07:44:42', 'sofa-da-_dv107s_2__037b5a7a0e944cceaf9413977dca8cb4.webp,sofa-da-_dv107s_1__f610988e289c42f3a1cfc0711c8794e5.webp'),
(134, 5, 'SOFA DA RUBY', 'Kích thước:\r\n\r\nSofa Văng: 2600 x 1000 x 800 mm\r\nDa công nghiệp cao cấp: Hiện đang trưng bày mẫu da công nghiệp, mang lại vẻ đẹp sang trọng, độ bền cao, và dễ dàng bảo trì. Đây là lựa chọn lý tưởng cho không gian nội thất hiện đại và tiện nghi.\r\nLựa chọn chất liệu tùy chỉnh: Khi đặt sản xuất mới, bạn có thể lựa chọn chất liệu theo nhu cầu và sở thích, bao gồm:\r\nDa công nghiệp: Chất liệu phổ biến với hơn 100 màu sắc đa dạng, mang lại sự linh hoạt trong thiết kế và dễ dàng bảo dưỡng.\r\nNỉ: Mang đến cảm giác ấm áp và thoải mái, phù hợp với không gian sống hiện đại.\r\nMirofiber: Một lựa chọn bền bỉ, mềm mại và dễ bảo quản, thích hợp cho gia đình có trẻ nhỏ hoặc thú cưng.\r\nCarola: Chất liệu cao cấp với độ bền cao, chống thấm nước và chống bám bụi, tạo sự tiện lợi trong quá trình sử dụng.\r\nDa bò Italia: Lựa chọn tối ưu cho sự sang trọng và đẳng cấp. Với hơn 30 màu sắc đa dạng, da bò thật Italia mang đến sự mềm mại, bền bỉ và vẻ đẹp tự nhiên.', 12500000.00, 0.00, NULL, '2025-06-03 07:48:49', 'dv118s_2e8fb5155e8348a4b1df5f6566d486de.webp,dv118s_3__c70fd94487ee43de8155753ec6551037.webp'),
(135, 5, 'SOFA DA LABONI', 'Kích thước:\r\n\r\nSofa Văng (2800 x 950-1800 x 730) mm (chỗ cong ra 1m8)\r\nChất liệu:\r\n\r\nDa công nghiệp: Đang trưng bày, với độ bền cao và khả năng chống thấm nước, dễ bảo trì và làm sạch.\r\nTùy chọn sản xuất mới:\r\n\r\nDa công nghiệp: Kết hợp giữa giá cả phải chăng và chất lượng bền bỉ, lý tưởng cho không gian sinh hoạt hàng ngày.\r\nNỉ: Mang lại sự mềm mại và sang trọng, tạo cảm giác ấm cúng cho không gian nội thất.\r\nMirofiber: Chất liệu cao cấp với khả năng chống bám bẩn, giữ màu sắc lâu bền và dễ bảo trì.\r\nCarola: Tạo nên sự sang trọng và bền bỉ, phù hợp với các thiết kế nội thất hiện đại.\r\nDa bò Italia: Được chọn lọc kỹ lưỡng với nhiều màu sắc tự nhiên, mang đến vẻ đẹp tinh tế và độ bền vượt trội.\r\nMàu sắc da đa dạng:\r\n\r\nDa bò thật cao cấp Italia: Hơn 30 màu sắc sang trọng, từ các tông màu cổ điển đến hiện đại, cho phép bạn dễ dàng lựa chọn phù hợp với phong cách của không gian sống.\r\nDa công nghiệp/Nỉ/Mirofiber/Carola: Hơn 100 màu sắc đa dạng, giúp bạn tùy chỉnh sản phẩm theo sở thích cá nhân và phong cách nội thất.', 19000000.00, 17000000.00, NULL, '2025-06-03 07:50:12', 'sofa-dv125s_2__9b692d4752854919b7e91e942c1222da_compact.webp,sofa-dv125s_3__3d8d87ac6f764844affb3eb6687e0c9a_compact.webp'),
(136, 12, 'Ghế thư giãn bập bênh DPATG1', 'Mô tả sản phẩm: \r\n\r\nTên sản phẩm: Ghế thư giãn bập bênh DPATG1\r\n\r\nMã sản phẩm: DPATG1\r\n\r\nKích thước ghế: 160*60*86cm\r\n\r\nKích thước bàn: 48x48x48cm\r\n\r\nMàu sắc: Màu nâu nhạt\r\n\r\nChất liệu: nhôm đúc sơn tĩnh điện, Lưới Textilen\r\n\r\nXuất xứ: Hàng nhập khẩu 100%', 3850000.00, 0.00, NULL, '2025-06-03 07:52:47', 'ghe-thu-gian-_dpatg1_1__b58a60bbf481458484cb2774dec72210.jpg'),
(137, 12, 'Ghế Thư Giãn Ngoài Trời BARCELONA Xám Nhạt', '\r\nSản phẩm	Ghế bành ngoài trời\r\nBộ sưu tập	BARCELONA\r\nKích cỡ	L71xW67xH65\r\nMàu sắc	Matte-gray\r\nChất liệu	Nhôm\r\nXuất xứ	Việt Nam\r\nĐơn vị	PCS', 2245000.00, 0.00, NULL, '2025-06-03 07:57:52', 'barcelona_outdoor_armchair_baya_2001856_2_0641a03f06d741578a76c1e930d9289d_master.webp,barcelona_outdoor_armchair_baya_2001856_76517a5e11554e788aae233af792e68f_master.webp'),
(138, 13, 'Ghế bar cao 60cm mặt gỗ tròn GBSK025', 'Hướng dẫn sử dụng: Ghế bar, ghế bàn đứng cao\r\nChất liệu: Khung chân sắt, mặt ghế gỗ\r\nKích thước (DxRxC): 39x39x60 cm (đường kính măt gỗ 33cm)\r\nMàu sắc: Màu nâu - Khung chân màu trắng/đen\r\nBảo hành: 12 Tháng', 650000.00, 0.00, NULL, '2025-06-03 08:02:01', 'ef5903ea3da6471ea8becffe9b746830-tplv-o3syd03w52-origin-jpeg-jpeg.webp'),
(139, 14, 'Giường Gỗ Tự Nhiên Mây Mắt Cáo MOHO FIJI 401', 'Kích thước phủ bì: Dài 210cm x Rộng 167/187cm x Cao 90cm\r\n\r\nChất liệu:\r\n\r\n- Đầu giường: Gỗ tràm tự nhiên và mây mắt cáo tự nhiên\r\n\r\n- Thân giường: Gỗ tràm tự nhiên và MDF veneer tràm chuẩn CARB-P2\r\n\r\n- Chân giường: Gỗ tràm tự nhiên\r\n\r\n- Tấm phản: Gỗ plywood chuẩn CARB-P2 (*)\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 12490000.00, 7290000.00, NULL, '2025-06-03 08:16:12', 'pro_1m6_noi_that_moho_giuong_ngu_fiji_b_cae43605b18e4e6a961709059b8d03f6_master.webp'),
(140, 14, 'Giường Gỗ MOHO VLINE 601', 'Kích thước phủ bì: Dài 212cm x Rộng 136/156/176/196cm x Cao đến đầu giường 92cm\r\n\r\nChất liệu:\r\n\r\n- Thân giường: Gỗ tràm tự nhiên, Veneer gỗ sồi tự nhiên\r\n\r\n- Chân giường: Gỗ cao su tự nhiên\r\n\r\n- Thanh vạt/ Tấm phản: Gỗ plywood chuẩn CARB-P2 (*)\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 8990000.00, 0.00, NULL, '2025-06-03 08:17:52', 'pro_mau_tu_nhien_noi_that_moho_giuong_ngu_go_soi_vline_601_4_b9aa87403dcd46259c3d98c492f3e731_master.jpg,pro_mau_tu_nhien_noi_that_moho_giuong_ngu_go_soi_vline_601_8cdf668db13644e8985bc0873c64ddbc_master.webp'),
(141, 14, 'Giường gỗ Có Hộc & Ổ Điện', 'Kích thước: Rộng 160/180 x Dài 218 x Cao 100 (cm)\r\n\r\nChất liệu: Gỗ công nghiệp phủ Melamine CARB-P2 (*)\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe người dùng và thân thiện với môi trường.', 12990000.00, 7990000.00, NULL, '2025-06-03 08:19:17', 'pro_trang_noi_that_moho_giuong_co_hoc_vienna_1m6_1_ede5117be7cd4ad3a745fedd914a67bc_master.jpg,pro_trang_noi_that_moho_giuong_co_hoc_vienna_1m6_2_ef7e4658b1da42509df1200386234c2a_master.webp'),
(142, 14, 'Giường Gỗ Có Hộc', 'Kích thước: Dài 204 x Rộng 161 x Cao 80 cm (Phù hợp với nệm 1m6 x 2m)\r\n\r\nKèm 2 hộc \r\n\r\nChất liệu chính: Gỗ MFC/ MDF phủ Melamin chuẩn CARB P2 (*)\r\n\r\n(*) Tiêu chuẩn California Air Resources Board xuất khẩu Mỹ, đảm bảo gỗ không độc hại, an toàn cho sức khỏe', 6490000.00, 0.00, NULL, '2025-06-03 08:21:57', 'pro_nau_noi_that_moho_giuong_co_hoc_grenaa_3_126d3aa4b7d549d199ed39ce0161750e_master.webp,pro_nau_noi_that_moho_giuong_co_hoc_grenaa_5_31a6c08f83154890962a858057558660_master.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shipping`
--

CREATE TABLE `shipping` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `id_province` int(11) DEFAULT NULL,
  `id_district` int(11) DEFAULT NULL,
  `name_province` varchar(200) DEFAULT NULL,
  `name_district` varchar(200) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `shipping`
--

INSERT INTO `shipping` (`id`, `name`, `id_province`, `id_district`, `name_province`, `name_district`, `created`) VALUES
(4, 'GHN', 1, 8, 'Thành phố Hà Nội', 'Quận Hoàng Mai', '2025-05-23 03:44:50'),
(5, 'GHN', 1, 7, 'Thành phố Hà Nội', 'Quận Hai Bà Trưng', '2025-06-02 11:44:01'),
(6, 'GHN', 30, 288, 'Tỉnh Hải Dương', 'Thành phố Hải Dương', '2025-06-02 11:44:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `side_menu`
--

CREATE TABLE `side_menu` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `side_menu`
--

INSERT INTO `side_menu` (`id`, `name`, `link`) VALUES
(1, 'Sofa', 'search.php?keyword=sofa'),
(2, 'Bàn & Tủ', 'search.php?keyword=bàn'),
(3, 'Ghế', 'search.php?keyword=ghế'),
(4, 'Giường', 'search.php?keyword=giường'),
(5, 'Văn phòng', 'search.php?keyword=văn phòng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `side_submenu`
--

CREATE TABLE `side_submenu` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `side_submenu`
--

INSERT INTO `side_submenu` (`id`, `parent_id`, `name`, `link`) VALUES
(1, 1, 'Sofa da', 'search.php?keyword=sofa da'),
(2, 1, 'Sofa vải', 'search.php?keyword=sofa vải'),
(3, 1, 'Sofa góc', 'search.php?keyword=sofa góc'),
(4, 2, 'Bàn sofa', 'search.php?keyword=bàn sofa'),
(5, 2, 'Bàn ăn', 'search.php?keyword=bàn ăn'),
(6, 2, 'Tủ giày', 'search.php?keyword=tủ giày'),
(7, 2, 'Tủ trang trí', 'search.php?keyword=tủ trang trí'),
(8, 3, 'Ghế ăn', 'search.php?keyword=ghế ăn'),
(9, 3, 'Ghế thư giãn', 'search.php?keyword=ghế thư giãn'),
(10, 3, 'Ghế quầy bar', 'search.php?keyword=ghế bar'),
(11, 4, 'Giường gỗ', 'search.php?keyword=giường gỗ'),
(12, 4, 'Giường vải', 'search.php?keyword=giường vải'),
(13, 4, 'Nệm', 'search.php?keyword=nệm'),
(14, 5, 'Ghế văn phòng', 'search.php?keyword=ghế văn phòng'),
(15, 5, 'Bàn văn phòng', 'search.php?keyword=bàn văn phòng'),
(16, 5, 'Tủ văn phòng', 'search.php?keyword=tủ văn phòng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `image_link` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `slider`
--

INSERT INTO `slider` (`id`, `name`, `image_link`, `sort_order`, `created`) VALUES
(5, 'Nội thất tinh giản', 'slider3.jpg.webp', 2, '2025-05-30 10:58:20'),
(6, 'Slider1', 'slder1.webp', 1, '2025-06-01 19:18:37'),
(7, 'Slider3', 'slider2.webp', 3, '2025-06-01 19:19:01'),
(8, 'Slider4', 'slider3.webp', 4, '2025-06-01 19:19:42'),
(9, 'Slider5', 'slider5.webp', 5, '2025-06-01 19:20:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `top_menu`
--

CREATE TABLE `top_menu` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `link` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `top_menu`
--

INSERT INTO `top_menu` (`id`, `name`, `link`, `sort_order`) VALUES
(1, 'Trang chủ', 'index.php', 1),
(2, 'Giới thiệu', 'about.php', 2),
(3, 'Dịch Vụ', 'services.php', 3),
(4, 'Tin Tức', 'news.php', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `user_phone` varchar(100) DEFAULT NULL,
  `user_address` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `payment` varchar(32) DEFAULT NULL,
  `created` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `transaction`
--

INSERT INTO `transaction` (`id`, `status`, `user_id`, `user_name`, `user_email`, `user_phone`, `user_address`, `message`, `amount`, `payment`, `created`) VALUES
(51, 0, 13, 'Yến', 'yen123@gmail.com', '0328104038', '123 Hàng Chiếu, Phường Hàng Buồm, Quận Hoàn Kiếm, Thành phố Hà Nội', '', 6550000.00, 'bank_transfer', 1749007028);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `phone`, `address`, `created`) VALUES
(13, 'Yến', 'yen123@gmail.com', '$2y$10$RGX8rAE2.bzWDqidJCEeq.GxgIw5Y6M7QyocYNvKSjKDTo/heJpI2', '0', 'Hà Nội', NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `catalog`
--
ALTER TABLE `catalog`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `comment_reply`
--
ALTER TABLE `comment_reply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `footer_column`
--
ALTER TABLE `footer_column`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `footer_link`
--
ALTER TABLE `footer_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `column_id` (`column_id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `catalog_id` (`catalog_id`);

--
-- Chỉ mục cho bảng `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `side_menu`
--
ALTER TABLE `side_menu`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `side_submenu`
--
ALTER TABLE `side_submenu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Chỉ mục cho bảng `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `top_menu`
--
ALTER TABLE `top_menu`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `catalog`
--
ALTER TABLE `catalog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT cho bảng `comment_reply`
--
ALTER TABLE `comment_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `footer_column`
--
ALTER TABLE `footer_column`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `footer_link`
--
ALTER TABLE `footer_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `models`
--
ALTER TABLE `models`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT cho bảng `shipping`
--
ALTER TABLE `shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `side_menu`
--
ALTER TABLE `side_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `side_submenu`
--
ALTER TABLE `side_submenu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `top_menu`
--
ALTER TABLE `top_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `comment_reply`
--
ALTER TABLE `comment_reply`
  ADD CONSTRAINT `comment_reply_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`),
  ADD CONSTRAINT `comment_reply_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `footer_link`
--
ALTER TABLE `footer_link`
  ADD CONSTRAINT `footer_link_ibfk_1` FOREIGN KEY (`column_id`) REFERENCES `footer_column` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`),
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`);

--
-- Các ràng buộc cho bảng `side_submenu`
--
ALTER TABLE `side_submenu`
  ADD CONSTRAINT `side_submenu_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `side_menu` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
