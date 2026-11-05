-- =====================================================================
--  ฐานข้อมูล: เว็บไซต์ท่องเที่ยวชุมชน "3 สายน้ำ 3 ศาสนา" จ.พระนครศรีอยุธยา
--  ใช้กับ: MySQL 8.0+ / MariaDB 10.6+  ·  Laravel 12 + Livewire 3
--
--  วิธีใช้ (เลือกอย่างใดอย่างหนึ่ง):
--   1) phpMyAdmin → สร้างฐานข้อมูล ayutthaya_3rivers (utf8mb4_unicode_ci) → Import ไฟล์นี้
--   2) คำสั่ง:  mysql -u root -p < ayutthaya_3rivers.sql
--
--  ไฟล์นี้ใส่ข้อมูลในตาราง migrations ไว้แล้ว จึง "ไม่ต้อง" รัน php artisan migrate ซ้ำ
--  (ถ้าต้องการใช้ migrate แทน ให้ข้ามไฟล์นี้แล้วรัน php artisan migrate --seed)
--
--  บัญชีทดสอบ (รหัสผ่าน: password)
--   admin@ecotour.test  (ผู้ดูแลระบบ)
--   user@ecotour.test   (ลูกค้า)
--
--  หมายเหตุ: พิกัดอ้างอิง Wikipedia / OpenStreetMap ควรตรวจซ้ำก่อนเผยแพร่
--            สินค้าเป็นข้อมูลตัวอย่าง ให้แทนที่ด้วยสินค้าจริงของชุมชน
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `ayutthaya_3rivers`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ayutthaya_3rivers`;

SET NAMES utf8mb4;
SET time_zone = '+07:00';
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `order_items`, `orders`, `products`, `product_categories`,
  `attractions`, `communities`, `sessions`, `password_reset_tokens`, `users`,
  `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `migrations`;

-- =====================================================================
--  ส่วนที่ 1: ตารางมาตรฐานของ Laravel
-- =====================================================================

