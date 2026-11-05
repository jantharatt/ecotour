# 🌊 3 สายน้ำ 3 ศาสนา — เว็บไซต์นำเสนอชุมชน แหล่งท่องเที่ยว และร้านของฝาก

**Stack:** Laravel 12 · Livewire 3 · MySQL 8 · Tailwind CSS v4 · Leaflet + OpenStreetMap (แผนที่ฟรี ไม่ต้องใช้ API key)

> เว็บไซต์ไม่มีระบบจองทริป — เน้น **นำเสนอชุมชน แนะนำแหล่งท่องเที่ยวพร้อมตำแหน่งที่ตั้ง** และ **ขายสินค้าชุมชน**

---

## 1. ภาพรวมระบบ

| ส่วน | ความสามารถ |
|---|---|
| **หน้าแรก** | Hero “3 สายน้ำ 3 ศาสนา” · การ์ดศาสนสถาน 3 ศาสนา · 3 แม่น้ำ · แผนที่รวมทุกสถานที่ · สถานที่แนะนำ · ชุมชน · สินค้า |
| **เรื่องราว** (`/story`) | เนื้อหา 3 สายน้ำ (ปราการธรรมชาติรอบเกาะเมือง) และ 3 ศาสนา พร้อมลิงก์ไปแต่ละสถานที่ |
| **แหล่งท่องเที่ยว & แผนที่** (`/places`) | แผนที่หมุดสีตามศาสนา/ประเภท · กรองตามศาสนา ประเภท ชุมชน ค้นหาชื่อ · ปุ่ม **📍 ใกล้ฉัน** (ใช้ GPS ของผู้ใช้ เรียงสถานที่ตามระยะทาง กม.) · ปุ่ม **นำทาง** เปิด Google Maps |
| **หน้าสถานที่** (`/places/{slug}`) | รายละเอียด แกลเลอรี เวลาเปิด ค่าเข้าชม ช่วงเวลาแนะนำ ข้อควรปฏิบัติ · แผนที่ · ปุ่มนำทาง / คัดลอกพิกัด · **สถานที่ใกล้เคียง** พร้อมระยะทาง · ของฝากจากชุมชนเดียวกัน · นับยอดเข้าชม |
| **ชุมชน** (`/communities`) | เรื่องราว งานอนุรักษ์ แผนที่สถานที่ในชุมชน ช่องทางติดต่อ (โทร, LINE, Facebook) ปุ่มนำทางไปชุมชน · สินค้าของชุมชน |
| **ร้านของฝาก** | กรองหมวด/OTOP/ราคา · ตะกร้า · ค่าส่งตามน้ำหนัก (ส่งฟรีเมื่อครบ ฿1,000) · ชำระเงิน (พร้อมเพย์/โอน/ปลายทาง) · แนบสลิป · ติดตามสถานะ |
| **หลังบ้าน** (`/admin`) | แดชบอร์ด (ยอดขาย, สถานที่ยอดนิยม, สินค้าใกล้หมด, แจ้งเตือนสถานที่ที่ยังไม่มีพิกัด) · **จัดการสถานที่ พร้อมปักหมุดบนแผนที่** (คลิก/ลากหมุด/ใช้ตำแหน่งปัจจุบัน/พิมพ์พิกัด) · แกลเลอรีหลายรูป · จัดการชุมชน · สินค้า · คำสั่งซื้อ + เลขพัสดุ |

---

## 2. ฐานข้อมูล

```
communities ─┬─< attractions          (แหล่งท่องเที่ยว + พิกัด lat/lng)
             └─< products >── product_categories
users ──< orders ──< order_items >── products
```

| ตาราง | ฟิลด์สำคัญ |
|---|---|
| `communities` | name, slug, tagline, description, conservation, ตำบล/อำเภอ/จังหวัด, **lat, lng**, contact, facebook_url, line_id |
| `attractions` | community_id, type (`nature`/`viewpoint`/`culture`/`learning`/`food`/`homestay`), **faith** (`buddhism`/`christianity`/`islam`), summary, description, address, **lat, lng**, opening_hours, entry_fee, best_time, eco_tips, image, gallery (JSON), is_featured, views |
| `products` | price, stock, unit, weight_gram, story, is_otop |
| `orders` / `order_items` | สถานะ `pending → paid → packing → shipped → completed` (`cancelled` คืนสต็อกอัตโนมัติ) |

การคำนวณ “ใกล้ฉัน” และ “สถานที่ใกล้เคียง” ใช้สูตร Haversine ใน MySQL (`Attraction::scopeNearest`)