CREATE TABLE `migrations` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch`     INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ผู้ใช้: admin = ผู้ดูแล, member = สมาชิกชุมชน, customer = ลูกค้า
CREATE TABLE `users` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`              VARCHAR(255) NOT NULL,
  `email`             VARCHAR(255) NOT NULL,
  `role`              ENUM('admin','member','customer') NOT NULL DEFAULT 'customer',
  `phone`             VARCHAR(20) NULL,
  `address`           TEXT NULL,
  `email_verified_at` TIMESTAMP NULL,
  `password`          VARCHAR(255) NOT NULL,
  `remember_token`    VARCHAR(100) NULL,
  `created_at`        TIMESTAMP NULL,
  `updated_at`        TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email`      VARCHAR(255) NOT NULL,
  `token`      VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id`            VARCHAR(255) NOT NULL,
  `user_id`       BIGINT UNSIGNED NULL,
  `ip_address`    VARCHAR(45) NULL,
  `user_agent`    TEXT NULL,
  `payload`       LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key`        VARCHAR(255) NOT NULL,
  `value`      MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key`        VARCHAR(255) NOT NULL,
  `owner`      VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue`        VARCHAR(255) NOT NULL,
  `payload`      LONGTEXT NOT NULL,
  `attempts`     TINYINT UNSIGNED NOT NULL,
  `reserved_at`  INT UNSIGNED NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at`   INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id`             VARCHAR(255) NOT NULL,
  `name`           VARCHAR(255) NOT NULL,
  `total_jobs`     INT NOT NULL,
  `pending_jobs`   INT NOT NULL,
  `failed_jobs`    INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options`        MEDIUMTEXT NULL,
  `cancelled_at`   INT NULL,
  `created_at`     INT NOT NULL,
  `finished_at`    INT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid`       VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue`      TEXT NOT NULL,
  `payload`    LONGTEXT NOT NULL,
  `exception`  LONGTEXT NOT NULL,
  `failed_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
--  ส่วนที่ 2: ชุมชนและแหล่งท่องเที่ยว
-- =====================================================================

CREATE TABLE `communities` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(255) NOT NULL                COMMENT 'ชื่อชุมชน',
  `slug`          VARCHAR(255) NOT NULL                COMMENT 'ใช้ใน URL',
  `tagline`       VARCHAR(200) NULL                    COMMENT 'คำโปรย/จุดเด่น',
  `description`   LONGTEXT NULL                        COMMENT 'เรื่องราวชุมชน',
  `conservation`  TEXT NULL                            COMMENT 'งานอนุรักษ์ (บรรทัดละ 1 ข้อ)',
  `address`       VARCHAR(255) NULL,
  `subdistrict`   VARCHAR(100) NULL                    COMMENT 'ตำบล',
  `district`      VARCHAR(100) NULL                    COMMENT 'อำเภอ',
  `province`      VARCHAR(100) NULL                    COMMENT 'จังหวัด',
  `lat`           DECIMAL(10,7) NULL                   COMMENT 'ละติจูด',
  `lng`           DECIMAL(10,7) NULL                   COMMENT 'ลองจิจูด',
  `contact_name`  VARCHAR(255) NULL,
  `contact_phone` VARCHAR(20) NULL,
  `facebook_url`  VARCHAR(255) NULL,
  `line_id`       VARCHAR(50) NULL,
  `cover_image`   VARCHAR(255) NULL                    COMMENT 'path ใน storage/app/public',
  `is_active`     TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`    TIMESTAMP NULL,
  `updated_at`    TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `communities_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ชุมชน / วิสาหกิจชุมชน';

CREATE TABLE `attractions` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `community_id`  BIGINT UNSIGNED NOT NULL,
  `name`          VARCHAR(255) NOT NULL,
  `slug`          VARCHAR(255) NOT NULL,
  `type`          ENUM('nature','culture','learning','food','homestay','viewpoint') NOT NULL DEFAULT 'nature'
                  COMMENT 'ธรรมชาติ/วัฒนธรรม/แหล่งเรียนรู้/อาหาร/ที่พัก/จุดชมวิว',
  `faith`         ENUM('buddhism','christianity','islam') NULL COMMENT 'เส้นทาง 3 ศาสนา',
  `summary`       VARCHAR(300) NULL,
  `description`   LONGTEXT NULL,
  `address`       VARCHAR(255) NULL,
  `lat`           DECIMAL(10,7) NULL                   COMMENT 'ละติจูด',
  `lng`           DECIMAL(10,7) NULL                   COMMENT 'ลองจิจูด',
  `opening_hours` VARCHAR(150) NULL,
  `entry_fee`     VARCHAR(100) NULL,
  `best_time`     VARCHAR(150) NULL,
  `contact_phone` VARCHAR(20) NULL,
  `eco_tips`      TEXT NULL                            COMMENT 'ข้อควรปฏิบัติ (บรรทัดละ 1 ข้อ)',
  `image`         VARCHAR(255) NULL,
  `gallery`       JSON NULL                            COMMENT 'อาร์เรย์ path รูปภาพ',
  `is_featured`   TINYINT(1) NOT NULL DEFAULT 0,
  `is_active`     TINYINT(1) NOT NULL DEFAULT 1,
  `views`         INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`    TIMESTAMP NULL,
  `updated_at`    TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attractions_slug_unique` (`slug`),
  KEY `attractions_community_id_foreign` (`community_id`),
  KEY `attractions_lat_lng_index` (`lat`, `lng`),
  CONSTRAINT `attractions_community_id_foreign` FOREIGN KEY (`community_id`)
    REFERENCES `communities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='แหล่งท่องเที่ยว';

-- =====================================================================
--  ส่วนที่ 3: ร้านค้า
-- =====================================================================

CREATE TABLE `product_categories` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(255) NOT NULL,
  `slug`       VARCHAR(255) NOT NULL,
  `icon`       VARCHAR(10) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `community_id`        BIGINT UNSIGNED NOT NULL,
  `product_category_id` BIGINT UNSIGNED NOT NULL,
  `name`                VARCHAR(255) NOT NULL,
  `slug`                VARCHAR(255) NOT NULL,
  `producer`            VARCHAR(255) NULL           COMMENT 'ผู้ผลิต/กลุ่ม',
  `story`               TEXT NULL                   COMMENT 'เรื่องเล่าผลิตภัณฑ์',
  `description`         TEXT NULL,
  `price`               DECIMAL(10,2) NOT NULL,
  `stock`               INT UNSIGNED NOT NULL DEFAULT 0,
  `unit`                VARCHAR(30) NOT NULL DEFAULT 'ชิ้น',
  `weight_gram`         INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'ใช้คำนวณค่าส่ง',
  `image`               VARCHAR(255) NULL,
  `is_otop`             TINYINT(1) NOT NULL DEFAULT 0,
  `is_active`           TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`          TIMESTAMP NULL,
  `updated_at`          TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_community_id_foreign` (`community_id`),
  KEY `products_product_category_id_foreign` (`product_category_id`),
  CONSTRAINT `products_community_id_foreign` FOREIGN KEY (`community_id`)
    REFERENCES `communities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_product_category_id_foreign` FOREIGN KEY (`product_category_id`)
    REFERENCES `product_categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='สินค้าชุมชน';

CREATE TABLE `orders` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code`           VARCHAR(20) NOT NULL              COMMENT 'เลขที่คำสั่งซื้อ เช่น OD261105ABCD',
  `user_id`        BIGINT UNSIGNED NOT NULL,
  `subtotal`       DECIMAL(10,2) NOT NULL,
  `shipping_fee`   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total`          DECIMAL(10,2) NOT NULL,
  `payment_method` ENUM('promptpay','transfer','cod') NOT NULL DEFAULT 'promptpay',
  `payment_slip`   VARCHAR(255) NULL,
  `status`         ENUM('pending','paid','packing','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
  `ship_name`      VARCHAR(255) NOT NULL,
  `ship_phone`     VARCHAR(20) NOT NULL,
  `ship_address`   TEXT NOT NULL,
  `tracking_no`    VARCHAR(50) NULL,
  `created_at`     TIMESTAMP NULL,
  `updated_at`     TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_code_unique` (`code`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='คำสั่งซื้อ';

CREATE TABLE `order_items` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id`     BIGINT UNSIGNED NOT NULL,
  `product_id`   BIGINT UNSIGNED NULL,
  `product_name` VARCHAR(255) NOT NULL               COMMENT 'ชื่อสินค้า ณ วันที่สั่ง',
  `price`        DECIMAL(10,2) NOT NULL              COMMENT 'ราคา ณ วันที่สั่ง',
  `qty`          INT UNSIGNED NOT NULL,
  `line_total`   DECIMAL(10,2) NOT NULL,
  `created_at`   TIMESTAMP NULL,
  `updated_at`   TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`)
    REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`)
    REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='รายการสินค้าในคำสั่งซื้อ';

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
--  ส่วนที่ 4: ข้อมูลตั้งต้น
-- =====================================================================

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2026_11_05_000001_add_role_to_users_table', 1),
('2026_11_05_000002_create_community_tables', 1),
('2026_11_05_000003_create_shop_tables', 1);

-- รหัสผ่านทั้งสองบัญชีคือ: password
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `password`, `created_at`, `updated_at`) VALUES
(1, 'ผู้ดูแลระบบ',   'admin@ecotour.test', 'admin',    NULL,         '$2y$12$tF3UdDgqV45cLgXtRnvOuOEwlU4aCDXaT6c/VHmBMpQil3JGr6xli', NOW(), NOW()),
(2, 'ลูกค้าทดสอบ',   'user@ecotour.test',  'customer', '0812345678', '$2y$12$tF3UdDgqV45cLgXtRnvOuOEwlU4aCDXaT6c/VHmBMpQil3JGr6xli', NOW(), NOW());

INSERT INTO `communities`
(`id`, `name`, `slug`, `tagline`, `description`, `conservation`, `subdistrict`, `district`, `province`, `lat`, `lng`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ชุมชนสำเภาล่ม', 'samphao-lom', 'พุทธและคริสต์ริมฝั่งเจ้าพระยา',
 'ชุมชนริมแม่น้ำเจ้าพระยาฝั่งใต้ ตรงข้ามเกาะเมืองอยุธยา เป็นที่ตั้งของวัดพุทไธศวรรย์ พระอารามหลวงเก่าแก่สมัยพระเจ้าอู่ทอง และวัดนักบุญยอแซฟ โบสถ์คาทอลิกแห่งแรกของประเทศไทย ศาสนสถานทั้งสองตั้งอยู่ไม่ไกลกัน สะท้อนการอยู่ร่วมกันของผู้คนต่างความเชื่อมาหลายร้อยปี',
 'ร่วมดูแลความสะอาดริมแม่น้ำเจ้าพระยา\nอนุรักษ์โบราณสถานและจิตรกรรมฝาผนัง',
 'สำเภาล่ม', 'พระนครศรีอยุธยา', 'พระนครศรีอยุธยา', 14.3395200, 100.5582300, 1, NOW(), NOW()),
(2, 'ชุมชนคลองตะเคียน', 'khlong-takhian', 'วิถีมุสลิมริมคลองตะเคียน',
 'ชุมชนไทยมุสลิมริมคลองตะเคียน ศูนย์รวมจิตใจคือมัสยิดกุฎีช่อฟ้า หนึ่งในมัสยิดเก่าแก่ของประเทศไทย ผู้คนในชุมชนสืบเชื้อสายมาจากพ่อค้าและข้าราชการที่เข้ามาพึ่งพระบรมโพธิสมภารตั้งแต่สมัยกรุงศรีอยุธยา',
 'รักษาคลองตะเคียนให้สะอาด\nสืบสานอาหารและวัฒนธรรมมุสลิมดั้งเดิม',
 'คลองตะเคียน', 'พระนครศรีอยุธยา', 'พระนครศรีอยุธยา', 14.3238700, 100.5674900, 1, NOW(), NOW()),
(3, 'ย่านบางกะจะ – จุดบรรจบ 3 สายน้ำ', 'bang-kaja', 'จุดที่แม่น้ำ 3 สายมาบรรจบกัน',
 'ย่านการค้าทางน้ำสำคัญตั้งแต่สมัยโบราณ บริเวณหน้าวัดพนัญเชิงวรวิหาร ตรงข้ามป้อมเพชร คือจุดที่แม่น้ำเจ้าพระยา แม่น้ำป่าสัก และแม่น้ำลพบุรี (คลองเมือง) ไหลมาบรรจบกัน แม่น้ำทั้ง 3 สายโอบล้อมอยุธยาจนเป็น "เกาะเมือง" เป็นทั้งเส้นทางการค้าและปราการธรรมชาติป้องกันข้าศึก',
 'ร่วมลดขยะลงแม่น้ำ\nไม่ให้อาหารปลาด้วยขนมปังปริมาณมาก',
 'คลองสวนพลู', 'พระนครศรีอยุธยา', 'พระนครศรีอยุธยา', 14.3441700, 100.5788900, 1, NOW(), NOW());

INSERT INTO `attractions`
(`id`, `community_id`, `name`, `slug`, `type`, `faith`, `summary`, `description`, `address`, `lat`, `lng`, `opening_hours`, `entry_fee`, `eco_tips`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'วัดพุทไธศวรรย์', 'wat-phutthaisawan', 'culture', 'buddhism',
 'พระอารามหลวงเก่าแก่สมัยพระเจ้าอู่ทอง ปรางค์ประธานสีขาวโดดเด่นริมเจ้าพระยา',
 'พระอารามหลวงเก่าแก่ที่สร้างขึ้นในสมัยสมเด็จพระเจ้าอู่ทอง ณ บริเวณเวียงเหล็ก ที่ประทับระหว่างสร้างกรุงศรีอยุธยา\n\nจุดเด่นคือปรางค์ประธานสีขาว และตำหนักสมเด็จพระพุทธโฆษาจารย์ที่มีจิตรกรรมฝาผนังล้ำค่า',
 'ต.สำเภาล่ม อ.พระนครศรีอยุธยา', 14.3395200, 100.5582300, NULL, NULL,
 'แต่งกายสุภาพเมื่อเข้าศาสนสถาน\nเคารพผู้ที่มาประกอบศาสนกิจ งดส่งเสียงดัง\nไม่ทิ้งขยะริมแม่น้ำ', 1, 1, NOW(), NOW()),
(2, 1, 'วัดนักบุญยอแซฟ', 'st-joseph-church', 'culture', 'christianity',
 'โบสถ์คาทอลิกแห่งแรกในประเทศไทย สถาปัตยกรรมสีเหลืองนวลริมน้ำ',
 'โบสถ์คาทอลิกแห่งแรกในประเทศไทย ตั้งแต่สมัยสมเด็จพระนารายณ์มหาราช ที่พระราชทานที่ดินริมแม่น้ำเจ้าพระยาให้คณะมิชชันนารีฝรั่งเศส\n\nตัวโบสถ์ปัจจุบันเป็นสถาปัตยกรรมฟื้นฟูกอทิกสีเหลืองนวลริมน้ำที่งดงาม',
 '30 หมู่ 11 ต.สำเภาล่ม อ.พระนครศรีอยุธยา', 14.3386500, 100.5480300, 'จ.–ส. 08:00–17:00 · อา. 11:00–17:00', 'ไม่เสียค่าเข้าชม',
 'แต่งกายสุภาพเมื่อเข้าศาสนสถาน\nเคารพผู้ที่มาประกอบศาสนกิจ งดส่งเสียงดัง\nไม่ทิ้งขยะริมแม่น้ำ', 1, 1, NOW(), NOW()),
(3, 2, 'มัสยิดกุฎีช่อฟ้า', 'kudi-cho-fa-mosque', 'culture', 'islam',
 'ศูนย์รวมจิตใจของชาวไทยมุสลิม หนึ่งในมัสยิดเก่าแก่ของไทย',
 'ศูนย์รวมจิตใจของชาวไทยมุสลิมในพื้นที่ ซึ่งสืบเชื้อสายมาจากพ่อค้าและข้าราชการชาวเปอร์เซีย/อาหรับ ที่เข้ามาพึ่งพระบรมโพธิสมภารตั้งแต่สมัยกรุงศรีอยุธยา\n\nชื่อ "กุฎีช่อฟ้า" เป็นนามพระราชทานจากพระบาทสมเด็จพระจุลจอมเกล้าเจ้าอยู่หัว รัชกาลที่ 5',
 'หมู่ 4 ต.คลองตะเคียน อ.พระนครศรีอยุธยา', 14.3238700, 100.5674900, NULL, NULL,
 'แต่งกายสุภาพเมื่อเข้าศาสนสถาน\nเคารพผู้ที่มาประกอบศาสนกิจ งดส่งเสียงดัง\nไม่ทิ้งขยะริมแม่น้ำ', 1, 1, NOW(), NOW()),
(4, 3, 'วัดพนัญเชิงวรวิหาร', 'wat-phanan-choeng', 'culture', 'buddhism',
 'วัดริมจุดบรรจบแม่น้ำ 3 สาย ประดิษฐานหลวงพ่อโต (ซำปอกง)',
 'ตั้งอยู่ริมจุดบรรจบของแม่น้ำเจ้าพระยาและแม่น้ำป่าสัก หน้าวัดคือย่านการค้าทางน้ำสำคัญตั้งแต่สมัยโบราณ เป็นที่เคารพของทั้งชาวไทยและชาวไทยเชื้อสายจีน',
 'ต.คลองสวนพลู อ.พระนครศรีอยุธยา', 14.3441700, 100.5788900, 'ทุกวัน 08:00–17:00', NULL,
 'แต่งกายสุภาพเมื่อเข้าศาสนสถาน\nเคารพผู้ที่มาประกอบศาสนกิจ งดส่งเสียงดัง\nไม่ทิ้งขยะริมแม่น้ำ', 1, 1, NOW(), NOW()),
(5, 3, 'ป้อมเพชร', 'pom-phet', 'viewpoint', NULL,
 'ป้อมปราการริมน้ำ จุดชมวิวแม่น้ำ 3 สายบรรจบกัน',
 'ป้อมปราการสำคัญที่สุดของกรุงศรีอยุธยา ตั้งอยู่มุมตะวันออกเฉียงใต้ของเกาะเมือง ตรงข้ามวัดพนัญเชิง ปัจจุบันเป็นสวนสาธารณะริมน้ำ\n\nเป็นจุดที่เหมาะที่สุดในการชมแม่น้ำ 3 สายมาบรรจบกัน โดยเฉพาะช่วงเย็น',
 'ต.หอรัตนไชย อ.พระนครศรีอยุธยา', 14.3461980, 100.5765260, 'เปิดตลอด (พื้นที่สาธารณะ)', 'ไม่เสียค่าเข้าชม',
 'ไม่ทิ้งขยะลงแม่น้ำ\nไม่ปีนป่ายกำแพงโบราณสถาน', 0, 1, NOW(), NOW());

INSERT INTO `product_categories` (`id`, `name`, `slug`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'ของฝาก',               'gift',    '🎁', NOW(), NOW()),
(2, 'อาหารแปรรูป',          'food',    '🍯', NOW(), NOW()),
(3, 'ผ้าและเครื่องแต่งกาย',   'textile', '🧣', NOW(), NOW()),
(4, 'หัตถกรรม',             'craft',   '🧺', NOW(), NOW());

-- สินค้าตัวอย่าง (แก้ไขเป็นสินค้าจริงในหลังบ้าน)
INSERT INTO `products`
(`id`, `community_id`, `product_category_id`, `name`, `slug`, `producer`, `story`, `price`, `stock`, `unit`, `weight_gram`, `is_otop`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 'โรตีสายไหม (ชุดของฝาก)',            'product-1', 'กลุ่มผู้ผลิตในย่านบางกะจะ',  'ข้อมูลตัวอย่าง — แก้ไขเป็นเรื่องราวจริงของผู้ผลิตได้ในหลังบ้าน', 120.00,  50, 'ชุด',  600, 1, 1, NOW(), NOW()),
(2, 2, 2, 'ขนมมุสลิมโบราณ (ตัวอย่าง)',         'product-2', 'กลุ่มผู้ผลิตในชุมชนคลองตะเคียน', 'ข้อมูลตัวอย่าง — แก้ไขเป็นเรื่องราวจริงของผู้ผลิตได้ในหลังบ้าน',  90.00,  30, 'กล่อง', 400, 0, 1, NOW(), NOW()),
(3, 1, 1, 'โปสการ์ดภาพวาด 3 ศาสนา (ชุด 5 ใบ)', 'product-3', 'กลุ่มผู้ผลิตในชุมชนสำเภาล่ม',  'ข้อมูลตัวอย่าง — แก้ไขเป็นเรื่องราวจริงของผู้ผลิตได้ในหลังบ้าน',  79.00, 100, 'ชุด',   50, 0, 1, NOW(), NOW()),
(4, 2, 3, 'ผ้าคลุมผมลายพื้นถิ่น (ตัวอย่าง)',     'product-4', 'กลุ่มผู้ผลิตในชุมชนคลองตะเคียน', 'ข้อมูลตัวอย่าง — แก้ไขเป็นเรื่องราวจริงของผู้ผลิตได้ในหลังบ้าน', 250.00,  20, 'ผืน',  150, 0, 1, NOW(), NOW()),
(5, 1, 4, 'พวงกุญแจเรือสำเภาจิ๋ว',              'product-5', 'กลุ่มผู้ผลิตในชุมชนสำเภาล่ม',  'ข้อมูลตัวอย่าง — แก้ไขเป็นเรื่องราวจริงของผู้ผลิตได้ในหลังบ้าน',  59.00,  80, 'ชิ้น',   30, 0, 1, NOW(), NOW()),
(6, 3, 3, 'เสื้อยืดลาย 3 สายน้ำ',               'product-6', 'กลุ่มผู้ผลิตในย่านบางกะจะ',  'ข้อมูลตัวอย่าง — แก้ไขเป็นเรื่องราวจริงของผู้ผลิตได้ในหลังบ้าน', 290.00,  40, 'ตัว',  250, 1, 1, NOW(), NOW());

-- =====================================================================
--  ส่วนที่ 5: View สำหรับรายงาน (ใช้ใน phpMyAdmin หรือเครื่องมือรายงานได้)
-- =====================================================================

CREATE OR REPLACE VIEW `v_places_map` AS
SELECT a.id, a.name, a.type, a.faith, a.lat, a.lng, c.name AS community,
       CONCAT('https://www.google.com/maps/dir/?api=1&destination=', a.lat, ',', a.lng) AS directions_url,
       a.views, a.is_featured
FROM attractions a
JOIN communities c ON c.id = a.community_id
WHERE a.is_active = 1;

CREATE OR REPLACE VIEW `v_sales_by_community` AS
SELECT c.id AS community_id, c.name AS community,
       COALESCE(s.items_sold, 0) AS items_sold,
       COALESCE(s.revenue, 0)    AS revenue
FROM communities c
LEFT JOIN (
    SELECT p.community_id, SUM(oi.qty) AS items_sold, SUM(oi.line_total) AS revenue
    FROM order_items oi
    JOIN orders o   ON o.id = oi.order_id AND o.status IN ('paid','packing','shipped','completed')
    JOIN products p ON p.id = oi.product_id
    GROUP BY p.community_id
) s ON s.community_id = c.id;