### ข้อมูลตั้งต้น (Seeder)
| ชุมชน | สถานที่ | พิกัด |
|---|---|---|
| ชุมชนสำเภาล่ม | 🛕 วัดพุทไธศวรรย์ | 14.33952, 100.55823 |
| ชุมชนสำเภาล่ม | ⛪ วัดนักบุญยอแซฟ | 14.33865, 100.54803 |
| ชุมชนคลองตะเคียน | 🕌 มัสยิดกุฎีช่อฟ้า | 14.32387, 100.56749 |
| ย่านบางกะจะ | 🛕 วัดพนัญเชิงวรวิหาร | 14.34417, 100.57889 |
| ย่านบางกะจะ | 🏞️ ป้อมเพชร (จุดชมแม่น้ำ 3 สายบรรจบ) | 14.34620, 100.57653 |

> พิกัดอ้างอิงจาก Wikipedia / OpenStreetMap — แนะนำให้ตรวจซ้ำในหลังบ้านก่อนเปิดใช้จริง
> **สินค้าใน seeder เป็นข้อมูลตัวอย่าง** ให้แทนที่ด้วยสินค้าจริงของชุมชน

---

## 3. ธีมสี (สบายตา สดใส)

| ชื่อ | สี | ใช้กับ |
|---|---|---|
| `lagoon` | `#2AA5C4` | สายน้ำ โลโก้ ลิงก์ |
| `leaf` | `#2F9660` | สีหลัก ปุ่มหลัก |
| `sun` | `#F28C28` | ปุ่มนำทาง/ซื้อ ราคา |
| `cream` | `#FBF8F1` | พื้นหลังถนอมสายตา |
| หมุดศาสนา | พุทธ `#D97706` · คริสต์ `#2563EB` · อิสลาม `#059669` | แผนที่และป้าย |

ฟอนต์ Prompt (หัวข้อ) + Sarabun (เนื้อหา)

---

## 4. ติดตั้ง

```bash
composer create-project laravel/laravel ayutthaya-3rivers
cd ayutthaya-3rivers
composer require livewire/livewire
npm install
npm install leaflet

# คัดลอกโฟลเดอร์ app, bootstrap, database, resources, routes จากชุดนี้ทับลงโปรเจกต์
```

`.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ayutthaya_3rivers
DB_USERNAME=root
DB_PASSWORD=
APP_LOCALE=th
APP_TIMEZONE=Asia/Bangkok
PROMPTPAY_ID=
PROMPTPAY_NAME=
```

กำหนด `PROMPTPAY_ID` และ `PROMPTPAY_NAME` เป็นข้อมูลพร้อมเพย์จริงใน `.env`; ระบบอ่านค่าผ่าน `config/services.php`

```bash
# วิธี A: ใช้ migration
php artisan migrate --seed
# วิธี B: นำเข้าไฟล์ SQL สำเร็จรูปแทน (phpMyAdmin → Import หรือคำสั่งด้านล่าง) แล้วข้าม migrate
#   mysql -u root -p < database/ayutthaya_3rivers.sql
#   (ไฟล์สร้างฐานข้อมูลชื่อ ayutthaya_3rivers ให้เอง ตั้ง DB_DATABASE ให้ตรงกัน)

php artisan storage:link
npm run dev        # หรือ npm run build
php artisan serve
```

- ปุ่ม “ใกล้ฉัน” ต้องเปิดผ่าน **HTTPS** (หรือ localhost) เบราว์เซอร์จึงอนุญาตให้ใช้ตำแหน่ง
- ใส่ QR พร้อมเพย์จริงที่ `public/images/promptpay.png`

**บัญชีทดสอบ** — ผู้ดูแล `admin@ecotour.test` / `password` · ลูกค้า `user@ecotour.test` / `password`

---

## 5. โครงสร้างไฟล์หลัก

```
app/Livewire/
  Home.php
  Places/{Index,Show}.php        ← แผนที่ ใกล้ฉัน กรองศาสนา
  Communities/{Index,Show}.php
  Shop/{Index,Show}.php · Cart/{Page,Counter}.php · Checkout.php
  Account/{Dashboard,OrderShow}.php · Auth/{Login,Register}.php
  Admin/{Dashboard,Places,Communities,Products,Orders}.php
app/Models/  Community, Attraction, Product, ProductCategory, Order, OrderItem, User
app/Services/CartService.php
resources/js/app.js              ← Alpine: placesMap, locationPicker (Leaflet)
resources/css/app.css            ← ธีมสี + สไตล์หมุดแผนที่
resources/views/pages/story.blade.php
resources/views/partials/        map, location-picker, place-card, product-card, ...
```

## 6. แนวทางต่อยอด
- วาดเส้นทางเดินเที่ยว 3 ศาสนา (polyline) และเส้นแม่น้ำบนแผนที่
- รองรับหลายภาษา (EN/中文) สำหรับนักท่องเที่ยวต่างชาติ
- QR Code ติดหน้าสถานที่จริง ลิงก์มายังหน้าข้อมูล
- ระบบชำระเงินออนไลน์อัตโนมัติ · แจ้งเตือนออเดอร์ทาง LINE
- สิทธิ์ `member` ให้แต่ละชุมชนจัดการสถานที่/สินค้าของตนเอง
